@extends('layouts.app')

@section('content')
<div class="container-fluid admin-dashboard">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3><i class="fas fa-crown me-2"></i>Admin Dashboard</h3>
                        <span class="badge bg-light text-primary">System Administrator</span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="alert alert-success">
                        <h5><i class="fas fa-user-check me-2"></i>Welcome, {{ auth()->user()->name }}!</h5>
                        <p class="mb-0">You are logged in as <strong>ADMINISTRATOR</strong> - Full system access</p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="stats-grid">
                        <div class="stat-card bg-primary text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-users me-2"></i>Total Users</h5>
                                <h2>{{ $totalUsers }}</h2>
                                <small>All System Users</small>
                            </div>
                        </div>
                        
                        <div class="stat-card bg-success text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-book me-2"></i>Total Courses</h5>
                                <h2>{{ $totalCourses }}</h2>
                                <small>Available Courses</small>
                            </div>
                        </div>
                        
                        <div class="stat-card bg-info text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-chalkboard-teacher me-2"></i>Instructors</h5>
                                <h2>{{ $instructorCount }}</h2>
                                <small>Active Instructors</small>
                            </div>
                        </div>
                        
                        <div class="stat-card bg-warning text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-user-graduate me-2"></i>Students</h5>
                                <h2>{{ $studentCount }}</h2>
                                <small>Registered Students</small>
                            </div>
                        </div>

                        <div class="stat-card bg-secondary text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-user-friends me-2"></i>Parents</h5>
                                <h2>{{ $parentCount }}</h2>
                                <small>Registered Parents</small>
                            </div>
                        </div>

                        <div class="stat-card bg-danger text-white">
                            <div class="card-body">
                                <h5><i class="fas fa-chart-bar me-2"></i>Enrollments</h5>
                                <h2>{{ $totalEnrollments }}</h2>
                                <small>Course Enrollments</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4 quick-actions-section">
                        <div class="col-md-12">
                            <h4><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary quick-action-btn">
                                    <i class="fas fa-users me-2"></i>Manage All Users
                                </a>
                                <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="btn btn-success quick-action-btn">
                                    <i class="fas fa-user-graduate me-2"></i>Manage Students
                                </a>
                                <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}" class="btn btn-info quick-action-btn">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Manage Instructors
                                </a>
                                <a href="{{ route('admin.users.index', ['role' => 'parent']) }}" class="btn btn-warning quick-action-btn">
                                    <i class="fas fa-user-friends me-2"></i>Manage Parents
                                </a>
                                <a href="{{ route('courses.create') }}" class="btn btn-secondary quick-action-btn">
                                    <i class="fas fa-plus me-2"></i>Create New Course
                                </a>
                                <a href="{{ route('courses.index') }}" class="btn btn-dark quick-action-btn">
                                    <i class="fas fa-book me-2"></i>Manage Courses
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- System Overview -->
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="card system-overview border-0 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <h5><i class="fas fa-chart-line me-2"></i>System Overview</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Total Users</span>
                                            <span class="badge bg-primary rounded-pill">{{ $totalUsers }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Active Students</span>
                                            <span class="badge bg-success rounded-pill">{{ $studentCount }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Active Instructors</span>
                                            <span class="badge bg-info rounded-pill">{{ $instructorCount }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Registered Parents</span>
                                            <span class="badge bg-warning rounded-pill">{{ $parentCount }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Published Courses</span>
                                            <span class="badge bg-secondary rounded-pill">{{ $publishedCourses }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Total Enrollments</span>
                                            <span class="badge bg-danger rounded-pill">{{ $totalEnrollments }}</span>
                                        </div>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>Quiz Attempts</span>
                                            <span class="badge bg-dark rounded-pill">{{ $quizAttemptsCount }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Users -->
                            <div class="card mt-4 border-0 shadow-sm">
                                <div class="card-header bg-white">
                                    <h5><i class="fas fa-user-plus me-2"></i>Recent Registrations</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($recentUsers as $user)
                                    <div class="user-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'instructor' ? 'success' : ($user->role == 'parent' ? 'warning' : 'primary')) }} ms-1">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- User Distribution Chart -->
                            <div class="card chart-container">
                                <div class="card-header">
                                    <h5><i class="fas fa-chart-pie me-2"></i>User Distribution</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="userDistributionChart" width="400" height="250"></canvas>
                                </div>
                            </div>

                            <!-- Quick User Management -->
                            <div class="card mt-4 quick-user-management border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5><i class="fas fa-rocket me-2"></i>Quick User Management</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="card management-card">
                                                <div class="card-body">
                                                    <h6><i class="fas fa-user-graduate me-1"></i>Students</h6>
                                                    <h4 class="text-success">{{ $studentCount }}</h4>
                                                    <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="btn btn-success btn-sm w-100">
                                                        Manage Students
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card management-card">
                                                <div class="card-body">
                                                    <h6><i class="fas fa-chalkboard-teacher me-1"></i>Instructors</h6>
                                                    <h4 class="text-info">{{ $instructorCount }}</h4>
                                                    <a href="{{ route('admin.users.index', ['role' => 'instructor']) }}" class="btn btn-info btn-sm w-100">
                                                        Manage Instructors
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card management-card">
                                                <div class="card-body">
                                                    <h6><i class="fas fa-user-friends me-1"></i>Parents</h6>
                                                    <h4 class="text-warning">{{ $parentCount }}</h4>
                                                    <a href="{{ route('admin.users.index', ['role' => 'parent']) }}" class="btn btn-warning btn-sm w-100">
                                                        Manage Parents
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="card management-card">
                                                <div class="card-body">
                                                    <h6><i class="fas fa-user-plus me-1"></i>New User</h6>
                                                    <h4 class="text-primary">+</h4>
                                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm w-100">
                                                        Add User
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
// User Distribution Chart
const ctx = document.getElementById('userDistributionChart').getContext('2d');
const userChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Students', 'Instructors', 'Parents', 'Admins'],
        datasets: [{
            data: [
                {{ $studentCount }},
                {{ $instructorCount }},
                {{ $parentCount }},
                {{ $adminCount }}
            ],
            backgroundColor: [
                '#4cc9f0', // Success for Students
                '#4895ef', // Info for Instructors
                '#f72585', // Warning for Parents
                '#e63946'  // Danger for Admins
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        let value = context.raw || 0;
                        let total = {{ $totalUsers }};
                        let percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
});
</script>
@endsection