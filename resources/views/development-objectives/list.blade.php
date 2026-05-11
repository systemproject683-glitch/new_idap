<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Development Objectives - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/development-objectives-list.css') }}">
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('development-objectives.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <div class="p-8 page-content">
                <!-- Header -->
                <div class="header-bar page-header-fixed">
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">CEIT / <span class="text-orange-600 font-semibold">Development Objectives</span></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600 text-base">{{ now()->format('F d, Y') }}</p>
                            <span class="text-gray-300 text-base">|</span>
                            <span id="live-time" class="text-orange-500 font-semibold text-base"></span>
                        </div>
                    </div>
                </div>
                <div class="page-header-spacer"></div>



                @if(session('success') || session('error'))
                    <div class="alert-popup" id="alert-popup">
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                @endif

                @php
                    $totalObjectives = $objectives->count();
                    $pendingObjectives = $objectives->where('status', 'pending')->count();
                    $completedObjectives = $objectives->where('status', 'completed')->count();
                    $inProgressObjectives = $objectives->where('status', 'in_progress')->count();
                    $progressPercent = $totalObjectives > 0
                        ? round(($completedObjectives / $totalObjectives) * 100)
                        : 0;
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="p-2 scrollable-card-body">
                            @if($objectives->count() > 0)
                                <div class="space-y-4">
                                    @foreach($objectives as $objective)
                                        <div id="objective-{{ $objective->id }}" class="objective-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition relative">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="relative">
                                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                                        <span class="text-[#ff6b35]">Target: </span> {{ $objective->objective }}
                                                    </h3>
                                                    <div class="absolute top-0 right-0 z-10 flex flex-row-reverse items-center gap-0">
                                                        <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                        </span>
                                                        @if($objective->status === 'pending')
                                                            <button
                                                                type="button"
                                                                onclick="openEditForm({{ $objective->id }})"
                                                                class="btn-primary text-white px-3 py-1 rounded-full text-base font-semibold hover:bg-orange-600 transition whitespace-nowrap"
                                                                style="margin-right: -50px;"
                                                            >
                                                                Edit Form
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <p class="text-gray-600 mb-3">
                                                    <span class="text-[#ff6b35]">Action Plan: </span> {{ $objective->action_plan }}
                                                </p>
                                                <hr></hr>
                    
                                                <div class="flex items-center gap-4 mb-4 mt-3">
                                                    <span class="text-sm text-gray-500">
                                                        Created: {{ $objective->created_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        Hours: {{ $objective->number_of_hours !== null ? $objective->number_of_hours . ' hrs' : 'N/A' }}
                                                    </span>
                                                </div>

                                                <!-- File Upload Section -->
                                                <div class="mb-3">
                                                    @if($objective->max_files > 0)
                                                        @php
                                                            $fileCount = $objective->files->count();
                                                            $approvedFileCount = $objective->files->where('verification_status', 'approved')->count();
                                                            $percentage = ($approvedFileCount / $objective->max_files) * 100;
                                                        @endphp

                                                        <!-- Progress Bar -->
                                                        <div class="mb-3">
                                                            <div class="flex justify-between items-center mb-1">
                                                                <label for="file_{{ $objective->id }}" class="block text-gray-700 text-sm font-medium">
                                                                    Upload File/Certificate
                                                                </label>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-xs text-gray-500">
                                                                        {{ $approvedFileCount }}/{{ $objective->max_files }} approved files
                                                                    </span>
                                                                    @if($fileCount > $approvedFileCount)
                                                                        <span class="text-xs text-orange-500">
                                                                            ({{ $fileCount - $approvedFileCount }} pending)
                                                                        </span>
                                                                    @endif
                                                                    <span class="text-xs font-medium
                                                                        @if($objective->status === 'completed') text-green-700
                                                                        @elseif($objective->status === 'in_progress') text-blue-600
                                                                        @else text-orange-500
                                                                        @endif">
                                                                        {{ round($percentage) }}% Complete
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                                <div class="h-2 rounded-full transition-all duration-300
                                                                    @if($objective->status === 'completed') bg-green-500
                                                                    @elseif($objective->status === 'in_progress') bg-blue-500
                                                                    @else bg-orange-400
                                                                    @endif"
                                                                     style="width: {{ min($percentage, 100) }}%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label for="file_{{ $objective->id }}" class="block text-gray-700 text-sm font-medium mb-2">
                                                            Upload File/Certificate (Optional)
                                                        </label>
                                                    @endif

                                                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                                                        <form method="POST" action="{{ route('development-objectives.upload-file', $objective->id) }}"
                                                              enctype="multipart/form-data" class="space-y-3 lg:col-span-2">
                                                            @csrf
                                                            <div class="flex flex-col gap-3">
                                                                <label for="file_{{ $objective->id }}" class="upload-dropzone dropzone-offset flex items-center gap-4 cursor-pointer">
                                                                    <div class="flex items-center justify-center">
                                                                        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 15a4 4 0 0 0 4 4h9a4 4 0 0 0 0-8h-.2A5 5 0 0 0 6 9.7V9a4 4 0 0 0-3 6z"></path>
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 12v7m0-7l-3 3m3-3l3 3"></path>
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-sm font-semibold text-gray-700">
                                                                            Upload files here.
                                                                        </p>
                                                                        <p class="text-xs text-gray-700" id="file_name_{{ $objective->id }}">
                                                                            No file selected
                                                                        </p>
                                                                        <p class="text-xs text-gray-500">
                                                                            Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                                                                        </p>
                                                                    </div>
                                                                </label>
                                                                <div class="flex items-center justify-between">
                                                                    @if($objective->max_files > 0 && $objective->files->count() >= $objective->max_files)
                                                                        <p class="text-xs text-red-500">
                                                                            Maximum file limit reached ({{ $objective->max_files }} files)
                                                                        </p>
                                                                    @else
                                                                        <span></span>
                                                                    @endif
                                                                    <button type="submit" class="btn-primary text-white px-4 py-2 rounded text-sm"
                                                                            @if($objective->max_files > 0 && $objective->files->count() >= $objective->max_files) disabled @endif>
                                                                        Upload
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <input
                                                                type="file"
                                                                id="file_{{ $objective->id }}"
                                                                name="file"
                                                                data-filename-target="file_name_{{ $objective->id }}"
                                                                class="hidden"
                                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                                @if($objective->max_files > 0 && $objective->files->count() >= $objective->max_files) disabled @endif
                                                            >
                                                            @error('file')
                                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </form>

                                                        <div class="space-y-2 lg:col-span-3">
                                                            <div class="flex items-center justify-between">
                                                                <p class="text-sm font-semibold text-gray-700">Uploaded Files</p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ $objective->files->count() }}
                                                                    @if($objective->max_files > 0)
                                                                        / {{ $objective->max_files }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                            @if($objective->files->count() > 0)
                                                                <div class="space-y-2">
                                                                    @foreach($objective->files as $file)
                                                                        <div class="file-row flex items-center justify-between gap-3">
                                                                            <div class="flex items-center gap-3 min-w-0">
                                                                                <div class="w-9 h-9 rounded bg-orange-50 flex items-center justify-center">
                                                                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                    </svg>
                                                                                </div>
                                                                                <div class="file-info">
                                                                                    @php
                                                                                        $filePath = storage_path('app/public/' . $file->file_path);
                                                                                        $fileSizeLabel = null;
                                                                                        if (file_exists($filePath)) {
                                                                                            $fileSize = filesize($filePath);
                                                                                            if ($fileSize !== false) {
                                                                                                $fileSizeLabel = $fileSize >= 1048576
                                                                                                    ? number_format($fileSize / 1048576, 1) . ' MB'
                                                                                                    : number_format($fileSize / 1024, 0) . ' KB';
                                                                                            }
                                                                                        }
                                                                                        $displayName = \Illuminate\Support\Str::limit($file->file_name, 24);
                                                                                    @endphp
                                                                                    <div class="flex flex-wrap items-center gap-2">
                                                                                        <a href="{{ route('development-objectives.preview-file', $file->id) }}"
                                                                                           target="_blank"
                                                                                           rel="noopener noreferrer"
                                                                                           class="text-sm font-semibold text-orange-600 file-name"
                                                                                           title="{{ $file->file_name }}">
                                                                                            {{ $displayName }}
                                                                                        </a>
                                                                                        @if($fileSizeLabel)
                                                                                            <span class="text-xs text-gray-500">
                                                                                                {{ $fileSizeLabel }}
                                                                                            </span>
                                                                                        @endif
                                                                                    </div>
                                                                                    @if($file->verification_status === 'rejected' && $file->rejection_reason)
                                                                                        <div class="text-xs text-red-600">
                                                                                            Reason: {{ $file->rejection_reason }}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                                                @if($file->verification_status === 'pending')
                                                                                    <span class="file-badge">Pending Verification</span>
                                                                                @elseif($file->verification_status === 'approved')
                                                                                    <span class="file-badge">Approved</span>
                                                                                @elseif($file->verification_status === 'rejected')
                                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Disapproved</span>
                                                                                @endif

                                                                                @if($file->verification_status !== 'approved')
                                                                                    <form method="POST" action="{{ route('development-objectives.delete-file', $objective->id) }}"
                                                                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                                                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" title="Delete file">
                                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                            </svg>
                                                                                        </button>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <div class="text-xs text-gray-500">No files uploaded yet.</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <p class="text-gray-500">No development objectives found. Add your first objective from the Add Objective page.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-6 pt-2 right-column-sticky">
                        <div class="card border-l-4 border-orange-500">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 text-orange-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                        </svg>
                                        <h2 class="text-lg font-semibold text-orange-600">Objectives Summary</h2>
                                    </div>
                                    <button id="openIdapModal" class="btn-primary text-white px-3 py-1.5 rounded text-sm hover:bg-orange-600 transition">
                                        View IDAP
                                    </button>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Objectives</span>
                                    <span class="text-lg font-semibold text-orange-600">{{ $totalObjectives }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Pending</span>
                                    <span class="text-lg font-semibold text-orange-600">{{ $pendingObjectives }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Completed</span>
                                    <span class="text-lg font-semibold text-orange-600">{{ $completedObjectives }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">In Progress</span>
                                    <span class="text-lg font-semibold text-orange-600">{{ $inProgressObjectives }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card border-l-4 border-orange-500">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center gap-2 text-orange-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16M6 17V9m6 8V5m6 12v-3" />
                                    </svg>
                                    <h2 class="text-lg font-semibold text-orange-600">Your Progress</h2>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="text-center text-3xl font-semibold text-orange-600 mb-4">
                                    {{ $progressPercent }}%
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-orange-500" style="width: {{ min($progressPercent, 100) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Based on completed objectives
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IDAP Modal -->
                @php
                    $availableYears = $availableYears ?? collect();
                    $displayYear = $selectedYear ?? now()->format('Y');
                @endphp
                <div id="idapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4 idap-modal-container">
                    <div class="bg-white rounded-lg w-full max-h-[95vh] overflow-y-auto" style="max-width: 1400px;">
                        <!-- Modal Header -->
                        <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between" style="z-index: 10; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            <h2 class="text-xxl font-bold text-gray-800">Individual Development and Action Plan (IDAP)</h2>
                            <div class="flex items-center gap-3">
                                <label for="idapYearSelect" class="text-xs text-gray-500">Year</label>
                                <select id="idapYearSelect" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                    @if($availableYears->isEmpty())
                                        <option value="{{ now()->format('Y') }}">{{ now()->format('Y') }}</option>
                                    @else
                                        @foreach($availableYears as $year)
                                            <option value="{{ $year }}" {{ (string) $displayYear === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Modal Body - Document in Landscape -->
                        <div id="idapDocument">
                            <!-- First Page -->
                            <div class="a4-page">
                                <!-- Document Reference Number - Top Right -->
                                <div class="page-header-ref text-right" style="font-family: Arial; font-size: 12px; font-style: italic; color: #999; margin-bottom: 4px;">
                                    HRDO-QF-26
                                </div>

                                <div class="page-content-body">

                                <!-- Header Section with Logos -->
                                <div class="flex items-center justify-center mb-6 pb-4" style="border-bottom: 2px solid #666; gap: 2px; display: flex; align-items: flex-start; justify-content: center;">
                                    <!-- CVSU Logo -->
                                    <div style="width: 110px; text-align: center; flex-shrink: 0; padding-top: 0;">
                                        @if(file_exists(public_path('images/cvsu-logo.png')))
                                            <img src="{{ asset('images/cvsu-logo.png') }}" alt="CVSU Logo" style="width: 110px; height: 110px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                        @else
                                            <svg viewBox="0 0 100 100" style="width: 110px; height: 110px; display: block; margin: 0 auto; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                <!-- CVSU Diamond Logo with Torch -->
                                                <defs>
                                                    <linearGradient id="cvsuGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" style="stop-color:#4a7c59;stop-opacity:1" />
                                                        <stop offset="100%" style="stop-color:#2d5016;stop-opacity:1" />
                                                    </linearGradient>
                                                </defs>
                                                <!-- Diamond shape -->
                                                <polygon points="50,5 95,50 50,95 5,50" fill="url(#cvsuGrad)" stroke="#1a3a0a" stroke-width="2"/>
                                                <!-- Inner lighter diamond -->
                                                <polygon points="50,15 85,50 50,85 15,50" fill="#a8d5a8" stroke="none"/>
                                                <!-- Torch/Flame symbol -->
                                                <circle cx="50" cy="45" r="8" fill="#f5b041"/>
                                                <path d="M 50 30 Q 45 35 45 42 Q 45 50 50 55 Q 55 50 55 42 Q 55 35 50 30" fill="#ff8c00"/>
                                                <circle cx="50" cy="38" r="5" fill="#ffd700"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Center Header Text -->
                                    <div style="text-align: center; padding: 0;">
                                        <p style="font-family: Arial; font-size: 12px; color: #000000; margin: 0;">Republic of the Philippines</p>
                                        <h1 style="font-family: Bookman Old Style; font-size: 17px; font-weight: bold; color: #000000; margin: 2px 0;">CAVITE STATE UNIVERSITY</h1>
                                        <p style="font-family: Arial; font-size: 12px; font-weight: bold; color: #000000; margin: 2px 0; line-height: 1;">Don Severino de las Alas Campus</p>
                                        <p style="font-family: Arial; font-size: 12px; color: #000000; margin: 1px 0; line-height: 1;">Indang, Cavite</p>
                                        <p style="font-family: Arial; font-size: 12px; color: #000000; margin: 0; line-height: 1;">(046) 483-9250</p>
                                        <a href="https://www.cvsu.edu.ph" style="font-family: Arial; font-size: 12px; font-style: italic; color: #2563eb; text-decoration: underline; margin: 0; display: block; line-height: 1;">www.cvsu.edu.ph</a>
                                        <p style="font-family: Arial; font-size: 15px; font-weight: bold; color: #000000; margin-top: 30px;">HUMAN RESOURCE DEVELOPMENT OFFICE</p>
                                    </div>

                                    <!-- Bagong Pilipinas Logo -->
                                    <div style="width: 110px; text-align: center; flex-shrink: 0; padding-top: 0;">
                                        @if(file_exists(public_path('images/bagong-pilipinas-logo.png')))
                                            <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas Logo" style="width: 110px; height: 110px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                        @else
                                            <svg viewBox="0 0 100 100" style="width: 110px; height: 110px; display: block; margin: 0 auto; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                <!-- Bagong Pilipinas Logo (simplified star with colors) -->
                                                <defs>
                                                    <linearGradient id="bpGradTop" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" style="stop-color:#ffd700;stop-opacity:1" />
                                                        <stop offset="100%" style="stop-color:#ffed4e;stop-opacity:1" />
                                                    </linearGradient>
                                                    <linearGradient id="bpGradBot" x1="0%" y1="0%" x2="100%" y2="100%">
                                                        <stop offset="0%" style="stop-color:#1f4788;stop-opacity:1" />
                                                        <stop offset="50%" style="stop-color:#c41e3a;stop-opacity:1" />
                                                        <stop offset="100%" style="stop-color:#ffd700;stop-opacity:1" />
                                                    </linearGradient>
                                                </defs>
                                                <!-- Yellow sun/star top -->
                                                <circle cx="50" cy="35" r="22" fill="url(#bpGradTop)"/>
                                                <!-- Philippines shape approximation (curved shape) -->
                                                <path d="M 35 50 Q 30 60 35 75 Q 50 85 65 75 Q 70 60 65 50 Z" fill="url(#bpGradBot)"/>
                                                <!-- Center accent -->
                                                <circle cx="50" cy="65" r="8" fill="#ffd700"/>
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Document Title -->
                                <h2 style="font-family: Arial; text-align: center; font-weight: bold; color: #000; margin: 8px 0; font-size: 15px; letter-spacing: 0.5px;">INDIVIDUAL DEVELOPMENT AND ACTION PLAN</h2>
                                <div style="text-align: center; margin-bottom: 12px; font-size: 11px;">
                                    <span style="font-weight: bold; color: #000;">{{ $displayYear }}</span>
                                </div>

                                <!-- Employee Information Section -->
                                <div style="margin-bottom: 14px; font-size: 10px; line-height: 1.3;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                                        <div style="flex: 0 0 48%;">
                                            <span style="font-family: Arial; font-size: 13px; font-weight: bold; color: #000;">Name:</span>
                                            <span class="idap-field-span" style="font-family: Arial; font-size: 13px; border-bottom: 1px solid #000; display: inline-block; width: calc(100% - 190px); text-align: left; color: #000; padding: 0px 0; margin-left: 2px;">{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->middle_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</span>
                                        </div>
                                        <div style="flex: 0 0 48%;">
                                            <span style="font-family: Arial; font-size: 13px;  font-weight: bold; color: #000;">College/Campus/Office/Unit:</span>
                                            <span class="idap-field-span" style="font-family: Arial; font-size: 13px; border-bottom: 1px solid #000; display: inline-block; width: calc(100% - 290px); text-align: left; color: #000; padding: 1px 0;">{{ auth()->user()->department ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        @php
                                            $regularizedAt = auth()->user()->regularized_at ?? null;
                                            $yearsInPosition = $regularizedAt
                                                ? (int) \Carbon\Carbon::parse($regularizedAt)->diffInYears(now())
                                                : null;
                                        @endphp
                                        <div style="flex: 0 0 48%;">
                                            <span style="font-family: Arial; font-size: 13px; font-weight: bold; color: #000;">Position:</span>
                                            <span class="idap-field-span" style="font-family: Arial; font-size: 13px; border-bottom: 1px solid #000; display: inline-block; width: calc(100% - 200px); text-align: left; color: #000; padding: 1px 0; ">{{ ucfirst(auth()->user()->role ?? 'N/A') }}@if(auth()->user()->academic_rank) - {{ auth()->user()->academic_rank }}@endif</span>
                                        </div>
                                        <div style="flex: 0 0 48%;">
                                            <span style="font-family: Arial; font-size: 13px; font-weight: bold; color: #000;">Years in Position:</span>
                                            <span class="idap-field-span" style="font-family: Arial; font-size: 13px; border-bottom: 1px solid #000; display: inline-block; width: calc(100% - 220px); text-align: left; color: #000; padding: 0px 0;">{{ $yearsInPosition !== null ? $yearsInPosition . ' years' : '' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Objectives Table -->
                                @php
                                    $allObjectives = ($idapObjectives ?? $objectives)->toArray();
                                    $objectivesPerPage = 3;
                                    $paginatedObjectives = array_chunk($allObjectives, $objectivesPerPage);
                                    if (empty($paginatedObjectives)) {
                                        $paginatedObjectives = [[]];
                                    }
                                @endphp

                                <!-- First Page Table -->
                                @if(isset($paginatedObjectives[0]) || count($paginatedObjectives) > 0)
                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; border: 1px solid #000;">
                                        <thead>
                                            <tr style="background-color: #fff;">
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; width: 18%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">DEVELOPMENT OBJECTIVES / TARGET - L&D PLAN</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 18%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">ACTION PLAN AND STRATEGIES</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 11%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">BUDGET REQUIREMENT</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;" colspan="4">TARGET PERIOD</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 15%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">SUPPORT REQUIRED</th>
                                            </tr>
                                            <tr style="background-color: #f9f9f9;">
                                                <th colspan="3" style="border: 1px solid #000; padding: 10px 3px;"></th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q1</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q2</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q3</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q4</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paginatedObjectives[0] as $objective)
                                                <tr>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #000; word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $objective['objective'] }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #000; word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $objective['action_plan'] }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #000;">
                                                        @if(!empty($objective['budget_requirement']))
                                                            {{ number_format($objective['budget_requirement'], 2) }}
                                                        @else
                                                            __________
                                                        @endif
                                                    </td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q1' ? '☑' : '☐' }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q2' ? '☑' : '☐' }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q3' ? '☑' : '☐' }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q4' ? '☑' : '☐' }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #000; word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                                                        @if(!empty($objective['support_required']))
                                                            {{ $objective['support_required'] }}
                                                        @else
                                                            __________
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif

                                <!-- Signature Section - Only on first page if it's the only page -->
                                @if(count($paginatedObjectives) == 1)
                                    <div style="page-break-inside: avoid; display: flex; justify-content: space-between; margin-top: 32px; font-size: 9px; gap: 40px;">
                                        <div style="flex: 1; text-align: left;">
                                            <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 8px 0;">Prepared by:</p>
                                            <div style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 4px 0; width: 70%;">{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->middle_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</div>
                                            <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
                                            <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Employee</p>
                                        </div>
                                        <div style="flex: 1; text-align: left;">
                                            <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 8px 0;">Reviewed by:</p>
                                            <div style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 4px 0; width: 70%;">{{ $departmentChair ? $departmentChair->first_name . ' ' . $departmentChair->last_name : '' }}</div>
                                            <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
                                            <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Department Chair / Immediate Supervisor</p>
                                        </div>
                                        <div style="flex: 1; text-align: left;">
                                            <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 8px 0;">Approved by:</p>
                                            <div style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 4px 0; width: 70%;">Willie</div>
                                            <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
                                            <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Dean/Director/Unit Head</p>
                                        </div>
                                    </div>
                                @endif

                                </div><!-- close page-content-body -->

                                <!-- Footer -->
                                <div class="page-footer-ref" style="font-family: Arial; font-size: 12px; text-align: right; margin-top: 20px; color: #999;">
                                    V02-2025-10-27
                                </div>
                            </div><!-- Close first page -->

                            @for($pageIndex = 1; $pageIndex < count($paginatedObjectives); $pageIndex++)
                                @php
                                    $pageObjectives = $paginatedObjectives[$pageIndex];
                                @endphp
                                
                                <!-- Start page {{ $pageIndex + 1 }} -->
                                <div class="a4-page">
                                    <!-- Document Reference Number - Top Right -->
                                    <div class="page-header-ref text-right" style="font-family: Arial; font-size: 12px; font-style: italic; color: #999; margin-bottom: 4px;">
                                        HRDO-QF-26
                                    </div>
                                    
                                    <div class="page-content-body">
                                    
                                    <!-- Repeat Header on Subsequent Pages -->
                                    <div class="flex items-center justify-center mb-6 pb-4" style="border-bottom: 2px solid #666; gap: 2px; display: flex; align-items: flex-start; justify-content: center;">
                                            <!-- CVSU Logo -->
                                            <div style="width: 110px; text-align: center; flex-shrink: 0; padding-top: 0;">
                                                @if(file_exists(public_path('images/cvsu-logo.png')))
                                                    <img src="{{ asset('images/cvsu-logo.png') }}" alt="CVSU Logo" style="width: 110px; height: 110px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                @else
                                                    <svg viewBox="0 0 100 100" style="width: 110px; height: 110px; display: block; margin: 0 auto; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                        <defs>
                                                            <linearGradient id="cvsuGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                                                                <stop offset="0%" style="stop-color:#4a7c59;stop-opacity:1" />
                                                                <stop offset="100%" style="stop-color:#2d5016;stop-opacity:1" />
                                                            </linearGradient>
                                                        </defs>
                                                        <polygon points="50,5 95,50 50,95 5,50" fill="url(#cvsuGrad2)" stroke="#1a3a0a" stroke-width="2"/>
                                                        <polygon points="50,15 85,50 50,85 15,50" fill="#a8d5a8" stroke="none"/>
                                                        <circle cx="50" cy="45" r="8" fill="#f5b041"/>
                                                        <path d="M 50 30 Q 45 35 45 42 Q 45 50 50 55 Q 55 50 55 42 Q 55 35 50 30" fill="#ff8c00"/>
                                                        <circle cx="50" cy="38" r="5" fill="#ffd700"/>line
                                                    </svg>
                                                @endif
                                            </div>

                                            <!-- Center Header Text -->
                                            <div style="text-align: center; padding: 0;">
                                                <p style="font-family: Arial; font-size: 12px; color: #000000; margin: 0;">Republic of the Philippines</p>
                                                <h1 style="font-family: Bookman Old Style; font-size: 17px; font-weight: bold; color: #000000; margin: 2px 0;">CAVITE STATE UNIVERSITY</h1>
                                                <p style="font-family: Arial; font-size: 12px; font-weight: bold; color: #000000; margin: 2px 0; line-height: 1;">Don Severino de las Alas Campus</p>
                                                <p style="font-family: Arial; font-size: 12px; color: #000000; margin: 1px 0; line-height: 1;">Indang, Cavite</p>
                                                <p style="font-family: Arial; font-size: 12px; color: #000000; margin: 0; line-height: 1;">(046) 483-9250</p>
                                                <a href="https://www.cvsu.edu.ph" style="font-family: Arial; font-size: 12px; font-style: italic; color: #2563eb; text-decoration: underline; margin: 0; display: block; line-height: 1;">www.cvsu.edu.ph</a>
                                                <p style="font-family: Arial; font-size: 15px; font-weight: bold; color: #000000; margin-top: 30px;">HUMAN RESOURCE DEVELOPMENT OFFICE</p>
                                            </div>

                                            <!-- Bagong Pilipinas Logo -->
                                            <div style="width: 110px; text-align: center; flex-shrink: 0; padding-top: 0;">
                                                @if(file_exists(public_path('images/bagong-pilipinas-logo.png')))
                                                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}" alt="Bagong Pilipinas Logo" style="width: 110px; height: 110px; object-fit: contain; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                @else
                                                    <svg viewBox="0 0 100 100" style="width: 110px; height: 110px; display: block; margin: 0 auto; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                        <defs>
                                                            <linearGradient id="bpGradTop2" x1="0%" y1="0%" x2="100%" y2="100%">
                                                                <stop offset="0%" style="stop-color:#ffd700;stop-opacity:1" />
                                                                <stop offset="100%" style="stop-color:#ffed4e;stop-opacity:1" />
                                                            </linearGradient>
                                                            <linearGradient id="bpGradBot2" x1="0%" y1="0%" x2="100%" y2="100%">
                                                                <stop offset="0%" style="stop-color:#1f4788;stop-opacity:1" />
                                                                <stop offset="50%" style="stop-color:#c41e3a;stop-opacity:1" />
                                                                <stop offset="100%" style="stop-color:#ffd700;stop-opacity:1" />
                                                            </linearGradient>
                                                        </defs>
                                                        <circle cx="50" cy="35" r="22" fill="url(#bpGradTop2)"/>
                                                        <path d="M 35 50 Q 30 60 35 75 Q 50 85 65 75 Q 70 60 65 50 Z" fill="url(#bpGradBot2)"/>
                                                        <circle cx="50" cy="65" r="8" fill="#ffd700"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Document Title -->
                                        <h2 style="font-family: Arial; text-align: center; font-weight: bold; color: #000; margin: 8px 0; font-size: 15px; letter-spacing: 0.5px;">INDIVIDUAL DEVELOPMENT AND ACTION PLAN</h2>
                                        <div style="text-align: center; margin-bottom: 12px; font-size: 11px;">
                                            <span style="font-family: Arial; font-weight: bold; color: #000;">{{ $displayYear }}</span>
                                        </div>
                                    
                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; border: 1px solid #000;">
                                        <thead>
                                            <tr style="background-color: #fff;">
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; width: 18%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">DEVELOPMENT OBJECTIVES / TARGET - L&D PLAN</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 18%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">ACTION PLAN AND STRATEGIES</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 11%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">BUDGET REQUIREMENT</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;" colspan="4">TARGET PERIOD</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 10px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 15%; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">SUPPORT REQUIRED</th>
                                            </tr>
                                            <tr style="background-color: #f9f9f9;">
                                                <th colspan="3" style="border: 1px solid #000; padding: 10px 3px;"></th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q1</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q2</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q3</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q4</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 10px 3px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($pageObjectives) > 0)
                                                @foreach($pageObjectives as $objective)
                                                    <tr>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #000; word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $objective['objective'] }}</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #000; word-break: break-word; overflow-wrap: break-word; white-space: normal;">{{ $objective['action_plan'] }}</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #000;">
                                                            @if(!empty($objective['budget_requirement']))
                                                                {{ number_format($objective['budget_requirement'], 2) }}
                                                            @else
                                                                __________
                                                            @endif
                                                        </td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q1' ? '☑' : '☐' }}</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q2' ? '☑' : '☐' }}</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q3' ? '☑' : '☐' }}</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">{{ ($objective['target_period'] ?? '') === 'Q4' ? '☑' : '☐' }}</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #000; word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                                                            @if(!empty($objective['support_required']))
                                                                {{ $objective['support_required'] }}
                                                            @else
                                                                __________
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @for($i = 1; $i <= 3; $i++)
                                                    <tr>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; color: #000;">{{ $i }}. ___________________________</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; color: #000;">___________________________</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #000;">__________</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #000;">__________</td>
                                                    </tr>
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                    <!-- Signature Section - Only on last page -->
                                    @if($pageIndex == count($paginatedObjectives) - 1)
                                        <div style="page-break-inside: avoid; display: flex; justify-content: space-between; margin-top: 32px; font-size: 9px; gap: 40px;">
                                            <div style="flex: 1; text-align: left;">
                                                <p style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 8px 0;">Prepared by:</p>
                                                <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">{{ auth()->user()->first_name ?? '' }} {{ auth()->user()->middle_name ?? '' }} {{ auth()->user()->last_name ?? '' }}</div>
                                                <div style="border-top: 1px solid #000; padding-top: 2px; width: 70%;"></div>
                                                <p style="font-family: Arial; font-size: 15px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Employee</p>
                                            </div>
                                            <div style="flex: 1; text-align: left;">
                                                <p style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 8px 0;">Reviewed by:</p>
                                                <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">{{ $departmentChair ? $departmentChair->first_name . ' ' . $departmentChair->last_name : '' }}</div>
                                                <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
                                                <p style="font-family: Arial; font-size: 15px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Department Chair / Immediate Supervisor</p>
                                            </div>
                                            <div style="flex: 1; text-align: left;">
                                                <p style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 8px 0;">Approved by:</p>
                                                <div style="font-family: Arial; font-size: 15px; color: #000; margin: 0 0 4px 0; width: 70%;">Willie</div>
                                                <div style="border-top: 1px solid #000; padding-top: 1px; width: 70%;"></div>
                                                <p style="font-family: Arial; font-size: 15px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Dean/Director/Unit Head</p>
                                            </div>
                                        </div>
                                        
                                    @endif
                                    </div><!-- close page-content-body -->

                                    <!-- Footer -->
                                    <div class="page-footer-ref" style="font-family: Arial; font-size: 12px; text-align: right; margin-top: 20px; color: #999;">
                                        V02-2025-10-27
                                    </div>
                                </div><!-- Close page {{ $pageIndex + 1 }} -->
                            @endfor

                            @if(count($paginatedObjectives) == 0)
                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; border: 1px solid #000;">
                                        <thead>
                                            <tr style="background-color: #fff;">
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 8px 3px; text-align: center; vertical-align: top; width: 18%; color: #000; font-size: 13px; font-weight: normal;">DEVELOPMENT OBJECTIVES / TARGET</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 8px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 18%; color: #000; font-size: 13px; font-weight: normal;">ACTION PLAN AND STRATEGIES</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 8px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 11%; color: #000; font-size: 13px; font-weight: normal;">BUDGET REQUIREMENT</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; vertical-align: top; font-weight: bold; color: #000; font-size: 13px; font-weight: normal;" colspan="4">TARGET PERIOD</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; vertical-align: top; font-weight: bold; width: 15%; color: #000; font-size: 13px; font-weight: normal;">SUPPORT REQUIRED</th>
                                            </tr>
                                            <tr style="background-color: #f9f9f9;">
                                                <th colspan="3" style="border: 1px solid #000; padding: 4px 3px;"></th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q1</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q2</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q3</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q4</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for($i = 1; $i <= 3; $i++)
                                                <tr>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; color: #000;">{{ $i }}. ___________________________</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; color: #000;">___________________________</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #000;">__________</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #000;">__________</td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div><!-- Close first page for empty state -->
                            @endif

                        </div><!-- Close idapDocument -->

                        <!-- Action Buttons -->
                        <div class="bg-white border-t border-gray-200 p-4 flex items-center justify-center gap-3 sticky bottom-0">
                            <button id="printIdapButton" class="btn-primary text-white px-6 py-2 rounded hover:bg-orange-600 transition flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </button>
                            <button id="closeIdapModal2" class="btn-danger text-white px-6 py-2 rounded hover:bg-red-700 transition flex items-center gap-2" style="background-color: #6b7280;">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Close
                            </button>
                        </div>
                    </div>
                </div>
                <div id="editFormModal" class="fixed inset-0 z-50 flex items-center justify-center hidden" aria-modal="true" role="dialog">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditForm()"></div>
                    <!-- Dialog panel -->
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto animate-modal">
                        <!-- Close button -->
                        <button type="button" onclick="closeEditForm()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="p-8">
                            <!-- Header -->
                            <div class="flex items-center gap-3 mb-6">
                                <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-gray-800">Edit L&amp;D Form</h2>
                                    <p class="text-xs text-gray-500 mt-0.5">Update your learning &amp; development details</p>
                                </div>
                            </div>
                            <!-- Form -->
                            <form id="editLndForm" method="POST" action="#">
                                @csrf
                                <input type="hidden" id="edit_objective_id" name="objective_id" value="">
                                <div class="space-y-4">
                                    <div>
                                        <label for="lnd_type" class="block text-sm font-semibold text-gray-700 mb-1.5">Type of L&amp;D</label>
                                        <input
                                            type="text"
                                            id="lnd_type"
                                            name="lnd_type"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-orange-50 text-gray-700 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400"
                                            placeholder="Enter type of L&D (e.g., training, short courses)..."
                                        >
                                    </div>
                                    <div>
                                        <label for="lnd_title" class="block text-sm font-semibold text-gray-700 mb-1.5">Title</label>
                                        <input
                                            type="text"
                                            id="lnd_title"
                                            name="lnd_title"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-orange-50 text-gray-700 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400"
                                            placeholder="Enter the title of the activity..."
                                        >
                                    </div>
                                    <div>
                                        <label for="lnd_period_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Period Date</label>
                                        <input
                                            type="text"
                                            id="lnd_period_date"
                                            name="lnd_period_date"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-orange-50 text-gray-700 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400"
                                            placeholder="e.g., January - March 2026"
                                        >
                                    </div>
                                    <div>
                                        <label for="lnd_hours" class="block text-sm font-semibold text-gray-700 mb-1.5">Hours/Units Completed</label>
                                        <input
                                            type="number"
                                            id="lnd_hours"
                                            name="lnd_hours"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-orange-50 text-gray-700 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400"
                                            placeholder="Enter hours or units"
                                            min="0"
                                            step="0.5"
                                        >
                                    </div>
                                    <div>
                                        <label for="lnd_proof_completion" class="block text-sm font-semibold text-gray-700 mb-1.5">Proof of Completion</label>
                                        <input
                                            type="text"
                                            id="lnd_proof_completion"
                                            name="lnd_proof_completion"
                                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-orange-50 text-gray-700 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400"
                                            placeholder="Enter proof of completion details..."
                                        >
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    onclick="saveEditForm(event)"
                                    class="mt-6 w-full py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm transition shadow-sm"
                                >
                                    Save Changes
                                </button>
                                <button
                                    type="button"
                                    onclick="closeEditForm()"
                                    class="mt-2 w-full py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition font-medium"
                                >
                                    Cancel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateTime() {
            var now = new Date();
            var h = now.getHours();
            var ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            var m = now.getMinutes().toString().padStart(2,'0');
            var s = now.getSeconds().toString().padStart(2,'0');
            document.getElementById('live-time').textContent = h+':'+m+':'+s+' '+ampm;
        }
        updateTime(); setInterval(updateTime, 1000);
    </script>
</body>
</html>

<script>
function updateMaxFiles(objectiveId, maxFiles) {
    if (!maxFiles) return;

    if (confirm(`Are you sure you want to set the maximum file limit to ${maxFiles} files for this objective?`)) {
        fetch(`/development-objectives/${objectiveId}/update-max-files`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ max_files: maxFiles })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error updating file limit: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating file limit');
        });
    } else {
        const select = document.getElementById(`max_files_${objectiveId}`);
        if (select) {
            select.value = '';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const alertPopup = document.getElementById('alert-popup');
    if (!alertPopup) {
        return;
    }

    setTimeout(() => {
        alertPopup.classList.add('alert-hidden');
    }, 2000);
});

document.addEventListener('DOMContentLoaded', () => {
    const fileInputs = document.querySelectorAll('input[type="file"][data-filename-target]');

    fileInputs.forEach((input) => {
        input.addEventListener('change', (event) => {
            const targetId = event.currentTarget.getAttribute('data-filename-target');
            const target = targetId ? document.getElementById(targetId) : null;
            if (!target) {
                return;
            }

            const file = event.currentTarget.files && event.currentTarget.files[0];
            target.textContent = file ? file.name : 'No file selected';
        });
    });
});

function openEditForm(objectiveId) {
    const modal = document.getElementById('editFormModal');
    const objectiveInput = document.getElementById('edit_objective_id');

    if (objectiveInput) {
        objectiveInput.value = objectiveId || '';
    }

    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeEditForm() {
    const modal = document.getElementById('editFormModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function saveEditForm(event) {
    event.preventDefault();

    const objectiveId = document.getElementById('edit_objective_id').value;
    const lndType = document.getElementById('lnd_type').value;
    const lndTitle = document.getElementById('lnd_title').value;
    const lndPeriodDate = document.getElementById('lnd_period_date').value;
    const lndHours = document.getElementById('lnd_hours').value;
    const lndProofCompletion = document.getElementById('lnd_proof_completion').value;

    if (!lndType || !lndTitle || !lndPeriodDate || !lndHours || !lndProofCompletion) {
        alert('Please fill in all fields');
        return;
    }

    // Submit to backend
    fetch(`/development-objectives/${objectiveId}/lnd-data`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            lnd_type: lndType,
            lnd_title: lndTitle,
            lnd_period_date: lndPeriodDate,
            lnd_hours: lndHours,
            lnd_proof_completion: lndProofCompletion
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('L&D data saved successfully! The data will appear in the Summary of L&D page.');
            closeEditForm();
            document.getElementById('editLndForm').reset();
            // Optionally reload the page to show updated data
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the data.');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const editFormModal = document.getElementById('editFormModal');

    if (!editFormModal) {
        return;
    }

    editFormModal.addEventListener('click', (event) => {
        if (event.target === editFormModal) {
            closeEditForm();
        }
    });
});

// IDAP Modal functionality
document.addEventListener('DOMContentLoaded', () => {
    const idapModal = document.getElementById('idapModal');
    const openIdapModal = document.getElementById('openIdapModal');
    const closeIdapModal2 = document.getElementById('closeIdapModal2');
    const printIdapButton = document.getElementById('printIdapButton');
    const idapYearSelect = document.getElementById('idapYearSelect');

    if (!idapModal || !openIdapModal) return;

    // Open modal
    openIdapModal.addEventListener('click', () => {
        idapModal.classList.remove('hidden');
    });

    const queryParams = new URLSearchParams(window.location.search);
    if (queryParams.get('openIdap') === '1') {
        idapModal.classList.remove('hidden');
    }

    if (idapYearSelect) {
        idapYearSelect.addEventListener('change', () => {
            const params = new URLSearchParams(window.location.search);
            params.set('year', idapYearSelect.value);
            params.set('openIdap', '1');
            window.location = `{{ route('development-objectives.list') }}?${params.toString()}`;
        });
    }

    // Close modal
    const closeModal = () => {
        idapModal.classList.add('hidden');
    };

    closeIdapModal2.addEventListener('click', closeModal);

    // Close modal when clicking outside
    idapModal.addEventListener('click', (event) => {
        if (event.target === idapModal) {
            closeModal();
        }
    });

    // Download functionality - render each .a4-page as its own PDF page
    printIdapButton.addEventListener('click', async () => {
        const docEl = document.getElementById('idapDocument');
        const yearVal = document.getElementById('idapYearSelect') ? document.getElementById('idapYearSelect').value : 'IDAP';

        printIdapButton.disabled = true;
        printIdapButton.textContent = 'Generating...';

        // Temporarily remove modal overflow clipping
        const modalScroller = document.querySelector('#idapModal > div');
        const savedOverflow  = modalScroller ? modalScroller.style.overflow  : '';
        const savedMaxHeight = modalScroller ? modalScroller.style.maxHeight : '';
        if (modalScroller) {
            modalScroller.style.overflow  = 'visible';
            modalScroller.style.maxHeight = 'none';
        }

        const CAPTURE_WIDTH = 1122;

        try {
            const pages = Array.from(docEl.querySelectorAll('.a4-page'));
            if (pages.length === 0) return;
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ unit: 'mm', format: 'a4', orientation: 'landscape' });
            const pdfW = pdf.internal.pageSize.getWidth();
            const pdfH = pdf.internal.pageSize.getHeight();

            for (let i = 0; i < pages.length; i++) {
                if (i > 0) pdf.addPage();

                // Force A4-landscape width so capture matches the real form width
                const prevStyle = pages[i].getAttribute('style') || '';
                pages[i].style.width    = CAPTURE_WIDTH + 'px';
                pages[i].style.minWidth = CAPTURE_WIDTH + 'px';
                pages[i].style.maxWidth = CAPTURE_WIDTH + 'px';

                // Force Arial 13px on table cells — overrides the table-level font-size: 9px
                // so html2canvas renders them at the intended 13px, matching header/signature text
                const allCells = pages[i].querySelectorAll('table td, table th');
                const prevFontFamily = Array.from(allCells).map(c => c.style.fontFamily);
                const prevFontSize   = Array.from(allCells).map(c => c.style.fontSize);
                allCells.forEach(c => { c.style.fontFamily = 'Arial'; c.style.fontSize = '13px'; });

                // Patch field underline spans so border-bottom appears below text in PDF
                const fieldSpans = pages[i].querySelectorAll('.idap-field-span');
                const prevFieldPadding = Array.from(fieldSpans).map(s => s.style.paddingBottom);
                fieldSpans.forEach(s => { s.style.paddingBottom = '6px'; });

                pages[i].scrollIntoView({ block: 'start' });
                await new Promise(r => setTimeout(r, 150));

                const canvas = await window.html2canvas(pages[i], {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth:  CAPTURE_WIDTH,
                    width:        CAPTURE_WIDTH,
                    imageTimeout: 0,
                    removeContainer: true
                });

                pages[i].setAttribute('style', prevStyle);

                // Restore table cell fonts
                allCells.forEach((c, j) => { c.style.fontFamily = prevFontFamily[j]; c.style.fontSize = prevFontSize[j]; });

                // Restore field span padding
                fieldSpans.forEach((s, j) => { s.style.paddingBottom = prevFieldPadding[j]; });

                // Fit proportionally — scale to fill width; if taller than page, scale down to fit height
                // (prevents signature section from being clipped when content exceeds A4 landscape height)
                const imgH_raw = (canvas.height / canvas.width) * pdfW;
                let imgW = pdfW;
                let imgH = imgH_raw;
                if (imgH > pdfH) {
                    const scaleDown = pdfH / imgH;
                    imgW = pdfW * scaleDown;
                    imgH = pdfH;
                }
                pdf.addImage(canvas.toDataURL('image/jpeg', 0.98), 'JPEG', (pdfW - imgW) / 2, 0, imgW, imgH);
            }

            pdf.save(`IDAP_${yearVal}.pdf`);
        } catch (err) {
            alert('Error generating PDF: ' + err.message);
        } finally {
            if (modalScroller) {
                modalScroller.style.overflow  = savedOverflow;
                modalScroller.style.maxHeight = savedMaxHeight;
            }
            printIdapButton.disabled = false;
            printIdapButton.innerHTML = '<svg class="h-4 w-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>Download';
        }
    });
});
</script>


