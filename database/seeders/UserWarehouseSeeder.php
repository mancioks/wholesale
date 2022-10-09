<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $warehouse = Warehouse::query()->first();

        foreach ($users as $user) {
            if (!$user->warehouse()->exists()) {
                $user->update(['warehouse_id' => $warehouse->id]);
            }
        }
    }
}
