<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuizController;

Route::get('/', function () {
        // Agar user already logged in hai tou directly dashboard par bhejein

       if (auth()->check()) {
        return redirect()->route('home');
    }
    return view('welcome');
});

Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Home Dashboard (After login)
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
// Courses Routes
Route::resource('courses', CourseController::class);

// Enrollment routes
Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('courses.my-courses');
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

// Simple Payment routes
Route::middleware(['auth'])->group(function () {
    Route::get('/course/{course}/checkout', [PaymentController::class, 'showCheckout'])->name('payment.checkout');
    Route::post('/course/{course}/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::post('/course/{course}/free-enroll', [PaymentController::class, 'freeEnrollment'])->name('payment.free.enroll');
});

// Quizzes routes
Route::middleware(['auth'])->group(function () {
    Route::get('/courses/{course}/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/courses/{course}/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/courses/{course}/quizzes/{quiz}/start', [QuizController::class, 'startQuiz'])->name('quizzes.start');
    Route::get('/courses/{course}/quizzes/{quiz}/attempt/{attempt}', [QuizController::class, 'attemptQuiz'])->name('quizzes.attempt');
    Route::post('/courses/{course}/quizzes/{quiz}/attempt/{attempt}/submit', [QuizController::class, 'submitQuiz'])->name('quizzes.submit');
    Route::get('/courses/{course}/quizzes/{quiz}/result/{attempt}', [QuizController::class, 'showResult'])->name('quizzes.result');
});

// COMPLETE ADMIN ROUTES
Route::middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', function () {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        return redirect()->route('admin.users.index');
    });

    // Admin users index
    Route::get('/admin/users', function (Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $role = $request->input('role', 'all');
        $query = App\Models\User::query();
        
        if ($role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->latest()->paginate(10);
        $totalUsers = App\Models\User::count();
        $instructorCount = App\Models\User::where('role', 'instructor')->count();
        $studentCount = App\Models\User::where('role', 'student')->count();
        $parentCount = App\Models\User::where('role', 'parent')->count();
        
        return view('admin.users.index', compact(
            'users', 'totalUsers', 'instructorCount', 
            'studentCount', 'parentCount', 'role'
        ));
    })->name('admin.users.index');

    // Create user form
    Route::get('/admin/users/create', function () {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        return view('admin.users.create');
    })->name('admin.users.create');

    // Store new user
    Route::post('/admin/users', function (Request $request) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,instructor,student,parent',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string'
        ]);

        App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully!');
    })->name('admin.users.store');

    // Admin user show
    Route::get('/admin/users/{id}', function ($id) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $user = App\Models\User::findOrFail($id);
        $children = [];
        $parent = null;
        
        if ($user->role === 'parent') {
            $children = App\Models\User::where('parent_id', $user->id)->get();
        }
        
        if ($user->role === 'student') {
            $parent = App\Models\User::find($user->parent_id);
        }

        return view('admin.users.show', compact('user', 'children', 'parent'));
    })->name('admin.users.show');

    // Edit user form
    Route::get('/admin/users/{id}/edit', function ($id) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $user = App\Models\User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    })->name('admin.users.edit');

    // Update user
    Route::put('/admin/users/{id}', function (Request $request, $id) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $user = App\Models\User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,instructor,student,parent',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully!');
    })->name('admin.users.update');

    // Delete user
    Route::delete('/admin/users/{id}', function ($id) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $user = App\Models\User::findOrFail($id);

        if ($user->role === 'parent') {
            App\Models\User::where('parent_id', $user->id)->delete();
        }
        
        if ($user->role === 'student') {
            $user->update(['parent_id' => null]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully!');
    })->name('admin.users.destroy');

    // Parent-child management
    Route::get('/admin/users/{parent}/manage-children', function ($parentId) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $parent = App\Models\User::findOrFail($parentId);
        $children = App\Models\User::where('parent_id', $parentId)->get();
        $availableStudents = App\Models\User::where('role', 'student')
                                ->whereNull('parent_id')
                                ->get();

        return view('admin.users.manage-children', compact('parent', 'children', 'availableStudents'));
    })->name('admin.users.manage-children');

    // Add child to parent
    Route::post('/admin/users/{parent}/add-child', function (Request $request, $parentId) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $request->validate(['student_id' => 'required|exists:users,id']);
        $student = App\Models\User::findOrFail($request->student_id);
        $student->update(['parent_id' => $parentId]);

        return redirect()->back()->with('success', 'Student added to parent successfully!');
    })->name('admin.users.add-child');

    // Remove child from parent
    Route::post('/admin/users/{parent}/remove-child/{student}', function ($parentId, $studentId) {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Admin access required.');
        }
        
        $student = App\Models\User::findOrFail($studentId);
        $student->update(['parent_id' => null]);

        return redirect()->back()->with('success', 'Student removed from parent successfully!');
    })->name('admin.users.remove-child');
    // Parent-child routes
Route::middleware(['auth'])->group(function () {
    // Parent view child progress
    Route::get('/parent/child/{child}/progress', [HomeController::class, 'childProgress'])->name('parent.child.progress');
    
    // Certificate generation
    Route::get('/certificate/course/{course}', [HomeController::class, 'generateCertificate'])->name('certificate.generate');
});

// Instructor Routes
Route::middleware(['auth', 'instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/courses', [InstructorController::class, 'courses'])->name('courses');
    Route::get('/students', [InstructorController::class, 'students'])->name('students');
    Route::get('/assignments', [InstructorController::class, 'assignments'])->name('assignments');
    Route::get('/materials', [InstructorController::class, 'materials'])->name('materials');
    Route::post('/materials/quick', [InstructorController::class, 'quickUpload'])->name('materials.quick');
    Route::get('/course/{course}/students', [InstructorController::class, 'courseStudents'])->name('course.students');
        Route::get('courses', [\App\Http\Controllers\InstructorController::class, 'courses'])->name('courses');
    Route::get('/course/{course}/assignments', [InstructorController::class, 'courseAssignments'])->name('course.assignments');
});

});