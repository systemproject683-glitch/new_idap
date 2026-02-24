<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Reports - IDAP System</title>
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
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-in_progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .file-item {
            transition: all 0.2s ease;
        }
        .file-item:hover {
            background-color: #f9fafb;
            transform: translateX(2px);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('chairperson.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Department Reports</h1>
                    <p class="text-gray-600 mt-2">View all faculty members and their uploaded files for development objectives</p>
                </div>

                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="card p-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Faculty</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalFaculty }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #e8eaf6;">
                            <svg class="w-6 h-6" style="color: #5c6bc0;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card p-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Files Uploaded</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalFiles }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #e8f5e9;">
                            <svg class="w-6 h-6" style="color: #66bb6a;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="card p-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Pending Verification</p>
                            <p class="text-2xl font-bold {{ $pendingVerification > 0 ? 'text-orange-500' : 'text-gray-800' }}">{{ $pendingVerification }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #fff8e1;">
                            <svg class="w-6 h-6" style="color: #ffa726;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 2v6l2 2-2 2v6h12v-6l-2-2 2-2V2H6zm10 14.5V20H8v-3.5l2-2 2 2 2-2 2 2zM16 9.5l-2 2-2-2-2 2V4h8v5.5l-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Faculty Members with Files -->
                @if($facultyMembers->count() > 0)
                    @foreach($facultyMembers as $faculty)
                        <div class="card mb-6">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                            <span class="text-lg font-bold text-gray-600">
                                                {{ strtoupper(substr($faculty->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $faculty->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $faculty->email }} • {{ $faculty->department }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $totalObjectives = $faculty->developmentObjectives->count();
                                                $completedObjectives = $faculty->developmentObjectives->where('status', 'completed')->count();
                                                $totalFiles = $faculty->developmentObjectives->sum(function($objective) {
                                                    return $objective->files->count();
                                                });
                                                $pendingFiles = $faculty->developmentObjectives->sum(function($objective) {
                                                    return $objective->files->where('verification_status', 'pending')->count();
                                                });
                                            @endphp
                                            <span class="text-sm text-gray-500">
                                                {{ $completedObjectives }}/{{ $totalObjectives }} objectives completed
                                            </span>
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $totalFiles }} files uploaded
                                            </span>
                                            @if($pendingFiles > 0)
                                                <span class="text-sm text-gray-500">•</span>
                                                <span class="text-sm text-orange-600 font-medium">
                                                    {{ $pendingFiles }} pending verification
                                                </span>
                                                
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                @if($faculty->developmentObjectives->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($faculty->developmentObjectives as $objective)
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-900 mb-1">{{ $objective->objective }}</h4>
                                                        <p class="text-sm text-gray-600 mb-2">{{ $objective->action_plan }}</p>
                                                        <div class="flex items-center space-x-3">
                                                            <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                                {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                            </span>
                                                            <span class="text-xs text-gray-500">
                                                                Created: {{ $objective->created_at->format('M d, Y') }}
                                                            </span>
                                                            <span class="text-xs text-gray-500">
                                                                Required files: {{ $objective->max_files }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                @if($objective->files->count() > 0)
                                                    <div class="mt-3">
                                                        <h5 class="text-sm font-medium text-gray-700 mb-2">Uploaded Files ({{ $objective->files->count() }}/{{ $objective->max_files }})</h5>
                                                        <div class="space-y-2">
                                                            @foreach($objective->files as $file)
                                                                <div class="file-item flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                                    <div class="flex items-center">
                                                                        <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                            </svg>
                                                                        </div>
                                                                        <div>
                                                                            <p class="text-sm font-medium text-gray-900">{{ $file->file_name }}</p>
                                                                            <p class="text-xs text-gray-500">
                                                                                Uploaded: {{ $file->created_at->format('M d, Y H:i') }}
                                                                                @if(isset($file->file_size))
                                                                                    • {{ number_format($file->file_size / 1024, 2) }} KB
                                                                                @endif
                                                                                @if($file->verification_status === 'pending')
                                                                                    • <span class="text-orange-600 font-medium">Pending Verification</span>
                                                                                @elseif($file->verification_status === 'approved')
                                                                                    • <span class="text-green-600 font-medium">Approved</span>
                                                                                @elseif($file->verification_status === 'rejected')
                                                                                    • <span class="text-red-600 font-medium">Rejected</span>
                                                                                @endif
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                           target="_blank"
                                                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                                            View
                                                                        </a>
                                                                        <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                           download="{{ $file->file_name }}"
                                                                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                                            Download
                                                                        </a>
                                                                        @if($file->verification_status === 'pending')
                                                                            <form method="POST" action="{{ route('chairperson.file-verification.approve', $file->id) }}" class="inline">
                                                                                @csrf
                                                                                <button type="submit" 
                                                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200 shadow-sm hover:shadow-md"
                                                                                        onclick="return confirmApprove({{ $file->id }}, '{{ $faculty->name }}', '{{ $file->file_name }}')">
                                                                                    ✓ Approve
                                                                                </button>
                                                                            </form>
                                                                            <form method="POST" action="{{ route('chairperson.file-verification.reject', $file->id) }}" class="inline">
                                                                                @csrf
                                                                                <button type="submit" 
                                                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200 shadow-sm hover:shadow-md"
                                                                                        onclick="return promptReject({{ $file->id }}, '{{ $faculty->name }}', '{{ $file->file_name }}')">
                                                                                    ✗ Reject
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4 bg-gray-50 rounded-lg">
                                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                            </svg>
                                                        </div>
                                                        <p class="text-sm text-gray-500">No files uploaded yet</p>
                                                        <p class="text-xs text-gray-400 mt-1">{{ $objective->max_files }} files required</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2M9 7a1 1 0 012 0v6a1 1 0 11-2 0V7z"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Development Objectives</h4>
                                        <p class="text-gray-600">{{ $faculty->name }} hasn't created any development objectives yet.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Faculty Members Found</h3>
                        <p class="text-gray-600">There are no faculty members in your department yet.</p>
                    </div>
                @endif
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
        
        function promptReject(fileId, facultyName, fileName) {
            var reason = prompt('Are you sure you want to reject this file?\n\nFaculty: ' + facultyName + '\nFile: ' + fileName + '\n\nPlease provide a reason for rejection:');
            
            if (reason === null) {
                return false; // User cancelled
            }
            
            if (reason.trim() === '') {
                alert('Rejection reason is required.');
                return false;
            }
            
            // Find the form and add the rejection reason
            var form = document.querySelector('form[action*="/' + fileId + '/reject"]');
            if (form) {
                // Create hidden input for rejection reason
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'rejection_reason';
                input.value = reason;
                form.appendChild(input);
                
                return true;
            }
            
            return false;
        }
    </script>
</body>
</html>
