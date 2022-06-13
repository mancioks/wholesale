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
        // mantas.ins@gmail.com admin123 ADMIN
        User::factory(1)->create([
            'email' => 'mantas.ins@gmail.com',
            'role_id' => Role::ADMIN,
        ]);

        // sandelis@lzt.lt admin123 WAREHOUSE
        User::factory(1)->create([
            'email' => 'sandelis@lzt.lt',
            'role_id' => Role::WAREHOUSE,
        ]);

        // test@test.lt admin123 CUSTOMER
        User::factory(1)->create([
            'email' => 'test@test.lt',
        ]);
    }
}
