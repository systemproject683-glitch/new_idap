<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Development Objectives - List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        
        .card {
            background-color: var(--surface-color);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-bar {
            background-color: var(--surface-color);
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        :root {
            --surface-color: #ffffff;
            --page-header-height: 84px;
            --page-header-gap: 6px;
        }

        .objective-card {
            background-color: var(--surface-color);
        }

        .page-header-fixed {
            position: fixed;
            top: 0;
            left: 256px;
            right: 0;
            z-index: 20;
            margin: 0;
            height: var(--page-header-height);
        }

        .page-content {
            padding-top: 0;
        }

        .page-header-spacer {
            height: calc(var(--page-header-height) + var(--page-header-gap));
        }

        .right-column-sticky {
            position: sticky;
            top: calc(var(--page-header-height) + var(--page-header-gap));
            align-self: flex-start;
        }

        .alert-popup {
            position: fixed;
            top: calc(var(--page-header-height) + var(--page-header-gap));
            right: 24px;
            z-index: 50;
            max-width: 420px;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .alert-hidden {
            opacity: 0;
            transform: translateY(-8px);
            pointer-events: none;
        }

        .scrollable-card-body {
            overflow-y: visible;
            flex: 1;
            max-height: none;
        }

        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .btn-success {
            background-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-warning {
            background-color: #ffc107;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .input-field {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .input-field:focus {
            border-color: #ff6b35;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fff7ed;
            color: #c2410c;
        }
        .status-in-progress {
            background-color: #ffedd5;
            color: #9a3412;
        }
        .status-completed {
            background-color: #fed7aa;
            color: #7c2d12;
        }

        .upload-dropzone {
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            background-color: #ffffff;
            padding: 18px;
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }

        .upload-dropzone:hover {
            border-color: #f97316;
            background-color: #fff7ed;
        }

        .dropzone-offset {
            margin-top: 16px;
        }

        .upload-icon {
            width: 40px;
            height: 40px;
            color: #94a3b8;
        }

        .file-row {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background-color: #ffffff;
            padding: 10px 12px;
            overflow: hidden;
        }

        .file-badge {
            background-color: #fff7ed;
            color: #c2410c;
            border-radius: 9999px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .file-info {
            min-width: 0;
        }

        .file-name {
            display: block;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
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
                    <h1 class="text-2xl font-bold text-gray-800 mt-0">Development Objectives / Target</h1>
                    <p class="text-gray-600 mt-1 mb-0 leading-tight">Review your objectives, files, and progress</p>
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
                                        <div id="objective-{{ $objective->id }}" class="objective-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $objective->objective }}</h3>
                                                <p class="text-gray-600 mb-3">{{ $objective->action_plan }}</p>

                                                <div class="flex items-center gap-4">
                                                    <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        Created: {{ $objective->created_at->format('M d, Y') }}
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
                                                                        @if($percentage >= 100) text-orange-700
                                                                        @elseif($percentage >= 75) text-orange-600
                                                                        @elseif($percentage >= 50) text-orange-500
                                                                        @else text-orange-400
                                                                        @endif">
                                                                        {{ round($percentage) }}% Complete
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                                <div class="h-2 rounded-full transition-all duration-300
                                                                    @if($percentage >= 100) bg-orange-500
                                                                    @elseif($percentage >= 75) bg-orange-400
                                                                    @elseif($percentage >= 50) bg-orange-300
                                                                    @else bg-orange-200
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
                                                                                        <a href="{{ asset('storage/' . $file->file_path) }}"
                                                                                           target="_blank"
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
                                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Rejected</span>
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
                                <div class="flex items-center gap-2 text-orange-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                    <h2 class="text-lg font-semibold text-orange-600">Objectives Summary</h2>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
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
</script>
