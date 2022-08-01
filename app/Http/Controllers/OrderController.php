<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrderRequest;
use App\Mail\Admin\OrderCanceled;
use App\Mail\Admin\OrderCreated;
use App\Mail\Customer\OrderPrepared;
use App\Mail\Customer\OrderAccepted;
use App\Mail\Customer\OrderDeclined;
use App\Mail\Customer\OrderDone;
use App\Mail\Customer\OrderPreparing;
use App\Mail\CustomMail;
use App\Mail\Warehouse\OrderReceived;
use App\Models\Order;
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

    public function confirm(ConfirmOrderRequest $request, OrderService $orderService)
    {
        if(auth()->user()->details()->doesntExist()) {
            return redirect()->route('user.settings');
        }

        $order = $orderService->create($request);
        $orderService->attachProducts($order);

        $recipients = User::ofRole(Role::ADMIN)->get();
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new OrderCreated($order));
        }

        Mail::to($order->user)->send(new \App\Mail\Customer\OrderCreated($order));

        $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
        MailService::send($recipients, 'Sukurtas naujas užsakymas', sprintf('Užsakymą %s sukūrė %s', $order->number, auth()->user()->name));

        return redirect()->route('order.success', $order)->with('status', 'Order created');
    }

    public function orderSuccess(Order $order)
    {
        return view('order.success', compact('order'));
    }

    public function setStatus(Order $order, Status $status)
    {
        if(!array_key_exists($status->id, $order->actions)) {
            return redirect()->back()->withErrors(['Cannot change status, try again']);
        }

        $order->update([
            'status_id' => $status->id,
        ]);

        switch ($status->id) {
            case Status::CANCELED:
                $recipients = User::ofRole(Role::ADMIN)->get();
                foreach ($recipients as $recipient) {
                    Mail::to($recipient)->send(new OrderCanceled($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas atšauktas', sprintf('Užsakymą %s atšaukė %s', $order->number, auth()->user()->name));

                break;

            case Status::DECLINED:
                Mail::to($order->user)->send(new OrderDeclined($order));

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas atmestas', sprintf('Užsakymą %s atmetė %s', $order->number, auth()->user()->name));

                break;

            case Status::ACCEPTED:
                Mail::to($order->user)->send(new OrderAccepted($order));

                $recipients = User::ofRole(Role::WAREHOUSE)->get();
                foreach ($recipients as $recipient) {
                    Mail::to($recipient)->send(new OrderReceived($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas priimtas', sprintf('Užsakymą %s priėmė %s', $order->number, auth()->user()->name));

                break;

            case Status::PREPARING:
                Mail::to($order->user)->send(new OrderPreparing($order));

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas pradėtas ruošti', sprintf('Užsakymą %s pradėjo ruošti %s', $order->number, auth()->user()->name));

                break;

            case Status::PREPARED:
                Mail::to($order->user)->send(new OrderPrepared($order));

                $recipients = User::ofRole(Role::ADMIN)->get();
                foreach ($recipients as $recipient) {
                    Mail::to($recipient)->send(new \App\Mail\Admin\OrderPrepared($order));
                }

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas suruoštas', sprintf('Užsakymą %s suruošė %s', $order->number, auth()->user()->name));

                break;

            case Status::DONE:
                $order->update(['vat_number' => Setting::get('invoice')]);
                $order->refresh();
                Setting::inc('invoice');

                Mail::to($order->user)->send(new OrderDone($order));

                $recipients = User::ofRole(Role::SUPER_ADMIN)->get();
                MailService::send($recipients, 'Užsakymas užbaigtas', sprintf('Užsakymą %s užbaigė %s', $order->number, auth()->user()->name));

                break;
        }

        return redirect()->back()->with('status', 'Order status updated');
    }
}
