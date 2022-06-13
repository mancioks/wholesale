<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            'bank_transfer' => 'Bankinis pavedimas',
            'cash' => 'Grynieji pinigai atsiimant',
        ];

        foreach ($paymentMethods as $key => $name) {
            PaymentMethod::query()->create(['key' => $key, 'name' => $name]);
        }
    }
}
