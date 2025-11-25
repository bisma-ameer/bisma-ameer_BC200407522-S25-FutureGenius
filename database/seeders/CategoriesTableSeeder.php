<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Scratch Programming',
                'slug' => 'scratch-programming',
                'description' => 'Learn coding basics with visual programming'
            ],
            [
                'name' => 'Python for Kids',
                'slug' => 'python-for-kids', 
                'description' => 'Introduction to Python programming language'
            ],
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Build websites using HTML, CSS, JavaScript'
            ],
            [
                'name' => 'Artificial Intelligence',
                'slug' => 'artificial-intelligence',
                'description' => 'AI and Machine Learning basics for kids'
            ],
            [
                'name' => 'Robotics',
                'slug' => 'robotics',
                'description' => 'Learn robotics and automation'
            ]
        ];

        foreach ($categories as $category) {
            // Check if category already exists
            $existingCategory = DB::table('categories')->where('slug', $category['slug'])->first();
            
            if (!$existingCategory) {
                DB::table('categories')->insert([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}