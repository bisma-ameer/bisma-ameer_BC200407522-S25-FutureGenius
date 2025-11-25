@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>👨‍🎓 Student Dashboard</h3>
                        <span class="badge bg-light text-primary">Welcome, {{ auth()->user()->name }}!</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h5>Enrolled Courses</h5>
                                    <h3>{{ $enrolledCoursesCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h5>Completed Quizzes</h5>
                                    <h3>{{ $completedQuizzesCount }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h5>Overall Progress</h5>
                                    <h3>{{ $overallProgress }}%</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-danger">
                                <div class="card-body text-center">
                                    <h5>Certificates</h5>
                                    <h3>{{ $certificatesCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Enrolled Courses -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>📚 My Enrolled Courses</h5>
                                </div>
                                <div class="card-body">
                                    @if($enrolledCourses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Course</th>
                                                    <th>Progress</th>
                                                    <th>Quizzes</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Purane code ki jagah ye use karein --}}
@foreach($enrolledCourses as $enrollment)
@php
    $course = $enrollment->course;
    $progress = $enrollment->progress_percentage;
    // Safe way to get quiz attempts count
    $quizAttempts = $enrollment->quizAttemptsCount; // YEH LINE CHANGE KAREIN
@endphp
<tr>
    <td>
        <strong>{{ $course->title }}</strong>
        <br>
        <small class="text-muted">{{ $course->category->name }}</small>
    </td>
    <td>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar bg-{{ $progress >= 80 ? 'success' : ($progress >= 50 ? 'warning' : 'danger') }}" 
                 style="width: {{ $progress }}%">
                {{ $progress }}%
            </div>
        </div>
    </td>
    <td>
        <span class="badge bg-info">{{ $quizAttempts }} Attempts</span>
    </td>
    <td>
        <span class="badge bg-{{ $enrollment->status === 'completed' ? 'success' : 'primary' }}">
            {{ ucfirst($enrollment->status) }}
        </span>
    </td>
    <td>
        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-primary">Continue</a>
        @if($progress >= 80)
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#certificateModal{{ $course->id }}">
            🏆 Certificate
        </button>
        @endif
    </td>
</tr>
@endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="alert alert-info">
                                        <h5>No courses enrolled yet!</h5>
                                        <p>Start your learning journey by enrolling in courses.</p>
                                        <a href="{{ route('courses.index') }}" class="btn btn-primary">Browse Courses</a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Chart -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>📈 Learning Progress</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="progressChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Recent Quiz Results -->
                            <div class="card">
                                <div class="card-header">
                                    <h6>📝 Recent Quiz Results</h6>
                                </div>
                                <div class="card-body">
                                    @if($recentQuizAttempts->count() > 0)
                                    @foreach($recentQuizAttempts as $attempt)
                                    <div class="mb-3 p-2 border rounded">
                                        <strong>{{ $attempt->quiz->title }}</strong>
                                        <div class="progress mt-1" style="height: 10px;">
                                            <div class="progress-bar bg-{{ $attempt->is_passed ? 'success' : 'danger' }}" 
                                                 style="width: {{ $attempt->percentage }}%"></div>
                                        </div>
                                        <small>
                                            Score: {{ $attempt->percentage }}% 
                                            <span class="badge bg-{{ $attempt->is_passed ? 'success' : 'danger' }} float-end">
                                                {{ $attempt->is_passed ? 'Passed' : 'Failed' }}
                                            </span>
                                        </small>
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="text-muted">No quiz attempts yet.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>⚡ Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('courses.index') }}" class="btn btn-primary btn-sm">Browse Courses</a>
                                        <a href="{{ route('courses.my-courses') }}" class="btn btn-info btn-sm">My Courses</a>
                                        <a href="{{ route('quizzes.index', 1) }}" class="btn btn-warning btn-sm">Take Quizzes</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Parent Info -->
                            @if($parentInfo)
                            <div class="card mt-3">
                                <div class="card-header bg-warning">
                                    <h6>👨‍👩‍👧‍👦 Parent Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Parent:</strong> {{ $parentInfo->name }}</p>
                                    <p><strong>Email:</strong> {{ $parentInfo->email }}</p>
                                    <p><strong>Phone:</strong> {{ $parentInfo->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Progress Chart
const ctx = document.getElementById('progressChart').getContext('2d');
const progressChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($courseTitles) !!},
        datasets: [{
            label: 'Course Progress (%)',
            data: {!! json_encode($courseProgress) !!},
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Certificate Functions
function printCertificate(modalId) {
    const modal = document.getElementById(modalId);
    const certificate = modal.querySelector('.certificate');
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head><title>Certificate</title></head>
            <body>${certificate.innerHTML}</body>
        </html>
    `);
    printWindow.print();
}

function downloadCertificate(courseTitle) {
    alert('PDF download feature would be implemented here for: ' + courseTitle);
    // In real implementation, this would generate and download PDF
}
</script>
@endsection