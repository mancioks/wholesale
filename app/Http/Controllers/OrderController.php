<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrderRequest;
use App\Http\Requests\ShortageRequest;
use App\Mail\Admin\OrderCanceled;
use App\Mail\Admin\OrderCreated;
use App\Mail\Customer\OrderPrepared;
use App\Mail\Customer\OrderAccepted;
use App\Mail\Customer\OrderDeclined;
use App\Mail\Customer\OrderDone;
use App\Mail\Customer\OrderPreparing;
use App\Mail\Customer\OrderTaken;
use App\Mail\Warehouse\OrderReceived;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\MailService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $search_query = $request->get('query');
        $products = Product::search($search_query)->orderBy('id', 'desc')->paginate(8);
        return view('order.create', compact('products', 'search_query'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $paymentMethods = PaymentMethod::all();
        return view('order.show', compact('order', 'paymentMethods'));
    }

    public function review()
    {
        $payment_methods = PaymentMethod::all();
        $warehouses = Warehouse::where('active', true)->get();

        return view('order.review', compact('payment_methods', 'warehouses'));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('home')->with('status', 'Deleted successfully');
    }

    public function shortage(Order $order, ShortageRequest $request)
    {
        $stocks = array_combine($request->post('product'), $request->post('stock'));
        $orderItems = $order->items()->pluck('id')->toArray();

        foreach ($stocks as $itemId => $stock) {
            if(in_array($itemId, $orderItems)) {
                OrderItem::query()->where('id', $itemId)->update(['stock' => $stock]);
            }
        }

        return redirect()->back()->with('status', 'OK');
    }

    public function confirm(ConfirmOrderRequest $request, OrderService $orderService)
    {
        if (auth()->user()->details()->doesntExist()) {
            return redirect()->route('user.settings');
        }

        $user = auth()->user();
        if ($user->acting()->exists()) {
            $user = $user->acting;
        }

        $order = $orderService->create($request);
        $orderService->attachProducts($order);

        $recipients = User::ofRole(Role::ADMIN)->get();
        foreach ($recipients as $recipient) {
            if($recipient->get_emails) {
                Mail::to($recipient)->send(new OrderCreated($order));
            }
        }

        if ($order->user->get_emails) {
            Mail::to($order->user)->send(new \App\Mail\Customer\OrderCreated($order));
        }

        $recipients = User::ofRole(Role::SUPER_ADMIN)->get();

        $orderCreatedBy = $user->name;
        if (auth()->user()->acting()->exists()) {
            $orderCreatedBy = $orderCreatedBy . ' (admin: '.auth()->user()->name.')';
        }

        MailService::send($recipients, 'Sukurtas naujas užsakymas', sprintf('Užsakymą %s sukūrė %s', $order->number, $orderCreatedBy), ['order' => $order]);

        if (auth()->user()->acting()->exists()) {
            auth()->user()->update(['acting_as' => null]);

            return redirect()->route('order.show', $order)->with('status', sprintf(__('Order created for %s'), $user->name));
        }

        return redirect()->route('order.success', $order)->with('status', 'Order created');
    }

    public function orderSuccess(Order $order)
    {
        return view('order.success', compact('order'));
    }

    public function setStatus(Order $order, Status $status)
    {
        if (!array_key_exists($status->id, $order->actions)) {
            return redirect()->back()->withErrors(['Cannot change status, try again']);
        }

        $order->update([
            'status_id' => $status->id,
        ]);

        switch ($status->id) {
            case Status::CANCELED:
                $recipients = User::ofRole(Role::ADMIN)->get();
                foreach ($recipients as $recipient) {
                    if ($recipient->get_emails) {
                        Mail::to($recipient)->send(new OrderCanceled($order));
                    }
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas atšauktas', sprintf('Užsakymą %s atšaukė %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;

            case Status::DECLINED:
                if($order->user->get_emails) {
                    Mail::to($order->user)->send(new OrderDeclined($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas atmestas', sprintf('Užsakymą %s atmetė %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;

            case Status::ACCEPTED:
                if($order->user->get_emails) {
                    Mail::to($order->user)->send(new OrderAccepted($order));
                }

                $recipients = User::ofRole(Role::WAREHOUSE)->get();
                foreach ($recipients as $recipient) {
                    if($recipient->get_emails) {
                        Mail::to($recipient)->send(new OrderReceived($order));
                    }
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas priimtas', sprintf('Užsakymą %s priėmė %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;

            case Status::PREPARING:
                if($order->user->get_emails) {
                    Mail::to($order->user)->send(new OrderPreparing($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas pradėtas ruošti', sprintf('Užsakymą %s pradėjo ruošti %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;

            case Status::PREPARED:
                if($order->user->get_emails) {
                    Mail::to($order->user)->send(new OrderPrepared($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas suruoštas', sprintf('Užsakymą %s suruošė %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;

            case Status::TAKEN:
                if($order->user->get_emails) {
                    Mail::to($order->user)->send(new OrderTaken($order));
                }

                $recipients = User::ofRole(Role::ADMIN)->get();
                foreach ($recipients as $recipient) {
                    if($recipient->get_emails) {
                        Mail::to($recipient)->send(new \App\Mail\Admin\OrderTaken($order));
                    }
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas atsiimtas', sprintf('Užsakymą %s pažymėjo kaip atsiimtą %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;

            case Status::DONE:
                $order->update(['vat_number' => Setting::get('invoice')]);
                $order->refresh();
                Setting::inc('invoice');

                if($order->user->get_emails) {
                    Mail::to($order->user)->send(new OrderDone($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas užbaigtas', sprintf('Užsakymą %s užbaigė %s', $order->number, auth()->user()->name), ['order' => $order]);

                break;
        }

        return redirect()->back()->with('status', 'Order status updated');
    }
}
