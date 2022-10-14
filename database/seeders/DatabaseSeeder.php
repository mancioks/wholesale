<?php

namespace Database\Seeders;

use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            RoleSeeder::class,
            StatusSeeder::class,
            StatusTakenSeeder::class,
            PaymentMethodSeeder::class,
            PlaceholderImageSeeder::class,
            PaymentStatusSeeder::class,
            UserSeeder::class,
        ]);
    }
}
