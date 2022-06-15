<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmOrderRequest;
use App\Mail\OrderCreated;
use App\Mail\OrderCreatedTwo;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $search_query = $request->get('query');
        $products = Product::search($search_query)->orderBy('id', 'desc')->paginate(6);
        return view('order.create', compact('products', 'search_query'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function review()
    {
        $payment_methods = PaymentMethod::all();
        return view('order.review', compact('payment_methods'));
    }

    public function confirm(ConfirmOrderRequest $request, OrderService $orderService)
    {
        $order = $orderService->create($request);
        $orderService->attachProducts($order);

        $admins = User::ofRole(Role::ADMIN)->get();
        foreach ($admins as $recipient) {
            Mail::to($recipient)->send(new OrderCreated($order));
        }

        return redirect()->route('home')->with('status', 'order ok');
    }

    public function cancel(Order $order)
    {
        if(!$order->can_cancel) {
            return redirect()->route('order.show', $order)->withErrors(['Too late']);
        }

        $order->update([
            'status_id' => Status::where('key', 'canceled')->first()->id,
        ]);
        return redirect()->route('order.show', $order)->with('status', 'Order canceled!');
    }
}
