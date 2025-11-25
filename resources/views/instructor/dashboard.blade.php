@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>👨‍🏫 Instructor Dashboard</h3>
                        <div>
                            <span class="badge bg-light text-success">Welcome, {{ auth()->user()->name }}!</span>
                            <a href="{{ route('courses.create') }}" class="btn btn-light btn-sm ms-2">➕ Create Course</a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body text-center">
                                    <h5>My Courses</h5>
                                    <h3>{{ $myCoursesCount }}</h3>
                                    <small>Total Created</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success">
                                <div class="card-body text-center">
                                    <h5>Total Students</h5>
                                    <h3>{{ $totalStudents }}</h3>
                                    <small>Enrolled Students</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info">
                                <div class="card-body text-center">
                                    <h5>Assignments</h5>
                                    <h3>{{ $assignmentsCount }}</h3>
                                    <small>Active Assignments</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h5>Materials</h5>
                                    <h3>{{ $materialsCount }}</h3>
                                    <small>Videos & PDFs</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5>⚡ Quick Actions</h5>
                            <div class="d-grid gap-2 d-md-flex flex-wrap">
                                <a href="{{ route('courses.create') }}" class="btn btn-primary me-md-2 mb-2">
                                    ➕ Create New Course
                                </a>
                                <a href="{{ route('instructor.courses') }}" class="btn btn-success me-md-2 mb-2">
                                    📚 My Courses
                                </a>
                                <a href="{{ route('instructor.assignments') }}" class="btn btn-info me-md-2 mb-2">
                                    📝 Manage Assignments
                                </a>
                                <a href="{{ route('instructor.materials') }}" class="btn btn-warning me-md-2 mb-2">
                                    🎥 Upload Materials
                                </a>
                                <a href="{{ route('instructor.students') }}" class="btn btn-secondary me-md-2 mb-2">
                                    👨‍🎓 View Students
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- My Courses -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>📚 My Recent Courses</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentCourses->count() > 0)
                                    @foreach($recentCourses as $course)
                                    <div class="course-item mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $course->title }}</h6>
                                                <p class="mb-1 text-muted small">{{ Str::limit($course->description, 80) }}</p>
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-{{ $course->is_published ? 'success' : 'secondary' }}">
                                                        {{ $course->is_published ? 'Published' : 'Draft' }}
                                                    </span>
                                                    <span class="badge bg-primary">{{ $course->enrollments_count }} Students</span>
                                                    <span class="badge bg-info">{{ $course->difficulty }}</span>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('courses.show', $course->id) }}">View</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('courses.edit', $course->id) }}">Edit</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('instructor.course.students', $course->id) }}">Students</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('instructor.course.assignments', $course->id) }}">Assignments</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="alert alert-info">
                                        <p class="mb-0">No courses created yet. <a href="{{ route('courses.create') }}">Create your first course!</a></p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Recent Students -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>👨‍🎓 Recent Enrollments</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentEnrollments->count() > 0)
                                    @foreach($recentEnrollments as $enrollment)
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                        <div>
                                            <strong>{{ $enrollment->student->name }}</strong>
                                            <br>
                                            <small class="text-muted">Enrolled in: {{ $enrollment->course->title }}</small>
                                        </div>
                                        <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="text-muted">No recent enrollments.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity & Assignments -->
                        <div class="col-md-6">
                            <!-- Upcoming Assignments -->
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h5>📅 Upcoming Assignments</h5>
                                </div>
                                <div class="card-body">
                                    @if($upcomingAssignments->count() > 0)
                                    @foreach($upcomingAssignments as $assignment)
                                    <div class="assignment-item mb-3 p-3 border rounded">
                                        <h6 class="mb-1">{{ $assignment->title }}</h6>
                                        <p class="mb-1 small text-muted">Course: {{ $assignment->course->title }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $assignment->due_date->isPast() ? 'danger' : 'primary' }}">
                                                Due: {{ $assignment->due_date->format('M d, Y') }}
                                            </span>
                                            <span class="badge bg-info">{{ $assignment->submissions_count }} Submissions</span>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="alert alert-info">
                                        <p class="mb-0">No upcoming assignments. <a href="{{ route('instructor.assignments') }}">Create an assignment!</a></p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Course Performance -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>📊 Course Performance</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="coursePerformanceChart" width="400" height="200"></canvas>
                                </div>
                            </div>

                            <!-- Quick Materials Upload -->
                            <div class="card mt-4">
                                <div class="card-header bg-info text-white">
                                    <h6>🎥 Quick Material Upload</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('instructor.materials.quick') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-2">
                                            <select class="form-control form-control-sm" name="course_id" required>
                                                <option value="">Select Course</option>
                                                @foreach($myCourses as $course)
                                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" class="form-control form-control-sm" name="title" placeholder="Material Title" required>
                                        </div>
                                        <div class="mb-2">
                                            <select class="form-control form-control-sm" name="type" required>
                                                <option value="video">Video</option>
                                                <option value="pdf">PDF</option>
                                                <option value="document">Document</option>
                                            </select>
                                        </div>
                                        <div class="mb-2">
                                            <input type="file" class="form-control form-control-sm" name="file" required>
                                        </div>
                                        <button type="submit" class="btn btn-info btn-sm w-100">Upload Material</button>
                                    </form>
                                </div>
                            </div>
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
// Course Performance Chart
const ctx = document.getElementById('coursePerformanceChart').getContext('2d');
const performanceChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($courseTitles) !!},
        datasets: [{
            label: 'Enrolled Students',
            data: {!! json_encode($courseEnrollments) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection