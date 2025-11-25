<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        // Get or create instructor user
        $instructor = User::where('email', 'instructor@kidicode.com')->first();
        
        if (!$instructor) {
            $instructor = User::create([
                'name' => 'Tech Instructor',
                'email' => 'instructor@kidicode.com',
                'password' => bcrypt('password123'),
                'role' => 'instructor',
                'phone' => '0300-9876543'
            ]);
        }

        $courses = [
            [
                'title' => 'Scratch Beginner Course',
                'slug' => 'scratch-beginner-course',
                'description' => 'Perfect for kids starting their coding journey',
                'category_id' => 1,
                'instructor_id' => $instructor->id,
                'difficulty' => 'beginner',
                'price' => 0,
                'duration_hours' => 10,
                'total_lessons' => 15,
                'is_published' => true
            ],
            [
                'title' => 'Python Game Development',
                'slug' => 'python-game-development',
                'description' => 'Create fun games using Python',
                'category_id' => 2,
                'instructor_id' => $instructor->id,
                'difficulty' => 'intermediate',
                'price' => 2999,
                'duration_hours' => 20,
                'total_lessons' => 25,
                'is_published' => true
            ],
            [
                'title' => 'Build Your First Website',
                'slug' => 'build-first-website',
                'description' => 'HTML, CSS and JavaScript for beginners',
                'category_id' => 3,
                'instructor_id' => $instructor->id,
                'difficulty' => 'beginner',
                'price' => 1999,
                'duration_hours' => 15,
                'total_lessons' => 18,
                'is_published' => true
            ]
        ];

        foreach ($courses as $course) {
            // Check if course already exists
            $existingCourse = DB::table('courses')->where('slug', $course['slug'])->first();
            
            if (!$existingCourse) {
                DB::table('courses')->insert(array_merge($course, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            }
        }
    }
}