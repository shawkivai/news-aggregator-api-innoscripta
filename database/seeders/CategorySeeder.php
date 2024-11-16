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
            ['id' => 1, 'name' => 'Politics', 'status' => true, 'created_at' => now()],
            ['id' => 2, 'name' => 'Technology', 'status' => true, 'created_at' => now()],
            ['id' => 3, 'name' => 'Entertainment', 'status' => true, 'created_at' => now()],
            ['id' => 4, 'name' => 'Sports', 'status' => true, 'created_at' => now()],
            ['id' => 5, 'name' => 'Science', 'status' => true, 'created_at' => now()],
            ['id' => 6, 'name' => 'Health', 'status' => true, 'created_at' => now()],
            ['id' => 7, 'name' => 'Travel', 'status' => true, 'created_at' => now()],
            ['id' => 8, 'name' => 'Business', 'status' => true, 'created_at' => now()],
            ['id' => 9, 'name' => 'Food', 'status' => true, 'created_at' => now()],
            ['id' => 10, 'name' => 'Fashion', 'status' => true, 'created_at' => now()],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['id' => $category['id']], $category);
        }
    }
}
