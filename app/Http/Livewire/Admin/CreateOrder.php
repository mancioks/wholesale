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

class CreateOrder extends Component
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

    public $orderTypes = [
        Order::ORDER_TYPE_NORMAL => 'Normal',
        Order::ORDER_TYPE_ISSUE => 'Issue',
    ];

    public function mount()
    {
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
        $this->success = false;
        $this->subTotal = 0;
        $this->total = 0;

        foreach ($this->products as $product) {
            if (!array_key_exists($product->id, $this->productQty) || (int)$this->productQty[$product->id] < 1) {
                $this->productQty[$product->id] = 1;
            }

            $this->subTotal += $product->original_price * (int)$this->productQty[$product->id];
        }

        $this->total = $this->subTotal;

        if ($this->addPvm) {
            $this->total *= setting('pvm') / 100 + 1;
        }
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
                'products' => 'required',
            ]);
        } else {
            $this->validate([
                'selectedWarehouse' => 'required',
                'selectedCustomer' => 'required',
                'products' => 'required',
            ]);
        }

        $this->recalculateTotal();

        $this->createOrder();
        $this->resetFields();

        $this->emit('orderCreated');
        $this->success = true;
    }

    private function resetFields()
    {
        $this->mount();
        $this->selectedCustomer = null;
        $this->selectedWarehouse = null;
        $this->selectedPaymentMethod = null;
        $this->addPvm = null;
        $this->preInvoiceRequired = null;
        $this->name = '';
        $this->companyName = '';
        $this->companyCode = '';
        $this->companyPhone = '';
        $this->email = '';
        $this->address = '';
        $this->pvmCode = '';
        $this->message = '';
    }

    private function createOrder()
    {
        $statusId = Status::CREATED;

        if ($this->orderType === Order::ORDER_TYPE_ISSUE) {
            $statusId = Status::PREPARED;
        }

        $order = Order::create([
            'user_id' => $this->user->id,
            'status_id' => $statusId,
            'discount' => 0,
            'pvm' => $this->addPvm ? Setting::get('pvm') : 0,
            'total' => $this->total,
            'payment_method_id' => $this->selectedPaymentMethod,
            'payment_status_id' => PaymentStatus::PAID,
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
            'created_by' => auth()->user()->id,
            'order_type' => $this->orderType,
            'waybill_required' => $this->waybillRequired,
        ]);

        foreach ($this->products as $product) {
            OrderItem::query()->create([
                'order_id' => $order->id,
                'name' => $product->name,
                'price' => $product->original_price,
                'product_id' => $product->id,
                'qty' => $this->productQty[$product->id],
                'units' => $product->units,
                'prime_cost' => $product->prime_cost,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.create-order');
    }
}
