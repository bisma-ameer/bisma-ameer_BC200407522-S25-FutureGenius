@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>💳 Demo Checkout</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>🚀 Demo Payment System</h5>
                        <p>This is a simulated payment process for testing.</p>
                    </div>

                    <div class="course-info mb-4">
                        <h5>Course: {{ $course->title }}</h5>
                        <p class="h4 text-primary">Price: Rs. {{ number_format($course->price) }}</p>
                        <p><strong>Student:</strong> {{ $user->name }}</p>
                    </div>

                    <form action="{{ route('payment.process', $course->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Demo Payment Method</label>
                            <select class="form-control" name="payment_method">
                                <option value="visa">Visa Card (Demo)</option>
                                <option value="mastercard">MasterCard (Demo)</option>
                                <option value="jazzcash">JazzCash (Demo)</option>
                            </select>
                        </div>

                        <div class="demo-card mb-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6>💳 Test Credit Card</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Card Number:</strong> 4242 4242 4242 4242</p>
                                    <p><strong>Expiry Date:</strong> 12/34</p>
                                    <p><strong>CVC:</strong> 123</p>
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