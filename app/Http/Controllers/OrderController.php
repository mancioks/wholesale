<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrderRequest;
use App\Http\Requests\ShortageRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Mail\Admin\OrderCanceled;
use App\Mail\Admin\OrderCreated;
use App\Mail\Customer\OrderPrepared;
use App\Mail\Customer\OrderAccepted;
use App\Mail\Customer\OrderDeclined;
use App\Mail\Customer\OrderDone;
use App\Mail\Customer\OrderPreparing;
use App\Mail\Customer\OrderTaken;
use App\Mail\Warehouse\OrderReceived;
use App\Models\Category;
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
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $search_query = $request->get('query');
        $warehouses = Warehouse::where('active', true)->get();

        if (auth()->user()->acting()->exists()) {
            $products = auth()->user()->warehouse->products()
                ->where('name', 'LIKE', '%'.$search_query.'%')
                ->orderBy('id', 'desc')
                ->paginate(12);
        } else {
            $products = Product::query()
                ->with('warehouses')
                ->whereHas('warehouses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse->id)->where('enabled', true);
                })
                ->where('type', Product::PRODUCT_TYPE_REGULAR)
                ->where('name', 'LIKE', '%'.$search_query.'%')
                ->orWhereHas('warehouses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse->id)->where('enabled', true);
                })
                ->where('type', Product::PRODUCT_TYPE_REGULAR)
                ->where(['id' => $search_query])
                ->orderBy('id', 'desc')
                ->paginate(12);
        }

        $userPopularProducts = UserService::getUserPopularProducts(auth()->user(), 4);

        return view('order.create', compact('products', 'search_query', 'warehouses', 'userPopularProducts'));
    }

    public function new(Request $request)
    {
        $search_query = $request->get('query');
        $warehouses = Warehouse::where('active', true)->get();

        if (auth()->user()->acting()->exists()) {
            $products = auth()->user()->warehouse->products()
                ->where('name', 'LIKE', '%'.$search_query.'%')
                ->orderBy('id', 'desc')
                ->paginate(12);
        } else {
            $products = Product::query()
                ->with('warehouses')
                ->whereHas('warehouses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse->id)->where('enabled', true);
                })
                ->where('type', Product::PRODUCT_TYPE_REGULAR)
                ->where('name', 'LIKE', '%'.$search_query.'%')
                ->orWhereHas('warehouses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse->id)->where('enabled', true);
                })
                ->where('type', Product::PRODUCT_TYPE_REGULAR)
                ->where(['id' => $search_query])
                ->orderBy('id', 'desc')
                ->paginate(12);
        }

        $userPopularProducts = UserService::getUserPopularProducts(auth()->user(), 4);
        $categories = Category::where('slug', '!=', 'master')->get();

        return view('order.new', compact('products', 'search_query', 'warehouses', 'userPopularProducts', 'categories'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $paymentMethods = PaymentMethod::all();
        return view('order.show', compact('order', 'paymentMethods'));
    }

    public function edit(Order $order)
    {
        $warehouses = Warehouse::all();
        $paymentMethods = PaymentMethod::all();

        return view('order.edit', compact('order', 'warehouses', 'paymentMethods'));
    }

    public function update(Order $order, UpdateOrderRequest $request)
    {
        $order->update([
            'payment_method_id' => $request->post('payment_method_id'),
            'warehouse_id' => $request->post('warehouse_id'),
        ]);

        $updatedProducts = $request->post('product');

        if ($updatedProducts) {
            foreach ($updatedProducts as $productId => $updatedProduct) {
                $orderItem = OrderItem::query()->where(['id' => $productId])->firstOrFail();
                $orderItem->update($updatedProduct);
            }
        }

        $order = OrderService::recalculate($order);

        return redirect()->route('order.show', $order)->with('status', 'Order updated');
    }

    public function removeItem(OrderItem $item)
    {
        $order = $item->order;
        $item->delete();

        OrderService::recalculate($order);

        return redirect()->route('order.edit', $order)->with('status', 'Item removed');
    }

    public function addItem(Order $order, Product $product)
    {
        $orderUser = $order->user;
        $userPrice = $product->priceUsers()->where(['user_id' => $orderUser->id]);

        $price = $product->original_price;
        if ($userPrice->exists()) {
            $price = $userPrice->first()->pivot->price;
        }

        OrderItem::query()->create([
            'order_id' => $order->id,
            'name' => $product->name,
            'price' => $price,
            'product_id' => $product->id,
            'qty' => 1,
            'units' => $product->units,
            'prime_cost' => $product->prime_cost,
            'code' => $product->code,
        ]);

        OrderService::recalculate($order);

        return redirect()->back()->with('status', 'Product added');
    }

    public function review()
    {
        $payment_methods = PaymentMethod::all();
        $warehouses = Warehouse::where('active', true)->get();
        $user = auth()->user()->acting()->exists() ? auth()->user()->acting : auth()->user();

        return view('order.review', compact('payment_methods', 'warehouses', 'user'));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.order.index')->with('status', 'Deleted successfully');
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

                $order->update([
                    'accepted_by' => auth()->user()->id,
                ]);

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
