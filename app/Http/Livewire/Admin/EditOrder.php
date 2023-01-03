<?php

namespace App\Http\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Product;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Status;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EditOrder extends Component
{
    public Collection $products;
    public $productQty;
    public $searchQuery;
    public $customers;
    public $paymentMethods;
    public $warehouses;
    public $searchResults;
    public $selectedWarehouse;
    public $selectedCustomer;
    public $selectedPaymentMethod;
    public $preInvoiceRequired;
    public $addPvm;
    public $subTotal;
    public $total;
    public $waybillRequired;

    public $name;
    public $companyName;
    public $companyCode;
    public $companyPhone;
    public $email;
    public $address;
    public $pvmCode;
    public $message;

    public $success;

    public $orderType;

    public $order;

    public $orderTypes = [
        Order::ORDER_TYPE_NORMAL => 'Normal',
        Order::ORDER_TYPE_ISSUE => 'Issue',
    ];

    public function mount(Order $order)
    {
        $this->order = $order;

        $this->products = new Collection();
        $this->productQty = [];
        $this->searchQuery = '';
        $this->searchResults = new Collection();
        $this->customers = User::all();
        $this->paymentMethods = PaymentMethod::all();
        $this->warehouses = Warehouse::where('active', true)->get();
        $this->preInvoiceRequired = false;
        $this->addPvm = true;
        $this->subTotal = 0;
        $this->total = 0;
        $this->success = false;
        $this->orderType = Order::ORDER_TYPE_NORMAL;
        $this->waybillRequired = false;

        $this->setOrder($order);
    }

    private function setOrder(Order $order)
    {
        $this->selectedCustomer = $order->user_id;
        $this->selectedPaymentMethod = $order->payment_method_id;
        $this->selectedWarehouse = $order->warehouse_id;
        $this->preInvoiceRequired = $order->pre_invoice_required;
        $this->addPvm = $order->pvm;
        $this->subTotal = $order->sub_total;
        $this->total = $order->total;
        $this->waybillRequired = $order->waybill_required;
        $this->orderType = $order->order_type;
        $this->name = $order->customer_name;
        $this->companyName = $order->customer_company_name;
        $this->companyCode = $order->customer_company_registration_code;
        $this->companyPhone = $order->customer_company_phone_number;
        $this->email = $order->customer_email;
        $this->address = $order->customer_company_address;
        $this->pvmCode = $order->customer_company_vat_number;
        $this->message = $order->message;


        foreach ($order->items as $item) {
            $this->productQty[$item->product_id] = $item->qty;
            $product = Product::find($item->product_id);

            if (!$product) {
                continue;
            }

            $this->products->push($product);
        }

        $this->recalculateTotal();
    }

    public function getUserProperty()
    {
        return User::find($this->selectedCustomer);
    }

    public function updatedOrderType()
    {
        if ($this->orderType === Order::ORDER_TYPE_ISSUE) {
            $this->customers = User::where('role_id', Role::INSTALLER)->get();
            $this->waybillRequired = true;
        } else {
            $this->customers = User::all();
            $this->waybillRequired = false;
        }

        $this->reset(['selectedCustomer', 'selectedPaymentMethod']);
    }

    public function updatedProductQty()
    {
        $this->recalculateTotal();
    }

    public function updatedAddPvm()
    {
        $this->recalculateTotal();
    }

    private function recalculateTotal()
    {
        $this->subTotal = 0;
        $this->total = 0;

        foreach ($this->products as $product) {
            if (!array_key_exists($product->id, $this->productQty) || (int)$this->productQty[$product->id] < 1) {
                if ($this->productQty[$product->id] !== "") {
                    $this->productQty[$product->id] = 1;
                }
            }

            if ($this->productQty[$product->id] !== "") {
                $this->subTotal += $product->original_price * (int)$this->productQty[$product->id];
            }
        }

        $this->total = $this->subTotal;

        if ($this->addPvm) {
            $this->total *= setting('pvm') / 100 + 1;
        }
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
        $this->success = false;
    }

    public function updatedSearchQuery()
    {
        if ($this->searchQuery && $this->searchQuery !== '') {
            $this->searchResults = Product::search($this->searchQuery)->take(8)->get();
        } else {
            $this->searchResults = new Collection();
        }
    }

    public function add($productId)
    {
        $this->searchQuery = '';
        $this->searchResults = new Collection();

        $product = Product::find($productId);
        if (!$this->products->find($product)) {
            $this->products->add($product);
            $this->productQty[$product->id] = 1;
        } else {
            if (array_key_exists($product->id, $this->productQty)) {
                $this->productQty[$product->id]++;
            } else {
                $this->productQty[$product->id] = 1;
            }
        }

        $this->recalculateTotal();
    }

    public function remove($productId)
    {
        $this->products = $this->products->keyBy('id')->forget($productId);

        $this->recalculateTotal();
    }

    public function submit()
    {
        if ($this->orderType === Order::ORDER_TYPE_NORMAL) {
            $this->validate([
                'selectedWarehouse' => 'required',
                'selectedCustomer' => 'required',
                'selectedPaymentMethod' => 'required',
                'products' => '',
                'productQty.*' => 'required|integer|min:1',
            ]);
        } else {
            $this->validate([
                'selectedWarehouse' => 'required',
                'selectedCustomer' => 'required',
                'products' => '',
                'productQty.*' => 'required|integer|min:1',
            ]);
        }

        $this->recalculateTotal();

        $this->editOrder();

        $this->emit('orderUpdated');
        $this->success = true;
    }

    private function editOrder()
    {
        $this->order->update([
            'user_id' => $this->user->id,
            'discount' => 0,
            'pvm' => $this->addPvm ? Setting::get('pvm') : 0,
            'total' => $this->total,
            'payment_method_id' => $this->selectedPaymentMethod,
            'vat_number' => 0,
            'warehouse_id' => $this->selectedWarehouse,
            'message' => $this->message ?: '',
            'company_details' => setting('company.details'),
            'customer_name' => $this->name ?: $this->user->name,
            'customer_email' => $this->email ?: $this->user->email,
            'customer_company_name' => $this->companyName ?: '',
            'customer_company_address' => $this->address ?: '',
            'customer_company_registration_code' => $this->companyCode ?: '',
            'customer_company_vat_number' => $this->pvmCode ?: '',
            'customer_company_phone_number' => $this->companyPhone ?: '',
            'pre_invoice_required' => $this->preInvoiceRequired,
            'order_type' => $this->orderType,
            'waybill_required' => $this->waybillRequired,
        ]);

        $order = $this->order;
        $order->items()->delete();

        foreach ($this->products as $product) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                'name' => $product->name,
                'price' => $product->original_price,
                'product_id' => $product->id,
                'qty' => $this->productQty[$product->id],
                'units' => $product->units,
                'prime_cost' => $product->prime_cost,
                'code' => $product->code,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.edit-order');
    }
}
