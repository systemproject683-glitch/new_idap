<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Reports - L&D Plan</title>
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
        @keyframes pendingBlink {
            0%   { opacity: 1;   box-shadow: 0 0 4px  2px rgba(253,112,5,0.96); }
            50%  { opacity: 0.6; box-shadow: 0 0 15px 4px rgb(230,63,2); }
            100% { opacity: 1;   box-shadow: 0 0 4px  2px rgba(249,93,15,0.8); }
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fff7ed;
            color: #ff6b35;
            animation: pendingBlink 1.5s infinite ease-in-out;
        }
        .status-in-progress {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .objective-card {
            background-color: #ffffff;
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
        .file-info { min-width: 0; }
        .file-name {
            display: block;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('chairperson.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8 page-content">
                <!-- Header -->
                <div class="header-bar page-header-fixed">
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">Chairperson / <span class="text-orange-600 font-semibold">Department Reports</span></p>
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

                <!-- Two-column layout -->
                <div class="flex gap-6">

                    <!-- Left Column: Faculty list (70%) -->
                    <div style="width: 70%; min-width: 0;">

                <!-- Faculty Members with Files -->
                @if($facultyMembers->count() > 0)
                    @foreach($facultyMembers as $faculty)
                        @php
                            $totalObjectives   = $faculty->developmentObjectives->count();
                            $completedObjectives = $faculty->developmentObjectives->where('status', 'completed')->count();
                            $totalFacultyFiles = $faculty->developmentObjectives->sum(fn($o) => $o->files->count());
                            $pendingFiles      = $faculty->developmentObjectives->sum(fn($o) => $o->files->where('verification_status', 'pending')->count());
                        @endphp
                        <div class="card mb-6">
                            <!-- Faculty Header -->
                            <div class="p-5 border-b border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                             style="background: linear-gradient(135deg, #FFAA55, #FF6622);
                                                    box-shadow: 0 2px 6px rgba(0,0,0,.20);">
                                            {{ strtoupper(substr($faculty->first_name, 0, 1)) . strtoupper(substr($faculty->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="text-base font-semibold text-gray-900">{{ $faculty->name }}</h3>
                                            <p class="text-sm text-gray-500">{{ $faculty->email }} · {{ $faculty->department }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-sm text-gray-500">
                                        <span>{{ $completedObjectives }}/{{ $totalObjectives }} completed</span>
                                        <span class="text-gray-300">|</span>
                                        <span>{{ $totalFacultyFiles }} files</span>
                                        @if($pendingFiles > 0)
                                            <span class="text-gray-300">|</span>
                                            <span class="text-orange-500 font-medium">{{ $pendingFiles }} pending</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Objectives -->
                            <div class="p-5">
                                @if($faculty->developmentObjectives->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($faculty->developmentObjectives as $objective)
                                            @php
                                                $approvedFileCount = $objective->files->where('verification_status', 'approved')->count();
                                                $fileCount         = $objective->files->count();
                                                $percentage        = $objective->max_files > 0 ? ($approvedFileCount / $objective->max_files) * 100 : 0;
                                            @endphp
                                            <div class="objective-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition relative">
                                                <!-- Title + status badge -->
                                                <div class="relative mb-1">
                                                    <h4 class="text-base font-semibold text-gray-800 pr-32">
                                                        <span class="text-[#ff6b35]">Target: </span>{{ $objective->objective }}
                                                    </h4>
                                                    <div class="absolute top-0 right-0">
                                                        <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <p class="text-gray-600 mb-3">
                                                    <span class="text-[#ff6b35]">Action Plan: </span>{{ $objective->action_plan }}
                                                </p>
                                                <hr class="mb-3">

                                                <div class="flex items-center gap-4 mb-4">
                                                    <span class="text-sm text-gray-500">Created: {{ $objective->created_at->format('M d, Y') }}</span>
                                                    <span class="text-sm text-gray-500">Hours: {{ $objective->number_of_hours !== null ? $objective->number_of_hours . ' hrs' : 'N/A' }}</span>
                                                </div>

                                                <!-- Progress Bar -->
                                                @if($objective->max_files > 0)
                                                    <div class="mb-4">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="text-sm font-medium text-gray-700">Upload File/Certificate</span>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs text-gray-500">{{ $approvedFileCount }}/{{ $objective->max_files }} approved files</span>
                                                                @if($fileCount > $approvedFileCount)
                                                                    <span class="text-xs text-orange-500">({{ $fileCount - $approvedFileCount }} pending)</span>
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
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
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
                                                @endif

                                                <!-- Uploaded Files -->
                                                <div>
                                                    <div class="flex items-center justify-between mb-2">
                                                        <p class="text-sm font-semibold text-gray-700">Uploaded Files</p>
                                                        <p class="text-xs text-gray-500">{{ $fileCount }}@if($objective->max_files > 0) / {{ $objective->max_files }}@endif</p>
                                                    </div>
                                                    @if($objective->files->count() > 0)
                                                        <div class="space-y-2">
                                                            @foreach($objective->files as $file)
                                                                <div class="file-row flex items-center justify-between gap-3">
                                                                    <div class="flex items-center gap-3 min-w-0">
                                                                        <a href="{{ route('chairperson.file-verification.preview', $file->id) }}" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded bg-orange-50 flex items-center justify-center flex-shrink-0 hover:bg-orange-100 transition" title="Preview file">
                                                                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                        </a>
                                                                        <div class="file-info min-w-0">
                                                                            <a href="{{ route('chairperson.file-verification.preview', $file->id) }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-orange-600 file-name hover:underline" title="{{ $file->file_name }}">
                                                                                {{ \Illuminate\Support\Str::limit($file->file_name, 30) }}
                                                                            </a>
                                                                            <p class="text-xs text-gray-500">Uploaded: {{ $file->created_at->format('M d, Y H:i') }}</p>
                                                                            @if($file->verification_status === 'rejected' && $file->rejection_reason)
                                                                                <p class="text-xs text-red-600">Reason: {{ $file->rejection_reason }}</p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex items-center gap-2 flex-shrink-0">
                                                                        @if($file->verification_status === 'pending')
                                                                            <span class="file-badge">Pending</span>
                                                                        @elseif($file->verification_status === 'approved')
                                                                            <span class="file-badge" style="background-color:#d1fae5;color:#065f46;">Approved</span>
                                                                        @elseif($file->verification_status === 'rejected')
                                                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Disapproved</span>
                                                                        @endif
                                                                        @if($file->verification_status === 'pending')
                                                                            <form method="POST" action="{{ route('chairperson.file-verification.approve', $file->id) }}" class="inline">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-full text-xs font-medium transition"
                                                                                        onclick="return confirmApprove({{ $file->id }}, '{{ $faculty->name }}', '{{ $file->file_name }}')">
                                                                                    Approve
                                                                                </button>
                                                                            </form>
                                                                            <form method="POST" action="{{ route('chairperson.file-verification.reject', $file->id) }}" class="inline">
                                                                                @csrf
                                                                                <button type="submit"
                                                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-xs font-medium transition"
                                                                                        onclick="return promptReject({{ $file->id }}, '{{ $faculty->name }}', '{{ $file->file_name }}')">
                                                                                    Disapprove
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-500">No files uploaded yet.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-6 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-500">No development objectives yet.</p>
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
                    </div><!-- /left column -->

                    <!-- Right Column: Stat Cards (30%) -->
                    <div style="width: 30%; flex-shrink: 0; position: sticky; top: calc(var(--page-header-height) + var(--page-header-gap)); align-self: flex-start;">
                        <div class="flex flex-col gap-4">
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
                    </div><!-- /right column -->

                </div><!-- /two-column layout -->
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
