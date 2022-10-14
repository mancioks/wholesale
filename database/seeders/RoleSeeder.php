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
        $roles = ['customer', 'warehouse', 'admin', 'super_admin', 'installer'];

        foreach ($roles as $role) {
            if (Role::query()->where(['name' => $role])->doesntExist()) {
                Role::query()->create([
                    'name' => $role,
                ]);
            }
        }
    }
}
