<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Verification - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        .sidebar {
            background-color: #ff6b35;
        }
        .sidebar-item:hover {
            background-color: #e55a2b;
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
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('chairperson.sidebar')
        <div class="sidebar w-64 min-h-screen text-white fixed left-0 top-0">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-6">Chairperson Panel</h2>
                <nav>
                    <a href="{{ route('chairperson.dashboard') }}" class="sidebar-item block px-4 py-3 rounded mb-2 transition">
                        Dashboard
                    </a>
                    <a href="{{ route('chairperson.faculty-members') }}" class="sidebar-item block px-4 py-3 rounded mb-2 transition">
                        Faculty Members
                    </a>
                    <a href="{{ route('chairperson.file-verification') }}" class="sidebar-item block px-4 py-3 rounded mb-2 transition bg-orange-600">
                        File Verification
                    </a>
                    <a href="{{ route('chairperson.department-reports') }}" class="sidebar-item block px-4 py-3 rounded mb-2 transition">
                        Department Reports
                    </a>
                    <form method="POST" action="{{ route('chairperson.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="sidebar-item block w-full text-left px-4 py-3 rounded mb-2 transition">
                            Logout
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">File Verification</h1>
                            <p class="text-gray-600 mt-2">Review and verify uploaded files from faculty members</p>
                        </div>
                        <a href="{{ route('chairperson.department-reports') }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            ← Back to Department Reports
                        </a>
                    </div>
                </div>

                <!-- Pending Files Table -->
                <div class="card">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">Pending Files</h2>
                            <div class="text-sm text-gray-500">
                                Total: {{ $pendingFiles->total() }} files
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($pendingFiles->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Faculty Member</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Objective</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">File Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Uploaded</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingFiles as $file)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                            <span class="text-xs font-medium text-gray-600">
                                                                {{ strtoupper(substr($file->developmentObjective->user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $file->developmentObjective->user->name }}</p>
                                                            <p class="text-sm text-gray-500">{{ $file->developmentObjective->user->department }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <p class="text-gray-900">{{ \Illuminate\Support\Str::limit($file->developmentObjective->objective, 50) }}</p>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <p class="text-gray-600 text-sm">{{ $file->file_name }}</p>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <p class="text-gray-600 text-sm">{{ $file->created_at->format('M d, Y H:i') }}</p>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <div class="flex flex-col space-y-2">
                                                        <div class="flex space-x-2">
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                               target="_blank"
                                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                                View
                                                            </a>
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                               download="{{ $file->file_name }}"
                                                               class="text-green-600 hover:text-green-800 font-medium text-sm">
                                                                Download
                                                            </a>
                                                        </div>
                                                        <div class="flex space-x-2">
                                                            <form method="POST" action="{{ route('chairperson.file-verification.approve', $file->id) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200 shadow-sm hover:shadow-md"
                                                                        onclick="return confirmApprove({{ $file->id }}, '{{ $file->developmentObjective->user->name }}', '{{ $file->file_name }}')">
                                                                    ✓ Approve
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('chairperson.file-verification.reject', $file->id) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" 
                                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200 shadow-sm hover:shadow-md"
                                                                        onclick="return promptReject({{ $file->id }}, '{{ $file->developmentObjective->user->name }}', '{{ $file->file_name }}')">
                                                                    ✗ Reject
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6 flex justify-center">
                                {{ $pendingFiles->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Files</h3>
                                <p class="text-gray-600">All files have been reviewed and verified.</p>
                            </div>
                        @endif
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
