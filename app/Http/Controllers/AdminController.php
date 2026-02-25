<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DevelopmentObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try admin authentication first
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // Try regular user authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect faculty members to development objectives
            if ($user->role === 'faculty') {
                return redirect()->intended(route('development-objectives.index'));
            }
            
            // Redirect chairpersons to their dashboard
            if ($user->role === 'chairperson') {
                return redirect()->intended(route('chairperson.dashboard'));
            }
            
            // Default redirect for other user types
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard(Request $request)
    {
        $totalDevelopmentObjectives = DevelopmentObjective::count();
        
        // Get recent activities (last 5)
        $recentActivities = DevelopmentObjective::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($objective) {
                return [
                    'user' => $objective->user ? $objective->user->first_name . ' ' . $objective->user->last_name : 'Admin',
                    'action' => 'created new development objective',
                    'time' => $objective->created_at
                ];
            });
        
        // Get department distribution data
        $departments = ['DAFE', 'DCEA', 'DCEEE', 'DIET', 'DIT'];
        $departmentData = [];
        
        foreach ($departments as $dept) {
            $usersCount = User::where('department', $dept)->count();
            $objectivesCount = DevelopmentObjective::whereHas('user', function($query) use ($dept) {
                $query->where('department', $dept);
            })->count();
            $completedCount = DevelopmentObjective::whereHas('user', function($query) use ($dept) {
                $query->where('department', $dept);
            })->where('status', 'completed')->count();
            
            $departmentData[] = [
                'name' => $dept,
                'users' => $usersCount,
                'objectives' => $objectivesCount,
                'completed' => $completedCount
            ];
        }
        
        $allObjectives = DevelopmentObjective::with('user')->get();
        $facultyPlanAvailableYears = $allObjectives
            ->map(function ($objective) {
                return $objective->created_at ? (int) $objective->created_at->format('Y') : null;
            })
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $facultyPlanSelectedYear = $request->query('fpYear');
        if ($facultyPlanSelectedYear !== null) {
            $facultyPlanSelectedYear = (int) $facultyPlanSelectedYear;
        }

        if ($facultyPlanSelectedYear && !$facultyPlanAvailableYears->contains($facultyPlanSelectedYear)) {
            $facultyPlanSelectedYear = $facultyPlanAvailableYears->first();
        }

        if (!$facultyPlanSelectedYear) {
            $facultyPlanSelectedYear = $facultyPlanAvailableYears->first() ?? (int) now()->format('Y');
        }

        $facultyPlanObjectives = $allObjectives->filter(function ($objective) use ($facultyPlanSelectedYear) {
            return $objective->created_at && (int) $objective->created_at->format('Y') === $facultyPlanSelectedYear;
        })->values();

        return view('admin.dashboard', compact(
            'totalDevelopmentObjectives',
            'recentActivities',
            'departmentData',
            'facultyPlanObjectives',
            'facultyPlanAvailableYears',
            'facultyPlanSelectedYear'
        ));
    }

    public function userManagement()
    {
        $users = User::paginate(10);
        return view('admin.user-management', compact('users'));
    }

    public function createUser()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'department' => 'required|string|in:DAFE,DCEA,DCEEE,DIET,DIT',
            'role' => 'required|string|in:faculty,chairperson',
            'regularized_at' => 'nullable|date',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if chairperson already exists for this department
        if ($request->role === 'chairperson') {
            $existingChairperson = User::where('department', $request->department)
                ->where('role', 'chairperson')
                ->first();
            
            if ($existingChairperson) {
                return back()
                    ->withErrors(['role' => 'A chairperson already exists for this department.'])
                    ->withInput();
            }
        }

        User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'department' => $request->department,
            'role' => $request->role,
            'regularized_at' => $request->input('regularized_at') ?: null,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, User $user)
    {
        // Debug: Log the incoming request data
        \Log::info('Update user request data:', $request->all());
        \Log::info('User data before update:', $user->toArray());
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'department' => 'required|string|in:DAFE,DCEA,DCEEE,DIET,DIT',
            'role' => 'required|string|in:faculty,chairperson',
            'regularized_at' => 'nullable|date',
        ]);

        // Check if chairperson already exists for this department (excluding current user)
        if ($request->role === 'chairperson') {
            $existingChairperson = User::where('department', $request->department)
                ->where('role', 'chairperson')
                ->where('id', '!=', $user->id)
                ->first();
            
            if ($existingChairperson) {
                return back()
                    ->withErrors(['role' => 'A chairperson already exists for this department.'])
                    ->withInput();
            }
        }

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'department' => $request->department,
            'role' => $request->role,
            'regularized_at' => $request->input('regularized_at') ?: null,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}
