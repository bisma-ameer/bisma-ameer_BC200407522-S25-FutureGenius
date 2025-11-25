@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>📊 Child Progress - {{ $child->name }}</h3>
                        <a href="{{ route('home') }}" class="btn btn-light">Back to Dashboard</a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Progress Overview -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h5>Enrolled Courses</h5>
                                    <h3>{{ $enrollments->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h5>Overall Progress</h5>
                                    <h3>{{ round($overallProgress) }}%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h5>Quiz Attempts</h5>
                                    <h3>{{ $quizAttempts->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body text-center">
                                    <h5>Avg Quiz Score</h5>
                                    <h3>{{ $quizAttempts->avg('percentage') ? round($quizAttempts->avg('percentage')) : 0 }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Progress -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>📚 Course Progress</h5>
                        </div>
                        <div class="card-body">
                            @if($enrollments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th>Category</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Enrolled Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->course->title }}</td>
                                            <td>{{ $enrollment->course->category->name }}</td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" style="width: {{ $enrollment->progress_percentage }}%">
                                                        {{ $enrollment->progress_percentage }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $enrollment->status === 'completed' ? 'success' : 'primary' }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <p>No courses enrolled yet.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quiz Results -->
                    <div class="card">
                        <div class="card-header">
                            <h5>📝 Quiz Results</h5>
                        </div>
                        <div class="card-body">
                            @if($quizAttempts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Quiz</th>
                                            <th>Course</th>
                                            <th>Score</th>
                                            <th>Result</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quizAttempts as $attempt)
                                        <tr>
                                            <td>{{ $attempt->quiz->title }}</td>
                                            <td>{{ $attempt->quiz->course->title ?? 'N/A' }}</td>
                                            <td>{{ $attempt->percentage }}%</td>
                                            <td>
                                                <span class="badge bg-{{ $attempt->is_passed ? 'success' : 'danger' }}">
                                                    {{ $attempt->is_passed ? 'Passed' : 'Failed' }}
                                                </span>
                                            </td>
                                            <td>{{ $attempt->created_at->format('M d, Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <p>No quiz attempts yet.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection