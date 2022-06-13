<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentStatuses = [
            'waiting' => 'Waiting for payment',
            'paid' => 'Paid',
            'partly_paid' => 'Partly paid',
        ];

        foreach ($paymentStatuses as $key => $name) {
            PaymentStatus::query()->create([
                'name' => $name,
                'key' => $key,
            ]);
        }
    }
}
