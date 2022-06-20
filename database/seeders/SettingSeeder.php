<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'pvm' => [
                'value' => '21',
                'title' => 'PVM'
            ],
            'products.path' => [
                'value' => 'storage/uploads/',
                'title' => 'Product images path'
            ],
            'images.path' => [
                'value' => 'images/',
                'title' => 'Images path'
            ],
            'logo' => [
                'value' => 'images/vandenvala.png',
                'title' => 'Logo'
            ],
        ];

        foreach ($settings as $name => $setting) {
            Setting::set($name, $setting['value'], $setting['title']);
        }
    }
}
