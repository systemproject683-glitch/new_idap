<?php

namespace App\Http\Controllers;

use App\Models\DevelopmentObjectiveFile;
use App\Models\DevelopmentObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileVerificationController extends Controller
{
    /**
     * Display pending files for verification.
     */
    public function index()
    {
        $chairperson = Auth::user();
        
        // Get pending files from faculty in the same department
        $pendingFiles = DevelopmentObjectiveFile::with(['developmentObjective.user', 'verifiedBy'])
            ->where('verification_status', 'pending')
            ->whereHas('developmentObjective.user', function($query) use ($chairperson) {
                $query->where('department', $chairperson->department);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('chairperson.file-verification', compact('pendingFiles'));
    }
    
    /**
     * Show file details for verification.
     */
    public function show(DevelopmentObjectiveFile $file)
    {
        $chairperson = Auth::user();
        
        // Ensure the file is from faculty in the same department
        if ($file->developmentObjective->user->department !== $chairperson->department) {
            abort(403, 'Unauthorized action.');
        }
        
        $file->load(['developmentObjective.user', 'verifiedBy']);
        
        return view('chairperson.file-details', compact('file'));
    }
    
    /**
     * Approve a file.
     */
    public function approve(Request $request, DevelopmentObjectiveFile $file)
    {
        $chairperson = Auth::user();
        
        // Ensure the file is from faculty in the same department
        if ($file->developmentObjective->user->department !== $chairperson->department) {
            abort(403, 'Unauthorized action.');
        }
        
        // Update file verification status
        $file->update([
            'verification_status' => 'approved',
            'verified_at' => now(),
            'verified_by' => $chairperson->id,
        ]);
        
        // Check if objective should be marked as completed
        $objective = $file->developmentObjective;
        $approvedFileCount = $objective->files()->where('verification_status', 'approved')->count();
        
        if ($approvedFileCount >= $objective->max_files) {
            $objective->update([
                'status' => 'completed'
            ]);
            
            return redirect()->route('chairperson.department-reports')
                ->with('success', 'File approved! Objective marked as completed.');
        }
        
        return redirect()->route('chairperson.department-reports')
            ->with('success', 'File approved successfully!');
    }
    
    /**
     * Reject a file.
     */
    public function reject(Request $request, DevelopmentObjectiveFile $file)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);
        
        $chairperson = Auth::user();
        
        // Ensure the file is from faculty in the same department
        if ($file->developmentObjective->user->department !== $chairperson->department) {
            abort(403, 'Unauthorized action.');
        }
        
        // Update file verification status
        $file->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_at' => now(),
            'verified_by' => $chairperson->id,
        ]);
        
        // Check if objective status should be changed back
        $objective = $file->developmentObjective;
        $approvedFileCount = $objective->files()->where('verification_status', 'approved')->count();
        
        if ($objective->status === 'completed' && $approvedFileCount < $objective->max_files) {
            $objective->update([
                'status' => 'in_progress'
            ]);
        }
        
        return redirect()->route('chairperson.department-reports')
            ->with('success', 'File rejected successfully!');
    }
    
    /**
     * Stream file content for in-browser preview.
     * Returns styled HTML for DOCX/XLSX, binary inline for PDF/images.
     */
    public function preview(DevelopmentObjectiveFile $file)
    {
        $chairperson = Auth::user();

        if ($file->developmentObjective->user->department !== $chairperson->department) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        $ext       = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));
        $localPath = Storage::disk('public')->path($file->file_path);

        if (in_array($ext, ['docx', 'doc'])) {
            $html = $this->docxToHtml($localPath, $file->file_name);
            return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8')->header('Cache-Control', 'no-store');
        }

        if (in_array($ext, ['xlsx', 'xls'])) {
            $html = $this->xlsxToHtml($localPath, $file->file_name);
            return response($html, 200)->header('Content-Type', 'text/html; charset=UTF-8')->header('Cache-Control', 'no-store');
        }

        $mimeMap = [
            'pdf'  => 'application/pdf',
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
        ];
        $mime    = $mimeMap[$ext] ?? 'application/octet-stream';
        $content = Storage::disk('public')->get($file->file_path);

        return response($content, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . addslashes($file->file_name) . '"')
            ->header('Cache-Control', 'no-store');
    }

    /* ------------------------------------------------------------------ */
    /*  DOCX → HTML  (phpoffice/phpword)                                  */
    /* ------------------------------------------------------------------ */
    private function docxToHtml(string $path, string $fileName): string
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($path);

            $writer     = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $tmpFile    = tempnam(sys_get_temp_dir(), 'docx_') . '.html';
            $writer->save($tmpFile);
            $rawHtml    = file_get_contents($tmpFile);
            @unlink($tmpFile);

            // Extract just the <body> content from the generated HTML
            if (preg_match('/<body[^>]*>(.*?)<\/body>/si', $rawHtml, $m)) {
                $body = $m[1];
            } else {
                $body = $rawHtml;
            }

            return $this->wrapHtml($body, $fileName);
        } catch (\Throwable $e) {
            return $this->wrapHtml('<p style="color:#ef4444;">Could not render document: ' . htmlspecialchars($e->getMessage()) . '</p>', $fileName);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  XLSX → HTML  (phpoffice/phpspreadsheet)                           */
    /* ------------------------------------------------------------------ */
    private function xlsxToHtml(string $path, string $fileName): string
    {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

            $writer  = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);
            $writer->setSheetIndex(0);
            $rawHtml = $writer->generateHTMLAll();

            // Extract <body> content
            if (preg_match('/<body[^>]*>(.*?)<\/body>/si', $rawHtml, $m)) {
                $body = $m[1];
            } else {
                $body = $rawHtml;
            }

            // Extract any <style> blocks generated by phpspreadsheet
            $styles = '';
            preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $rawHtml, $sm);
            foreach ($sm[1] as $s) $styles .= $s;

            return $this->wrapHtml($body, $fileName, $styles);
        } catch (\Throwable $e) {
            return $this->wrapHtml('<p style="color:#ef4444;">Could not render spreadsheet: ' . htmlspecialchars($e->getMessage()) . '</p>', $fileName);
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Wrap content in a clean standalone HTML page                       */
    /* ------------------------------------------------------------------ */
    private function wrapHtml(string $content, string $fileName, string $extraStyles = ''): string
    {
        return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  *{box-sizing:border-box;}
  body{margin:0;padding:24px 28px;font-family:"Calibri","Segoe UI",Arial,sans-serif;font-size:11pt;line-height:1.5;color:#1f2937;background:#ffffff;}
  table{border-collapse:collapse;width:100%;margin:8px 0;}
  td,th{border:1px solid #9ca3af;padding:4px 8px;vertical-align:top;}
  p{margin:0 0 4px;}
  img{max-width:100%;}
  ' . $extraStyles . '
</style></head><body>' . $content . '</body></html>';
    }

    /**
     * Download file for review.
     */
    public function download(DevelopmentObjectiveFile $file)
    {
        $chairperson = Auth::user();

        // Ensure the file belongs to faculty in the same department
        $objective = $file->developmentObjective;
        $owner     = $objective?->user;

        if (!$objective || !$owner || $owner->department !== $chairperson->department) {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found on server.');
        }

        return Storage::disk('public')->download($file->file_path, $file->file_name);
    }
}
