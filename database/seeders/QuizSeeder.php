<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Get courses
        $pythonCourse = Course::where('slug', 'python-game-development')->first();
        $webCourse = Course::where('slug', 'build-first-website')->first();

        // Python Course Quiz
        if ($pythonCourse) {
            $pythonQuiz = Quiz::create([
                'course_id' => $pythonCourse->id,
                'title' => 'Python Basics Assessment',
                'description' => 'Test your understanding of Python programming fundamentals',
                'time_limit' => 30,
                'passing_score' => 70,
                'is_published' => true,
            ]);

            // Python Quiz Questions
            $pythonQuestions = [
                [
                    'question_text' => 'Which keyword is used to define a function in Python?',
                    'question_type' => 'multiple_choice',
                    'options' => ['function', 'def', 'define', 'func'],
                    'correct_answer' => 'def',
                    'points' => 1,
                    'order' => 1
                ],
                [
                    'question_text' => 'Python lists are mutable. True or False?',
                    'question_type' => 'true_false',
                    'options' => ['True', 'False'],
                    'correct_answer' => 'True',
                    'points' => 1,
                    'order' => 2
                ],
                [
                    'question_text' => 'What is the output of print(2 ** 3) in Python?',
                    'question_type' => 'multiple_choice',
                    'options' => ['6', '8', '9', '5'],
                    'correct_answer' => '8',
                    'points' => 1,
                    'order' => 3
                ],
                [
                    'question_text' => 'Explain what a variable is in Python?',
                    'question_type' => 'short_answer',
                    'options' => null,
                    'correct_answer' => 'A variable is a named location used to store data in memory.',
                    'points' => 2,
                    'order' => 4
                ],
                [
                    'question_text' => 'Which of the following is used for single-line comments in Python?',
                    'question_type' => 'multiple_choice',
                    'options' => ['//', '#', '/* */', '--'],
                    'correct_answer' => '#',
                    'points' => 1,
                    'order' => 5
                ]
            ];

            foreach ($pythonQuestions as $questionData) {
                Question::create(array_merge($questionData, ['quiz_id' => $pythonQuiz->id]));
            }
        }

        // Web Development Course Quiz
        if ($webCourse) {
            $webQuiz = Quiz::create([
                'course_id' => $webCourse->id,
                'title' => 'HTML & CSS Fundamentals',
                'description' => 'Test your knowledge of web development basics',
                'time_limit' => 20,
                'passing_score' => 60,
                'is_published' => true,
            ]);

            // Web Quiz Questions
            $webQuestions = [
                [
                    'question_text' => 'What does HTML stand for?',
                    'question_type' => 'multiple_choice',
                    'options' => [
                        'Hyper Text Markup Language',
                        'High Tech Modern Language',
                        'Hyper Transfer Markup Language',
                        'Home Tool Markup Language'
                    ],
                    'correct_answer' => 'Hyper Text Markup Language',
                    'points' => 1,
                    'order' => 1
                ],
                [
                    'question_text' => 'CSS is used for styling web pages. True or False?',
                    'question_type' => 'true_false',
                    'options' => ['True', 'False'],
                    'correct_answer' => 'True',
                    'points' => 1,
                    'order' => 2
                ],
                [
                    'question_text' => 'Which tag is used to create a hyperlink in HTML?',
                    'question_type' => 'multiple_choice',
                    'options' => ['<link>', '<a>', '<href>', '<hyperlink>'],
                    'correct_answer' => '<a>',
                    'points' => 1,
                    'order' => 3
                ],
                [
                    'question_text' => 'What is the purpose of CSS?',
                    'question_type' => 'short_answer',
                    'options' => null,
                    'correct_answer' => 'CSS is used to style and layout web pages.',
                    'points' => 2,
                    'order' => 4
                ]
            ];

            foreach ($webQuestions as $questionData) {
                Question::create(array_merge($questionData, ['quiz_id' => $webQuiz->id]));
            }
        }

        $this->command->info('Quizzes and questions seeded successfully!');
    }
}