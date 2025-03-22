<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Tech' => 'Articles about technology',
            'Lifestyle' => 'Articles about lifestyle and daily life',
            'Education' => 'Articles related to education',
            'Business' => 'Articles about business and entrepreneurship',
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => \Str::slug($name),
                'description' => $description,  // Ajouter une description
            ]);
        }
    }
}

