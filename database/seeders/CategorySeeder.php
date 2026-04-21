<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fiction', 'icon' => 'fa-book', 'description' => 'Fictional books including novels and stories'],
            ['name' => 'Non-Fiction', 'icon' => 'fa-book-open', 'description' => 'Educational and informative books'],
            ['name' => 'Science', 'icon' => 'fa-flask', 'description' => 'Scientific books and research materials'],
            ['name' => 'Technology', 'icon' => 'fa-laptop-code', 'description' => 'Computer and technology books'],
            ['name' => 'History', 'icon' => 'fa-landmark', 'description' => 'Historical books and documents'],
            ['name' => 'Biography', 'icon' => 'fa-user', 'description' => 'Biographies and autobiographies'],
            ['name' => 'Children', 'icon' => 'fa-child', 'description' => 'Books for children'],
            ['name' => 'Poetry', 'icon' => 'fa-feather-alt', 'description' => 'Poetry collections'],
            ['name' => 'Drama', 'icon' => 'fa-theater-masks', 'description' => 'Plays and dramatic works'],
            ['name' => 'Philosophy', 'icon' => 'fa-brain', 'description' => 'Philosophical works'],
            ['name' => 'Business', 'icon' => 'fa-chart-line', 'description' => 'Business and management books'],
            ['name' => 'Self Help', 'icon' => 'fa-heart', 'description' => 'Self improvement books'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}