<?php

namespace App\Http\Controllers;

use App\Models\DevelopmentObjective;
use App\Models\DevelopmentObjectiveFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DevelopmentObjectiveController extends Controller
{
    /**
     * Display the user's development objectives.
     */
    public function index()
    {
        $user = Auth::user();
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $predefinedObjectives = DevelopmentObjective::getPredefinedObjectives();
        $adminObjectives = DevelopmentObjective::getAdminObjectives();
        
        return view('development-objectives.index', compact('objectives', 'predefinedObjectives', 'adminObjectives'));
    }

    /**
     * Display the user's development objectives list.
     */
    public function list()
    {
        $user = Auth::user();
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $availableYears = $objectives
            ->filter(function ($objective) {
                return $objective->created_at !== null;
            })
            ->map(function ($objective) {
                return $objective->created_at->format('Y');
            })
            ->unique()
            ->sortDesc()
            ->values();

        $selectedYear = request()->query('year');
        if (empty($selectedYear)) {
            $selectedYear = $availableYears->first() ?? now()->format('Y');
        }

        $idapObjectives = $objectives
            ->filter(function ($objective) use ($selectedYear) {
                return $objective->created_at !== null
                    && $objective->created_at->format('Y') === (string) $selectedYear;
            })
            ->values();

        // Fetch the department chair for the user's department
        $departmentChair = User::where('department', $user->department)
            ->where('role', 'chairperson')
            ->first();

        return view('development-objectives.list', compact('objectives', 'availableYears', 'selectedYear', 'idapObjectives', 'departmentChair'));
    }

    /**
     * Display the user's progress tracking page.
     */
    public function progress()
    {
        $user = Auth::user();
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('development-objectives.progress', compact('objectives'));
    }

    /**
     * Display the summary of LND page.
     */
    public function summary()
    {
        $user = Auth::user();
        $allObjectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $summaryAvailableYears = $allObjectives
            ->filter(function ($objective) {
                return $objective->created_at !== null;
            })
            ->map(function ($objective) {
                return $objective->created_at->format('Y');
            })
            ->unique()
            ->sortDesc()
            ->values();

        $summarySelectedYear = request()->query('summaryYear');
        if (empty($summarySelectedYear)) {
            $summarySelectedYear = $summaryAvailableYears->first() ?? now()->format('Y');
        }

        $objectives = $allObjectives
            ->filter(function ($objective) use ($summarySelectedYear) {
                return $objective->created_at !== null
                    && $objective->created_at->format('Y') === (string) $summarySelectedYear;
            })
            ->values();

        return view('development-objectives.summary', compact('objectives', 'summaryAvailableYears', 'summarySelectedYear'));
    }

    /**
     * Display the interventions conducted form.
     */
    public function interventionsConducted()
    {
        $user = Auth::user();
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('development-objectives.interventions-conducted', compact('objectives'));
    }

    /**
     * Display the interventions attended form.
     */
    public function interventionsAttended()
    {
        $user = Auth::user();
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('development-objectives.interventions-attended', compact('objectives'));
    }

    /**
     * Display the proposed interventions form.
     */
    public function proposedInterventions()
    {
        $user = Auth::user();
        $objectives = DevelopmentObjective::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('development-objectives.proposed-interventions', compact('objectives'));
    }

    /**
     * Show the add objective form.
     */
    public function add()
    {
        $predefinedObjectives = DevelopmentObjective::getPredefinedObjectives();
        $adminObjectives = DevelopmentObjective::getAdminObjectives();

        return view('development-objectives.add', compact('predefinedObjectives', 'adminObjectives'));
    }

    /**
     * Store a new development objective.
     */
    public function store(Request $request)
    {
        $request->validate([
            'objective' => 'required|string',
            'title' => 'required|string',
            'action_plan' => 'required|string',
            'number_of_hours' => 'required|integer|min:0',
            'budget_requirement' => 'nullable|numeric|min:0',
            'target_period' => 'nullable|string|in:Q1,Q2,Q3,Q4',
            'target_date_from' => 'nullable|string',
            'target_date_to' => 'nullable|string',
            'support_required' => 'nullable|string',
            'max_files' => 'required|integer|min:1|max:3',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $objectiveName = $request->objective;
        
        // If "Other" is selected, use the custom objective name from input
        if ($objectiveName === 'Other') {
            $objectiveName = $request->input('custom_objective');
            
            if (empty($objectiveName)) {
                return redirect()->route('development-objectives.index')
                    ->with('error', 'Please specify your custom objective.');
            }
        }
        
        // Check if user already has this objective (for non-"Other" objectives)
        if ($objectiveName !== 'Other') {
            $existingObjective = DevelopmentObjective::where('user_id', $user->id)
                ->where('objective', $objectiveName)
                ->first();
                
            if ($existingObjective) {
                return redirect()->route('development-objectives.index')
                    ->with('error', 'You have already added this development objective.');
            }
        }
        
        // Get max_files from faculty selection
        $maxFiles = $request->max_files;
        
        // Handle file upload
        $filePath = null;
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('development-objectives', $fileName, 'public');
        }
        
        $objective = DevelopmentObjective::create([
            'user_id' => $user->id,
            'objective' => $objectiveName,
            'title' => $request->title,
            'action_plan' => $request->action_plan,
            'number_of_hours' => $request->number_of_hours,
            'budget_requirement' => $request->budget_requirement,
            'target_period' => $request->target_period,
            'target_date_from' => $request->target_date_from,
            'target_date_to' => $request->target_date_to,
            'support_required' => $request->support_required,
            'status' => 'pending',
            'is_admin_created' => false,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'max_files' => $maxFiles,
        ]);

        // If file was uploaded, create file record
        if ($filePath && $fileName) {
            DevelopmentObjectiveFile::create([
                'development_objective_id' => $objective->id,
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);
        }

        return redirect()->route('development-objectives.list')
            ->with('success', 'Development objective added successfully!');
    }

    /**
     * Update the L&D intervention data for an objective.
     */
    public function updateLndData(Request $request, DevelopmentObjective $objective)
    {
        // Ensure the objective belongs to the authenticated user
        if ($objective->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'lnd_type' => 'required|string|max:255',
            'lnd_title' => 'required|string|max:255',
            'lnd_period_date' => 'required|string|max:255',
            'lnd_hours' => 'required|numeric|min:0',
            'lnd_proof_completion' => 'required|string',
        ]);

        $objective->update([
            'lnd_type' => $request->lnd_type,
            'lnd_title' => $request->lnd_title,
            'lnd_period_date' => $request->lnd_period_date,
            'lnd_hours' => $request->lnd_hours,
            'lnd_proof_completion' => $request->lnd_proof_completion,
        ]);

        return response()->json(['success' => true, 'message' => 'L&D data updated successfully!']);
    }

    /**
     * Update the status of a development objective.
     */
    public function updateStatus(Request $request, DevelopmentObjective $objective)
    {
        // Ensure the objective belongs to the authenticated user
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $objective->update([
            'status' => $request->status,
        ]);

        return redirect()->route('development-objectives.index')
            ->with('success', 'Objective status updated successfully!');
    }

    /**
     * Delete a development objective.
     */
    public function destroy(DevelopmentObjective $objective)
    {
        // Ensure the objective belongs to the authenticated user
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $objective->delete();

        return redirect()->route('development-objectives.index')
            ->with('success', 'Development objective deleted successfully!');
    }

    /**
     * Display admin development objectives management page.
     */
    public function adminIndex(Request $request)
    {
        $adminObjectives = DevelopmentObjective::where('is_admin_created', true)
            ->whereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->get();

        // All unique objective names: admin-created + faculty-adopted
        $adminObjNames  = DevelopmentObjective::where('is_admin_created', true)->whereNull('user_id')->pluck('objective');
        $facultyObjNames = DevelopmentObjective::whereNotNull('user_id')->pluck('objective')->unique();
        $allObjectiveNames = $adminObjNames->merge($facultyObjNames)->unique()->sort()->values();

        $departments = ['DAFE', 'DCEA', 'DCEEE', 'DIET', 'DIT'];

        $selectedObjective  = $request->query('objective');
        $selectedDepartment = $request->query('department');

        $q = DevelopmentObjective::with('user')
            ->withCount([
                'files as total_files',
                'files as approved_files' => fn($fq) => $fq->where('verification_status', 'approved'),
            ])
            ->whereNotNull('user_id');

        if ($selectedObjective) {
            $q->where('objective', $selectedObjective);
        }
        if ($selectedDepartment) {
            $q->whereHas('user', fn($uq) => $uq->where('department', $selectedDepartment));
        }

        $facultyRecords = $q->get()->sortBy(function ($record) {
                $user = $record->user;
                return $user ? strtolower($user->last_name . ' ' . $user->first_name) : 'zzz';
            })->values();

        // Compute stats across all matching records before paginating
        $statsTotal     = $facultyRecords->count();
        $statsPending   = $facultyRecords->where('status', 'pending')->count();
        $statsInProg    = $facultyRecords->where('status', 'in_progress')->count();
        $statsCompleted = $facultyRecords->where('status', 'completed')->count();

        // Paginate manually (5 per page) to preserve the custom sort order
        $perPage    = 5;
        $page       = (int) $request->query('page', 1);
        $pageItems  = $facultyRecords->slice(($page - 1) * $perPage, $perPage)->values();
        $facultyRecords = new \Illuminate\Pagination\LengthAwarePaginator(
            $pageItems,
            $statsTotal,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.development-objectives', compact(
            'adminObjectives',
            'allObjectiveNames',
            'departments',
            'selectedObjective',
            'selectedDepartment',
            'facultyRecords',
            'statsTotal',
            'statsPending',
            'statsInProg',
            'statsCompleted'
        ));
    }

    /**
     * Store a new admin development objective.
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'objective' => 'required|string',
            'action_plan' => 'required|string',
        ]);

        DevelopmentObjective::create([
            'user_id' => null,
            'objective' => $request->objective,
            'action_plan' => $request->action_plan,
            'status' => 'pending',
            'is_admin_created' => true,
        ]);

        return redirect()->route('admin.development-objectives')
            ->with('success', 'Development objective added successfully! This will be available to all faculty members.');
    }

    /**
     * Delete an admin development objective.
     */
    public function adminDestroy(DevelopmentObjective $objective)
    {
        // Ensure this is an admin-created objective
        if (!$objective->is_admin_created || $objective->user_id !== null) {
            abort(403, 'Unauthorized action.');
        }

        $objective->delete();

        return redirect()->route('admin.development-objectives')
            ->with('success', 'Development objective deleted successfully!');
    }

    /**
     * Upload file for an existing development objective.
     */
    public function uploadFile(Request $request, DevelopmentObjective $objective)
    {
        // Ensure user owns this objective
        if ($objective->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Check if user has reached the maximum file limit
        $currentFileCount = $objective->files()->count();
        if ($currentFileCount >= $objective->max_files) {
            return redirect()->route('development-objectives.list')
                ->with('error', "You have reached the maximum file limit of {$objective->max_files} files for this objective.");
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('development-objectives', $fileName, 'public');
            
            // Create file record
            DevelopmentObjectiveFile::create([
                'development_objective_id' => $objective->id,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'verification_status' => 'pending', // Files start as pending verification
            ]);

            // Update the main objective file_path and file_name to the latest file
            $objective->update([
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

            // Check if user has uploaded the required number of files and update status based on approved files only
            $approvedFileCount = $objective->files()->where('verification_status', 'approved')->count();
            $totalFileCount = $objective->files()->count();
            
            if ($approvedFileCount >= $objective->max_files) {
                // Mark as completed if enough approved files
                $objective->update(['status' => 'completed']);
            } elseif ($objective->status === 'completed' && $approvedFileCount < $objective->max_files) {
                // Revert to in_progress if not enough approved files
                $objective->update(['status' => 'in_progress']);
            } else {
                // Mark as in_progress if there are files but not completed
                $objective->update(['status' => 'in_progress']);
            }

            return redirect()->route('development-objectives.list')
                ->with('success', "File uploaded successfully! ({$totalFileCount}/{$objective->max_files} files)");
        }

        return redirect()->route('development-objectives.list')
            ->with('error', 'No file uploaded.');
    }

    /**
     * Update max_files for an existing development objective.
     */
    public function updateMaxFiles(Request $request, DevelopmentObjective $objective)
    {
        // Ensure the objective belongs to the authenticated user
        if ($objective->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        // Only allow updating max_files if no files have been uploaded yet
        if ($objective->files()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot update file limit after files have been uploaded.'], 400);
        }

        $request->validate([
            'max_files' => 'required|integer|min:1|max:3',
        ]);

        $objective->update([
            'max_files' => $request->max_files,
        ]);

        return response()->json(['success' => true, 'message' => 'File limit updated successfully.']);
    }

    /**
     * Delete a file from a development objective.
     */
    public function deleteFile(Request $request, DevelopmentObjective $objective)
    {
        $request->validate([
            'file_id' => 'required|exists:development_objective_files,id',
        ]);

        $file = DevelopmentObjectiveFile::find($request->file_id);
        
        // Ensure file belongs to the objective and user
        if ($file->development_objective_id !== $objective->id || $objective->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the file
        $filePath = $file->file_path;
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $file->delete();

        // Recalculate objective status based on approved files only
        $approvedFileCount = $objective->files()->where('verification_status', 'approved')->count();
        $totalFileCount = $objective->files()->count();

        if ($approvedFileCount >= $objective->max_files) {
            // Mark as completed if enough approved files
            $objective->update(['status' => 'completed']);
        } elseif ($objective->status === 'completed' && $approvedFileCount < $objective->max_files) {
            // Revert to in_progress if not enough approved files
            $objective->update(['status' => 'in_progress']);
        } else {
            // Mark as in_progress if there are files but not completed
            $objective->update(['status' => 'in_progress']);
        }

        return redirect()->route('development-objectives.list')
            ->with('success', 'File deleted successfully!');
    }

    public function previewFile(DevelopmentObjectiveFile $file)
    {
        if ($file->developmentObjective->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        $ext       = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
        $localPath = Storage::disk('public')->path($file->file_path);

        if (in_array($ext, ['docx', 'doc'])) {
            try {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($localPath);
                $writer  = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
                $tmpFile = tempnam(sys_get_temp_dir(), 'docx_') . '.html';
                $writer->save($tmpFile);
                $rawHtml = file_get_contents($tmpFile);
                @unlink($tmpFile);
                $body = preg_match('/<body[^>]*>(.*?)<\/body>/si', $rawHtml, $m) ? $m[1] : $rawHtml;
            } catch (\Throwable $e) {
                $body = '<p style="color:#ef4444;">Could not render document: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            return response($this->wrapPreviewHtml($body, $file->file_name), 200)
                ->header('Content-Type', 'text/html; charset=UTF-8')
                ->header('Cache-Control', 'no-store');
        }

        $mimeMap = [
            'pdf'  => 'application/pdf',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
        ];
        $mime    = $mimeMap[$ext] ?? 'application/octet-stream';
        $content = Storage::disk('public')->get($file->file_path);

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . addslashes($file->file_name) . '"')
            ->header('Cache-Control', 'no-store');
    }

    private function wrapPreviewHtml(string $content, string $fileName): string
    {
        return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  *{box-sizing:border-box;}
  body{margin:0;padding:24px 28px;font-family:"Calibri","Segoe UI",Arial,sans-serif;font-size:11pt;line-height:1.5;color:#1f2937;background:#ffffff;}
  table{border-collapse:collapse;width:100%;margin:8px 0;}
  td,th{border:1px solid #9ca3af;padding:4px 8px;vertical-align:top;}
  p{margin:0 0 4px;}
  img{max-width:100%;}
</style></head><body>' . $content . '</body></html>';
    }
}
