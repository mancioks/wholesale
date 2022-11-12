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
                'title' => 'PVM',
                'type' => 'number',
                'edit' => 1,
            ],
            'products.path' => [
                'value' => 'storage/uploads/',
                'title' => 'Product images path',
                'type' => 'text',
            ],
            'images.path' => [
                'value' => 'images/',
                'title' => 'Images path',
                'type' => 'text',
            ],
            'signatures.path' => [
                'value' => 'storage/uploads/',
                'title' => 'Signatures path',
                'type' => 'text',
            ],
            'logo' => [
                'value' => 'images/vandenvala.png',
                'title' => 'Logo',
                'type' => 'text',
            ],
            'logo.white' => [
                'value' => 'images/vandenvala-white.png',
                'title' => 'Logo white',
                'type' => 'text',
            ],
            'company.details' => [
                'value' => 'Company, UAB',
                'title' => 'Company details',
                'type' => 'textarea',
                'edit' => 1,
            ],
            'invoice' => [
                'value' => '1',
                'title' => 'Next invoice number',
                'type' => 'number',
                'edit' => 1,
            ],
        ];

        foreach ($settings as $name => $setting) {
            if (Setting::query()->where(['name' => $name])->doesntExist()) {
                Setting::query()->create([
                    'name' => $name,
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'title' => $setting['title'],
                    'edit' => $setting['edit'] ?? 0,
                ]);
            }
        }
    }
}
