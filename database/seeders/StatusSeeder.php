<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'created' => 'Created',
            'accepted' => 'Accepted',
            'declined' => 'Declined',
            'preparing' => 'Preparing',
            'prepared' => 'Prepared',
            'done' => 'Done',
        ];

        foreach ($statuses as $key => $name) {
            Status::query()->create([
                'key' => $key,
                'name' => $name,
            ]);
        }
    }
}
