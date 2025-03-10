<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Android Phone',
                'description' => 'Services for Android smartphones',
            ],
            [
                'name' => 'IOS Phone',
                'description' => 'Services for Apple iPhones',
            ],
            [
                'name' => 'Laptop',
                'description' => 'Services for laptop computers',
            ],
            [
                'name' => 'Computer',
                'description' => 'Services for desktop computers',
            ],
            [
                'name' => 'Printer',
                'description' => 'Services for printing devices',
            ],
            [
                'name' => 'Mac',
                'description' => 'Services for Apple Mac computers',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
