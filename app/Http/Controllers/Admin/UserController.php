<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'all');
        
        $query = User::query();
        
        if ($role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->latest()->paginate(10);
        $totalUsers = User::count();
        $instructorCount = User::where('role', 'instructor')->count();
        $studentCount = User::where('role', 'student')->count();
        $parentCount = User::where('role', 'parent')->count();
        
        return view('admin.users.index', compact(
            'users', 'totalUsers', 'instructorCount', 
            'studentCount', 'parentCount', 'role'
        ));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,instructor,student,parent',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        // For parents, load their children
        $children = [];
        if ($user->isParent()) {
            $children = User::where('parent_id', $user->id)->get();
        }
        
        // For students, load their parent
        $parent = null;
        if ($user->isStudent()) {
            $parent = User::find($user->parent_id);
        }

        return view('admin.users.show', compact('user', 'children', 'parent'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,instructor,student,parent',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string'
        ]);

        $user->update($request->only(['name', 'email', 'role', 'phone', 'address']));

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // If deleting a parent, also delete their children
        if ($user->isParent()) {
            User::where('parent_id', $user->id)->delete();
        }
        
        // If deleting a student, remove from parent relationship
        if ($user->isStudent()) {
            $user->update(['parent_id' => null]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully!');
    }

    // Special method for parent-child management
    public function manageChildren($parentId)
    {
        $parent = User::findOrFail($parentId);
        $children = User::where('parent_id', $parentId)->get();
        $availableStudents = User::where('role', 'student')
                                ->whereNull('parent_id')
                                ->get();

        return view('admin.users.manage-children', compact('parent', 'children', 'availableStudents'));
    }

    public function addChild(Request $request, $parentId)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id'
        ]);

        $student = User::findOrFail($request->student_id);
        $student->update(['parent_id' => $parentId]);

        return redirect()->back()
                        ->with('success', 'Student added to parent successfully!');
    }

    public function removeChild($parentId, $studentId)
    {
        $student = User::findOrFail($studentId);
        $student->update(['parent_id' => null]);

        return redirect()->back()
                        ->with('success', 'Student removed from parent successfully!');
    }
}