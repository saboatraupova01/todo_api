<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Easy',
            'Medium',
            'Hard',
        ];

        foreach ($categories as $category) {

            Category::updateOrCreate(
                [
                    'name' => $category
                ]
            );

        }
    }
}
