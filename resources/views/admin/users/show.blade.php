@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>👤 User Details - {{ $user->name }}</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
            </div>

            <div class="row">
                <!-- User Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Role:</th>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'instructor' ? 'success' : ($user->role == 'parent' ? 'warning' : 'primary')) }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $user->address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Joined:</th>
                                    <td>{{ $user->created_at->format('F d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Parent-Child Relationship -->
                <div class="col-md-6">
                    @if($user->isParent())
                    <div class="card">
                        <div class="card-header bg-warning text-white">
                            <h5>👨‍👩‍👧‍👦 Children Management</h5>
                        </div>
                        <div class="card-body">
                            <h6>Children ({{ $children->count() }})</h6>
                            
                            @if($children->count() > 0)
                            <div class="list-group mb-3">
                                @foreach($children as $child)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $child->name }}</strong>
                                        <br>
                                        <small>{{ $child->email }}</small>
                                    </div>
                                    <form action="{{ route('admin.users.remove-child', ['parent' => $user->id, 'student' => $child->id]) }}" 
                                          method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Remove {{ $child->name }} from this parent?')">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted">No children assigned yet.</p>
                            @endif

                            <a href="{{ route('admin.users.manage-children', $user->id) }}" 
                               class="btn btn-success btn-sm">Manage Children</a>
                        </div>
                    </div>
                    @endif

                    @if($user->isStudent() && $parent)
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5>👨‍👩‍👧‍👦 Parent Information</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Parent Name:</strong> {{ $parent->name }}</p>
                            <p><strong>Parent Email:</strong> {{ $parent->email }}</p>
                            <p><strong>Parent Phone:</strong> {{ $parent->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

          <!-- Action Buttons -->
<div class="mt-4">
    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">✏️ Edit User</a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back to Users</a>
    
    @if($user->id != auth()->id())
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" 
                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
            🗑️ Delete User
        </button>
    </form>
    @endif
</div>
        </div>
    </div>
</div>
@endsection