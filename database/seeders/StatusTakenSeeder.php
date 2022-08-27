<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTakenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'taken' => 'Taken',
        ];

        foreach ($statuses as $key => $name) {
            Status::query()->create([
                'key' => $key,
                'name' => $name,
            ]);
        }
    }
}
