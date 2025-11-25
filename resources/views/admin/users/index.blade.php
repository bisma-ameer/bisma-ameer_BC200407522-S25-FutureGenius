@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>👥 User Management</h2>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add New User</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- User Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <h2>{{ $totalUsers }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">Instructors</h5>
                            <h2>{{ $instructorCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title">Students</h5>
                            <h2>{{ $studentCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Parents</h5>
                            <h2>{{ $parentCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>

          <!-- Filter Tabs -->
<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $role == 'all' ? 'active' : '' }}" 
           href="{{ route('admin.users.index') }}">All Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $role == 'student' ? 'active' : '' }}" 
           href="{{ route('admin.users.index', ['role' => 'student']) }}">👨‍🎓 Students</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $role == 'instructor' ? 'active' : '' }}" 
           href="{{ route('admin.users.index', ['role' => 'instructor']) }}">👨‍🏫 Instructors</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $role == 'parent' ? 'active' : '' }}" 
           href="{{ route('admin.users.index', ['role' => 'parent']) }}">👨‍👩‍👧‍👦 Parents</a>
    </li>
</ul>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Phone</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->isParent() && $user->hasChildren())
                                            <span class="badge bg-info">Has Children</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'instructor' ? 'success' : ($user->role == 'parent' ? 'warning' : 'primary')) }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-warning">Edit</a>
                                            @if($user->id != auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure? This action cannot be undone.')">
                                                    Delete
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection