<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Member Details - IDAP System</title>
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
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
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
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Faculty Member Details</h1>
                            <p class="text-gray-600 mt-2">View detailed information and objectives</p>
                        </div>
                        <a href="{{ route('chairperson.faculty-members') }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            ← Back to Faculty Members
                        </a>
                    </div>
                </div>

                <!-- Faculty Member Info -->
                <div class="card mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Faculty Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-6">
                                <span class="text-xl font-bold text-gray-600">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h3>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $user->role }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                        {{ $user->department }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="card p-6">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-gray-900">{{ $totalObjectives }}</p>
                            <p class="text-sm text-gray-600 mt-1">Total Objectives</p>
                        </div>
                    </div>
                    <div class="card p-6">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-green-600">{{ $completedObjectives }}</p>
                            <p class="text-sm text-gray-600 mt-1">Completed</p>
                        </div>
                    </div>
                    <div class="card p-6">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ $inProgressObjectives }}</p>
                            <p class="text-sm text-gray-600 mt-1">In Progress</p>
                        </div>
                    </div>
                    <div class="card p-6">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-yellow-600">{{ $pendingObjectives }}</p>
                            <p class="text-sm text-gray-600 mt-1">Pending</p>
                        </div>
                    </div>
                </div>

                <!-- Completion Rate -->
                <div class="card mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Completion Rate</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                <svg class="progress-ring" width="120" height="120">
                                    <circle class="text-gray-200" stroke="currentColor" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"></circle>
                                    <circle class="progress-ring-circle" 
                                            stroke="{{ $completionRate >= 75 ? '#10b981' : ($completionRate >= 50 ? '#f59e0b' : '#ef4444') }}" 
                                            stroke-width="8" 
                                            stroke-linecap="round" 
                                            fill="transparent" 
                                            r="52" 
                                            cx="60" 
                                            cy="60"
                                            stroke-dasharray="{{ 2 * 3.14159 * 52 }}"
                                            stroke-dashoffset="{{ 2 * 3.14159 * 52 * (1 - $completionRate / 100) }}">
                                    </circle>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-900">{{ round($completionRate) }}%</p>
                                        <p class="text-xs text-gray-600">Complete</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Objectives List -->
                <div class="card">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Development Objectives</h2>
                    </div>
                    <div class="p-6">
                        @if($objectives->count() > 0)
                            <div class="space-y-4">
                                @foreach($objectives as $objective)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $objective->objective }}</h3>
                                                <p class="text-gray-600 mb-3">{{ $objective->action_plan }}</p>
                                                
                                                <div class="flex items-center gap-4">
                                                    <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        Created: {{ $objective->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2M9 7a1 1 0 012 0v6a1 1 0 11-2 0V7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Development Objectives</h3>
                                <p class="text-gray-600">This faculty member hasn't created any development objectives yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
