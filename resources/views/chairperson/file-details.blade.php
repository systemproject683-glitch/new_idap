<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Details - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .btn-success {
            background-color: #10b981;
        }
        .btn-success:hover {
            background-color: #059669;
        }
        .btn-danger {
            background-color: #ef4444;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
        .header-bar {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        :root {
            --page-header-height: 84px;
            --page-header-gap: 16px;
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
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
         @include('chairperson.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8 page-content">
                <!-- Header -->
                <div class="header-bar page-header-fixed">
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">Chairperson / <span class="text-orange-600 font-semibold">File Verification</span></p>
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

                <!-- File Information -->
                <div class="card mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">File Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Faculty Member</h3>
                                <p class="text-gray-900">{{ $file->developmentObjective->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $file->developmentObjective->user->email }}</p>
                                <p class="text-sm text-gray-600">{{ $file->developmentObjective->user->department }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">File Details</h3>
                                <p class="text-gray-900">{{ $file->file_name }}</p>
                                <p class="text-sm text-gray-600">Uploaded: {{ $file->created_at->format('M d, Y H:i') }}</p>
                                <p class="text-sm text-gray-600">Size: {{ number_format(Storage::disk('public')->size($file->file_path) / 1024, 2) }} KB</p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Development Objective</h3>
                            <p class="text-gray-900">{{ $file->developmentObjective->objective }}</p>
                            <p class="text-sm text-gray-600 mt-2">{{ $file->developmentObjective->action_plan }}</p>
                            <p class="text-sm text-gray-600 mt-2">
                                Hours: {{ $file->developmentObjective->number_of_hours !== null ? $file->developmentObjective->number_of_hours . ' hrs' : 'N/A' }}
                            </p>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">File Progress</h3>
                            <div class="flex items-center space-x-4">
                                <p class="text-sm text-gray-600">
                                    Uploaded Files: {{ $file->developmentObjective->files()->count() }} / {{ $file->developmentObjective->max_files }}
                                </p>
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-blue-500" 
                                         style="width: {{ ($file->developmentObjective->files()->count() / $file->developmentObjective->max_files) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Preview -->
                <div class="card mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">File Preview</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-900 font-medium">{{ $file->file_name }}</p>
                                <a href="{{ asset('storage/' . $file->file_path) }}" 
                                   download="{{ $file->file_name }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 mt-4">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Actions -->
                <div class="card">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Verification Actions</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('chairperson.file-verification.approve', $file->id) }}" class="mb-4">
                            @csrf
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Approve File</h3>
                                    <p class="text-sm text-gray-600 mt-1">This file meets requirements and will be counted toward completion.</p>
                                    <p class="text-xs text-gray-500 mt-2">Faculty member: {{ $file->developmentObjective->user->name }}</p>
                                    <p class="text-xs text-gray-500">Current progress: {{ $file->developmentObjective->files()->count() }}/{{ $file->developmentObjective->max_files }} files</p>
                                </div>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200 shadow-sm hover:shadow-md" onclick="return confirmApprove({{ $file->id }}, '{{ $file->developmentObjective->user->name }}', '{{ $file->file_name }}')">
                                    ✓ Approve File
                                </button>
                            </div>
                        </form>
                        
                        <form method="POST" action="{{ route('chairperson.file-verification.reject', $file->id) }}">
                            @csrf
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject File</h3>
                                <div class="mb-4">
                                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                        Rejection Reason
                                    </label>
                                    <textarea name="rejection_reason" id="rejection_reason" rows="3" 
                                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Please provide a reason for rejection..." required></textarea>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600">The file will be rejected and faculty member will need to upload a replacement.</p>
                                    <p class="text-xs text-gray-500 mt-2">Faculty member: {{ $file->developmentObjective->user->name }}</p>
                                    <p class="text-xs text-gray-500">Current progress: {{ $file->developmentObjective->files()->count() }}/{{ $file->developmentObjective->max_files }} files</p>
                                </div>
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-md font-medium transition-colors duration-200 shadow-sm hover:shadow-md" onclick="return confirmReject({{ $file->id }}, '{{ $file->developmentObjective->user->name }}', '{{ $file->file_name }}')">
                                    ✗ Reject File
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmApprove(fileId, facultyName, fileName) {
            if (confirm('Are you sure you want to approve this file?\n\nFaculty: ' + facultyName + '\nFile: ' + fileName + '\n\nThis action will count the file toward the faculty member\'s completion percentage.')) {
                return true;
            }
            return false;
        }
        
        function confirmReject(fileId, facultyName, fileName) {
            if (confirm('Are you sure you want to reject this file?\n\nFaculty: ' + facultyName + '\nFile: ' + fileName + '\n\nThe faculty member will need to upload a replacement file and their completion percentage will be adjusted accordingly.')) {
                return true;
            }
            return false;
        }
    </script>
</body>
</html>
