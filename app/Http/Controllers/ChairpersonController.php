<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DevelopmentObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChairpersonController extends Controller
{
    /**
     * Display the chairperson dashboard.
     */
    public function dashboard()
    {
        $chairperson = Auth::user();
        
        // Get faculty members from the same department
        $facultyMembers = User::where('department', $chairperson->department)
            ->where('role', 'faculty')
            ->orderBy('first_name')
            ->get();
        
        // Get statistics
        $totalFaculty = $facultyMembers->count();
        $activeObjectives = DevelopmentObjective::whereHas('user', function($query) use ($chairperson) {
            $query->where('department', $chairperson->department);
        })->whereIn('status', ['pending', 'in_progress'])->count();
        
        $completedObjectives = DevelopmentObjective::whereHas('user', function($query) use ($chairperson) {
            $query->where('department', $chairperson->department);
        })->where('status', 'completed')->count();
        
        // Calculate faculty completion statistics
        $facultyWithCompletedObjectives = 0;
        $facultyWithAnyObjectives = 0;
        
        foreach ($facultyMembers as $faculty) {
            // Get faculty objectives
            $facultyObjectives = DevelopmentObjective::where('user_id', $faculty->id)->get();
            
            if ($facultyObjectives->count() > 0) {
                $facultyWithAnyObjectives++;
                
                // Check if faculty has completed all their objectives
                $allCompleted = true;
                foreach ($facultyObjectives as $objective) {
                    $status = strtolower(trim($objective->status));
                    if ($status !== 'completed') {
                        $allCompleted = false;
                        break;
                    }
                }
                
                if ($allCompleted) {
                    $facultyWithCompletedObjectives++;
                }
            }
        }
        
        // Calculate faculty completion percentage
        $facultyCompletionRate = $facultyWithAnyObjectives > 0 
            ? round(($facultyWithCompletedObjectives / $facultyWithAnyObjectives) * 100, 1) 
            : 0;
        
        return view('chairperson.dashboard', compact(
            'facultyMembers', 
            'totalFaculty', 
            'activeObjectives', 
            'completedObjectives',
            'facultyWithCompletedObjectives',
            'facultyWithAnyObjectives',
            'facultyCompletionRate'
        ));
    }
    
    /**
     * Display faculty members list.
     */
    public function facultyMembers()
    {
        $chairperson = Auth::user();
        
        // Get faculty members from the same department
        $facultyMembers = User::where('department', $chairperson->department)
            ->where('role', 'faculty')
            ->orderBy('first_name')
            ->paginate(10);
        
        return view('chairperson.faculty-members', compact('facultyMembers'));
    }
    
    /**
     * Display faculty member details with their objectives.
     */
    public function facultyMemberDetails(User $user)
    {
        $chairperson = Auth::user();
        
        // Ensure the faculty member is from the same department
        if ($user->department !== $chairperson->department) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get faculty member's objectives
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate statistics
        $totalObjectives = $objectives->count();
        $completedObjectives = $objectives->where('status', 'completed')->count();
        $inProgressObjectives = $objectives->where('status', 'in_progress')->count();
        $pendingObjectives = $objectives->where('status', 'pending')->count();
        
        // Calculate completion rate
        $completionRate = $totalObjectives > 0 ? round(($completedObjectives / $totalObjectives) * 100, 1) : 0;
        
        return view('chairperson.faculty-member-details', compact(
            'user', 
            'objectives', 
            'totalObjectives', 
            'completedObjectives', 
            'inProgressObjectives', 
            'pendingObjectives', 
            'completionRate'
        ));
    }
    
    /**
     * Display department reports with faculty files.
     */
    public function departmentReports()
    {
        $chairperson = Auth::user();
        
        // Get faculty members from the same department
        $facultyMembers = User::where('department', $chairperson->department)
            ->where('role', 'faculty')
            ->orderBy('first_name')
            ->get();
        
        // Load objectives and files for each faculty member
        foreach ($facultyMembers as $faculty) {
            $faculty->developmentObjectives = DevelopmentObjective::where('user_id', $faculty->id)
                ->with('files')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $totalFaculty = $facultyMembers->count();
        $allFacultyIds = $facultyMembers->pluck('id');
        $totalFiles = \App\Models\DevelopmentObjectiveFile::whereHas('developmentObjective', function($q) use ($allFacultyIds) {
            $q->whereIn('user_id', $allFacultyIds);
        })->count();
        $pendingVerification = \App\Models\DevelopmentObjectiveFile::whereHas('developmentObjective', function($q) use ($allFacultyIds) {
            $q->whereIn('user_id', $allFacultyIds);
        })->where('verification_status', 'pending')->count();
        
        return view('chairperson.department-reports', compact('facultyMembers', 'totalFaculty', 'totalFiles', 'pendingVerification'));
    }
    
    /**
     * Handle chairperson logout.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
    }
}
