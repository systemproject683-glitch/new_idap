<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DevelopmentObjective;
use App\Models\ProposedIntervention;
use App\Models\ConductedIntervention;
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
     * Display summary of Learning and Development for the department.
     */
    public function summaryLnd(Request $request)
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
        
        // Get statistics
        $totalFaculty = $facultyMembers->count();
        $allObjectives = DevelopmentObjective::whereHas('user', function($query) use ($chairperson) {
            $query->where('department', $chairperson->department);
        })->get();
        
        $totalObjectives = $allObjectives->count();
        $completedObjectives = $allObjectives->where('status', 'completed')->count();
        $inProgressObjectives = $allObjectives->where('status', 'in_progress')->count();
        $pendingObjectives = $allObjectives->where('status', 'pending')->count();

        // Get all proposed interventions to calculate available years
        $allProposedInterventions = ProposedIntervention::where('user_id', $chairperson->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate available years from proposed interventions
        $proposedAvailableYears = $allProposedInterventions
            ->map(function ($item) {
                return $item->created_at->format('Y');
            })
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        // Get all conducted interventions to calculate available years
        $allConductedInterventions = ConductedIntervention::where('user_id', $chairperson->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Calculate available years from conducted interventions
        $conductedAvailableYears = $allConductedInterventions
            ->map(function ($item) {
                return $item->created_at->format('Y');
            })
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        // Merge all available years
        $summaryAvailableYears = $proposedAvailableYears->merge($conductedAvailableYears)
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        // Get selected year from query parameter
        $summarySelectedYear = $request->query('summaryYear');

        // If no year is selected or invalid, default to the most recent year
        if (!$summarySelectedYear || !$summaryAvailableYears->contains($summarySelectedYear)) {
            $summarySelectedYear = $summaryAvailableYears->first();
        }

        // Filter proposed interventions by selected year
        $proposedInterventions = $allProposedInterventions
            ->filter(function ($item) use ($summarySelectedYear) {
                return $item->created_at->format('Y') === (string) $summarySelectedYear;
            })
            ->unique(function ($item) {
                return implode('|', [
                    trim((string) ($item->title ?? '')),
                    trim((string) ($item->objectives ?? '')),
                    trim((string) ($item->budget ?? '')),
                    trim((string) ($item->expected_number_of_participants ?? '')),
                    trim((string) ($item->dates ?? '')),
                    trim((string) ($item->person_responsible ?? '')),
                    trim((string) ($item->target_participants ?? '')),
                ]);
            })
            ->values();

        // Filter conducted interventions by selected year
        $conductedInterventions = $allConductedInterventions
            ->filter(function ($item) use ($summarySelectedYear) {
                return $item->created_at->format('Y') === (string) $summarySelectedYear;
            });
        
        // Calculate overall completion rate
        $completionRate = $totalObjectives > 0 ? round(($completedObjectives / $totalObjectives) * 100, 1) : 0;
        
        return view('chairperson.summary-lnd', compact(
            'facultyMembers',
            'totalFaculty',
            'totalObjectives',
            'completedObjectives',
            'inProgressObjectives',
            'pendingObjectives',
            'completionRate',
            'proposedInterventions',
            'conductedInterventions',
            'summaryAvailableYears',
            'summarySelectedYear'
        ));
    }

    /**
     * Display development objectives for the chairperson's department.
     */
    public function developmentObjectives(Request $request)
    {
        $chairperson = Auth::user();
        $department  = $chairperson->department;

        // All unique objective names used by faculty in this department
        $allObjectiveNames = DevelopmentObjective::whereNotNull('user_id')
            ->whereHas('user', fn($q) => $q->where('department', $department))
            ->pluck('objective')
            ->merge(
                DevelopmentObjective::where('is_admin_created', true)->whereNull('user_id')->pluck('objective')
            )
            ->unique()->sort()->values();

        $selectedObjective = $request->query('objective');

        $query = DevelopmentObjective::with('user')
            ->withCount([
                'files as total_files',
                'files as approved_files' => fn($fq) => $fq->where('verification_status', 'approved'),
            ])
            ->whereNotNull('user_id')
            ->whereHas('user', fn($uq) => $uq->where('department', $department))
            ->orderBy('created_at', 'desc');

        if ($selectedObjective) {
            $query->where('objective', $selectedObjective);
        }

        $facultyRecords = $query->get()->sortBy(function ($record) {
            $user = $record->user;
            return $user ? strtolower($user->last_name . ' ' . $user->first_name) : 'zzz';
        })->values();

        // Compute stats across all matching records before paginating
        $statsTotal     = $facultyRecords->count();
        $statsPending   = $facultyRecords->where('status', 'pending')->count();
        $statsInProg    = $facultyRecords->where('status', 'in_progress')->count();
        $statsCompleted = $facultyRecords->where('status', 'completed')->count();

        // Paginate manually (5 per page) to preserve the custom sort order
        $perPage   = 5;
        $page      = (int) $request->query('page', 1);
        $pageItems = $facultyRecords->slice(($page - 1) * $perPage, $perPage)->values();
        $facultyRecords = new \Illuminate\Pagination\LengthAwarePaginator(
            $pageItems,
            $statsTotal,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('chairperson.development-objectives', compact(
            'allObjectiveNames',
            'department',
            'selectedObjective',
            'facultyRecords',
            'statsTotal',
            'statsPending',
            'statsInProg',
            'statsCompleted'
        ));
    }

    /**
     * Add proposed form data to database
     */
    public function addProposedFormData(Request $request)
    {
        try {
            $chairperson = Auth::user();
            
            $validated = $request->validate([
                'proposed_title' => 'required|string',
                'proposed_objectives' => 'required|string',
                'proposed_budget' => 'nullable|string',
                'proposed_expected_participants' => 'nullable|numeric',
                'proposed_dates' => 'nullable|date_format:Y-m-d',
                'proposed_person_responsible' => 'nullable|string',
                'proposed_target_participants' => 'nullable|string',
            ]);

            $payload = [
                'user_id' => $chairperson->id,
                'title' => $validated['proposed_title'],
                'objectives' => $validated['proposed_objectives'],
                'budget' => $validated['proposed_budget'] ?: null,
                'dates' => $validated['proposed_dates'] ?: null,
                'person_responsible' => $validated['proposed_person_responsible'] ?: null,
                'target_participants' => $validated['proposed_target_participants'] ?: null,
                'expected_number_of_participants' => is_numeric($validated['proposed_expected_participants'] ?? null)
                    ? intval($validated['proposed_expected_participants'])
                    : null,
            ];

            $proposedIntervention = ProposedIntervention::firstOrCreate($payload);

            if (!$proposedIntervention->wasRecentlyCreated) {
                return response()->json([
                    'success' => false,
                    'message' => 'This proposed row already exists.',
                ], 409);
            }

            return response()->json([
                'success' => true,
                'message' => 'Proposed form data saved successfully',
                'data' => $proposedIntervention
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', collect($e->errors())->flatten()->all()),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error saving proposed form data: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add conducted form data to database
     */
    public function addConductedFormData(Request $request)
    {
        try {
            $chairperson = Auth::user();
            
            $validated = $request->validate([
                'conducted_type' => 'required|string',
                'conducted_title' => 'required|string',
                'conducted_date' => 'nullable|date_format:Y-m-d',
                'conducted_duration' => 'nullable|string',
                'conducted_leaving_service' => 'nullable|string',
                'conducted_target_participants' => 'nullable|numeric',
                'conducted_actual_participants' => 'nullable|numeric',
                'conducted_completion_date' => 'nullable|numeric|min:0|max:100',
                'conducted_proof' => 'nullable|string',
            ]);

            $conductedIntervention = ConductedIntervention::create([
                'user_id' => $chairperson->id,
                'type_of_lnd' => $validated['conducted_type'],
                'title' => $validated['conducted_title'],
                'date_conducted' => $validated['conducted_date'] ?: null,
                'duration' => $validated['conducted_duration'] ?: null,
                'leaving_service_provided' => $validated['conducted_leaving_service'] ?: null,
                'target_number_of_participants' => is_numeric($validated['conducted_target_participants']) ? intval($validated['conducted_target_participants']) : null,
                'actual_number_of_participants' => is_numeric($validated['conducted_actual_participants']) ? intval($validated['conducted_actual_participants']) : null,
                'completion_rate' => is_numeric($validated['conducted_completion_date']) ? intval($validated['conducted_completion_date']) : null,
                'proof_of_documentation' => $validated['conducted_proof'] ?: null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Conducted form data saved successfully',
                'data' => $conductedIntervention
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', collect($e->errors())->flatten()->all()),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error saving conducted form data: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
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
