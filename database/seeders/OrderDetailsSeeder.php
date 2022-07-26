<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $order->update([
                'company_details' => setting('company.details'),
                'customer_name' => $order->user->name,
                'customer_email' => $order->user->email,
                'customer_company_name' => $order->user->details->company_name,
                'customer_company_address' => $order->user->details->address,
                'customer_company_registration_code' => $order->user->details->registration_code,
                'customer_company_vat_number' => $order->user->details->vat_number,
                'customer_company_phone_number' => $order->user->details->phone_number,
            ]);
        }
    }
}
