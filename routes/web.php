<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DevelopmentObjectiveController;
use App\Http\Controllers\ChairpersonController;
use App\Http\Controllers\FileVerificationController;
use App\Http\Controllers\SocialAuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::post('/login', [AdminController::class, 'authenticate'])->name('login.submit');

// Google OAuth Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Admin Routes (Protected)
Route::middleware(['admin', 'nocache'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    Route::get('/create-user', [AdminController::class, 'createUser'])->name('create.user');
    Route::post('/store-user', [AdminController::class, 'storeUser'])->name('store.user');
    Route::put('/update-user/{user}', [AdminController::class, 'updateUser'])->name('update.user');
    Route::delete('/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('delete.user');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Development Objectives Management
    Route::get('/development-objectives', [DevelopmentObjectiveController::class, 'adminIndex'])->name('development-objectives');
    Route::post('/development-objectives', [DevelopmentObjectiveController::class, 'adminStore'])->name('development-objectives.store');
    Route::delete('/development-objectives/{objective}', [DevelopmentObjectiveController::class, 'adminDestroy'])->name('development-objectives.destroy');

    // Recent Activities
    Route::get('/recent-activities', [AdminController::class, 'recentActivities'])->name('recent-activities');
});

// Chairperson Routes (Protected)
Route::middleware(['chairperson', 'nocache'])->prefix('chairperson')->name('chairperson.')->group(function () {
    Route::get('/dashboard', [ChairpersonController::class, 'dashboard'])->name('dashboard');
    Route::get('/faculty-members', [ChairpersonController::class, 'facultyMembers'])->name('faculty-members');
    Route::get('/faculty-member/{user}', [ChairpersonController::class, 'facultyMemberDetails'])->name('faculty-member-details');
    Route::get('/department-reports', [ChairpersonController::class, 'departmentReports'])->name('department-reports');
    Route::get('/summary-lnd', [ChairpersonController::class, 'summaryLnd'])->name('summary-lnd');
    Route::post('/summary-lnd/add-proposed', [ChairpersonController::class, 'addProposedFormData'])->name('add-proposed');
    Route::post('/summary-lnd/add-conducted', [ChairpersonController::class, 'addConductedFormData'])->name('add-conducted');
    
    // File Verification Routes
    Route::get('/file-verification', [FileVerificationController::class, 'index'])->name('file-verification');
    Route::get('/file-verification/{file}', [FileVerificationController::class, 'show'])->name('file-verification.show');
    Route::post('/file-verification/{file}/approve', [FileVerificationController::class, 'approve'])->name('file-verification.approve');
    Route::post('/file-verification/{file}/reject', [FileVerificationController::class, 'reject'])->name('file-verification.reject');
    Route::get('/development-objectives', [ChairpersonController::class, 'developmentObjectives'])->name('development-objectives');
    
    Route::post('/logout', [ChairpersonController::class, 'logout'])->name('logout');
});

// File download & preview — chairperson only, NO nocache so binary responses work correctly
Route::middleware(['chairperson'])->prefix('chairperson')->name('chairperson.')->group(function () {
    Route::get('/file-verification/{file}/download', [FileVerificationController::class, 'download'])->name('file-verification.download');
    Route::get('/file-verification/{file}/preview', [FileVerificationController::class, 'preview'])->name('file-verification.preview');
});

// Faculty Routes (Protected)
Route::middleware(['auth', 'nocache'])->prefix('development-objectives')->name('development-objectives.')->group(function () {
    Route::get('/', [DevelopmentObjectiveController::class, 'index'])->name('index');
    Route::get('/add', [DevelopmentObjectiveController::class, 'add'])->name('add');
    Route::get('/list', [DevelopmentObjectiveController::class, 'list'])->name('list');
    Route::get('/progress', [DevelopmentObjectiveController::class, 'progress'])->name('progress');
    Route::get('/summary', [DevelopmentObjectiveController::class, 'summary'])->name('summary');
    Route::get('/interventions-conducted', [DevelopmentObjectiveController::class, 'interventionsConducted'])->name('interventions-conducted');
    Route::get('/interventions-attended', [DevelopmentObjectiveController::class, 'interventionsAttended'])->name('interventions-attended');
    Route::get('/proposed-interventions', [DevelopmentObjectiveController::class, 'proposedInterventions'])->name('proposed-interventions');
    Route::post('/', [DevelopmentObjectiveController::class, 'store'])->name('store');
    Route::put('/{objective}/status', [DevelopmentObjectiveController::class, 'updateStatus'])->name('update-status');
    Route::put('/{objective}/lnd-data', [DevelopmentObjectiveController::class, 'updateLndData'])->name('update-lnd-data');
    Route::delete('/{objective}', [DevelopmentObjectiveController::class, 'destroy'])->name('destroy');
    Route::post('/{objective}/upload-file', [DevelopmentObjectiveController::class, 'uploadFile'])->name('upload-file');
    Route::post('/{objective}/update-max-files', [DevelopmentObjectiveController::class, 'updateMaxFiles'])->name('update-max-files');
    Route::delete('/{objective}/delete-file', [DevelopmentObjectiveController::class, 'deleteFile'])->name('delete-file');
    Route::get('/files/{file}/preview', [DevelopmentObjectiveController::class, 'previewFile'])->name('preview-file');
});

// Backward-compatible alias for legacy route('summary') references.
Route::middleware(['auth', 'nocache'])->get('/summary', [DevelopmentObjectiveController::class, 'summary'])->name('summary');

// Handle GET /logout (e.g. browser redirect after session expiry)
Route::get('/logout', function () {
    return redirect()->route('login');
});

// Default logout route for regular users
Route::post('/logout', function () {
    Auth::logout();
    
    // Invalidate current session
    request()->session()->invalidate();
    
    // Regenerate CSRF token
    request()->session()->regenerateToken();
    
    return redirect()->route('login')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT')
        ->header('Clear-Site-Data', '"cache", "storage", "executionContexts"')
        ->header('Vary', '*')
        ->header('X-Content-Type-Options', 'nosniff');
})->name('logout');
