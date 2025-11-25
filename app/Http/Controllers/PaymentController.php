<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Payment;
use App\Models\CourseEnrollment;

class PaymentController extends Controller
{
    public function showCheckout($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();
        
        // Check if already enrolled
        $isEnrolled = CourseEnrollment::where([
            'course_id' => $courseId,
            'student_id' => $user->id
        ])->exists();
        
        if ($isEnrolled) {
            return redirect()->route('courses.show', $courseId)
                            ->with('info', 'You are already enrolled in this course!');
        }
        
        return view('payments.checkout', compact('course', 'user'));
    }

    public function processPayment(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();
        
        // Demo payment processing
        try {
            // Generate fake transaction ID for demo
            $transactionId = 'DEMO_' . time() . '_' . rand(1000, 9999);
            
            // Save payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'transaction_id' => $transactionId,
                'payment_method' => 'demo_card',
                'status' => 'completed',
                'payment_details' => [
                    'card_last4' => '4242',
                    'brand' => 'visa',
                    'demo' => true
                ]
            ]);

            // Auto-enroll after successful payment
            CourseEnrollment::create([
                'course_id' => $course->id,
                'student_id' => $user->id,
                'status' => 'active'
            ]);

            return redirect()->route('courses.show', $course->id)
                            ->with('success', 'Payment completed successfully! (Demo Mode)');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function freeEnrollment($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = auth()->user();

        // Check if course is free
        if ($course->price > 0) {
            return redirect()->back()->with('error', 'This course is not free.');
        }

        // Check if already enrolled
        $existingEnrollment = CourseEnrollment::where([
            'course_id' => $courseId,
            'student_id' => $user->id
        ])->first();

        if ($existingEnrollment) {
            return redirect()->route('courses.show', $courseId)
                            ->with('info', 'You are already enrolled in this course!');
        }

        // Create enrollment for free course
        CourseEnrollment::create([
            'course_id' => $course->id,
            'student_id' => $user->id,
            'status' => 'active'
        ]);

        return redirect()->route('courses.show', $courseId)
                        ->with('success', 'Successfully enrolled in the free course!');
    }
}