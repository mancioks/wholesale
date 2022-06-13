<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $roles = ['customer', 'warehouse', 'admin'];

         foreach ($roles as $role) {
             Role::query()->create([
                 'name' => $role,
             ]);
         }
    }
}
