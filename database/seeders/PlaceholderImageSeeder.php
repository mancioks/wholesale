<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlaceholderImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Image::query()->create([
            'name' => Setting::get('images.path').'placeholder.jpg',
        ]);
    }
}
