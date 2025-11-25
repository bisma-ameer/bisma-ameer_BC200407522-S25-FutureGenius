@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>👨‍👩‍👧‍👦 Parent Dashboard</h3>
                        <span class="badge bg-light text-dark">Monitoring: {{ $children->count() }} Children</span>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Children Overview -->
                    <div class="row mb-4">
                        @foreach($children as $child)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5>👶 {{ $child->name }}</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $childEnrollments = \App\Models\CourseEnrollment::with('course')
                                            ->where('student_id', $child->id)
                                            ->get();
                                        $childProgress = $childEnrollments->avg('progress_percentage') ?? 0;
                                        $completedCourses = $childEnrollments->where('status', 'completed')->count();
                                    @endphp
                                    
                                    <p><strong>Email:</strong> {{ $child->email }}</p>
                                    <p><strong>Enrolled Courses:</strong> {{ $childEnrollments->count() }}</p>
                                    <p><strong>Completed:</strong> {{ $completedCourses }} courses</p>
                                    
                                    <div class="progress mb-2">
                                        <div class="progress-bar bg-success" style="width: {{ $childProgress }}%">
                                            {{ round($childProgress) }}%
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('parent.child.progress', $child->id) }}" class="btn btn-sm btn-primary w-100">
                                        View Detailed Progress
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($children->count() == 0)
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5>No children assigned yet!</h5>
                                <p>Contact admin to assign your children to your account.</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Recent Activity -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>📊 Children's Recent Activity</h5>
                                </div>
                                <div class="card-body">
                                    @if($recentActivities->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Child</th>
                                                    <th>Activity</th>
                                                    <th>Course</th>
                                                    <th>Score</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentActivities as $activity)
                                                <tr>
                                                    <td>{{ $activity->child_name }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $activity->type === 'quiz' ? 'info' : 'success' }}">
                                                            {{ ucfirst($activity->type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $activity->course_title }}</td>
                                                    <td>
                                                        @if($activity->score)
                                                        <span class="badge bg-{{ $activity->score >= 80 ? 'success' : ($activity->score >= 50 ? 'warning' : 'danger') }}">
                                                            {{ $activity->score }}%
                                                        </span>
                                                        @else
                                                        <span class="badge bg-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $activity->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="alert alert-info">
                                        <p>No recent activity from your children.</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-md-4">
                            <!-- Progress Summary -->
                            <div class="card">
                                <div class="card-header">
                                    <h6>📈 Overall Progress Summary</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="childrenProgressChart" width="300" height="200"></canvas>
                                </div>
                            </div>

                            <!-- Quick Reports -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>📋 Quick Reports</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-sm btn-outline-primary">Weekly Progress</button>
                                        <button class="btn btn-sm btn-outline-success">Course Completion</button>
                                        <button class="btn btn-sm btn-outline-info">Quiz Performance</button>
                                        <button class="btn btn-sm btn-outline-warning">Generate Report</button>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Children Progress Chart
const ctx2 = document.getElementById('childrenProgressChart').getContext('2d');
const childrenChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($childrenNames) !!},
        datasets: [{
            data: {!! json_encode($childrenProgress) !!},
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    }
});
</script>
@endsection