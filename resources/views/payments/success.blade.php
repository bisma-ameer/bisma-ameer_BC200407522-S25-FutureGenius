@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>✅ Payment Successful!</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                        <h3>Thank You for Your Payment!</h3>
                    </div>

                    <div class="payment-details mb-4">
                        <h5>Payment Details</h5>
                        <p><strong>Course:</strong> {{ $payment->course->title }}</p>
                        <p><strong>Amount:</strong> Rs. {{ number_format($payment->amount) }}</p>
                        <p><strong>Transaction ID:</strong> {{ $payment->transaction_id }}</p>
                        <p><strong>Date:</strong> {{ $payment->created_at->format('F d, Y H:i') }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($payment->status) }}</span></p>
                    </div>

                    <div class="alert alert-info">
                        <h6>🎉 Enrollment Successful!</h6>
                        <p class="mb-0">You have been successfully enrolled in the course. You can now start learning!</p>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('courses.show', $payment->course_id) }}" class="btn btn-primary btn-lg">
                            Start Learning
                        </a>
                        <a href="{{ route('courses.my-courses') }}" class="btn btn-outline-primary">
                            View My Courses
                        </a>
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            Browse More Courses
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection