<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin@lzt.lt admin123 ADMIN
        User::factory(1)->create([
            'name' => 'Administratorius',
            'email' => 'admin@lzt.lt',
            'role_id' => Role::ADMIN,
        ]);

        // warehouse@lzt.lt admin123 WAREHOUSE
        User::factory(1)->create([
            'name' => 'Sandėlininkas',
            'email' => 'warehouse@lzt.lt',
            'role_id' => Role::WAREHOUSE,
        ]);

        // customer@lzt.lt admin123 CUSTOMER
        User::factory(1)->create([
            'name' => 'Klientas',
            'email' => 'customer@lzt.lt',
        ]);
    }
}
