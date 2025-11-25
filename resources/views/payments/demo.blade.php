@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>💳 Demo Payment</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="alert alert-info">
                            <h5>🚀 Demo Payment System</h5>
                            <p class="mb-0">This is a simulated payment process for testing.</p>
                        </div>
                    </div>

                    <div class="course-info mb-4">
                        <h5>Course: {{ $course->title }}</h5>
                        <p><strong>Price: Rs. {{ number_format($course->price) }}</strong></p>
                        <p><strong>Student: {{ $user->name }}</strong></p>
                    </div>

                    <div class="demo-card mb-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6>💳 Demo Credit Card</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Card Number:</strong> 4242 4242 4242 4242</p>
                                <p><strong>Expiry Date:</strong> 12/34</p>
                                <p><strong>CVC:</strong> 123</p>
                                <p class="text-muted small">This is a test card provided by Stripe for testing purposes.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.process', $course->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select Payment Method</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="visa" value="visa" checked>
                                    <label class="form-check-label" for="visa">
                                        💳 Visa Card (Demo)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="mastercard" value="mastercard">
                                    <label class="form-check-label" for="mastercard">
                                        💳 MasterCard (Demo)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                💳 Pay Rs. {{ number_format($course->price) }} (Demo)
                            </button>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection