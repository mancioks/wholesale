<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Category::query()->where('slug', 'master')->exists()) {
            return;
        }

        Category::query()->create([
            'name' => 'Master',
            'slug' => 'master',
            'position' => 1,
        ]);
    }
}
