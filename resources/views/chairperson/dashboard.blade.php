<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chairperson Dashboard - IDAP System</title>
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
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
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
                    <h1 class="text-3xl font-bold text-gray-800">Chairperson Dashboard</h1>
                    <p class="text-gray-600 mt-2">Manage and monitor faculty members in your department</p>
                </div>

                <!-- Department Info -->
                <div class="card mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->department }} Department</h2>
                                <p class="text-gray-600">Overview of your department's faculty members and objectives</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Welcome back,</p>
                                <p class="text-lg font-medium text-gray-800">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm mb-1">Total Faculty Members</p>
                                <p class="text-3xl font-bold">{{ $totalFaculty }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm mb-1">Faculty Completed</p>
                                <p class="text-3xl font-bold">{{ $facultyWithCompletedObjectives }}/{{ $facultyWithAnyObjectives }}</p>
                                <p class="text-white/80 text-xs mt-1">{{ round($facultyCompletionRate) }}% Complete</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm mb-1">Active Objectives</p>
                                <p class="text-3xl font-bold">{{ $activeObjectives }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-2M9 7a1 1 0 012 0v6a1 1 0 11-2 0V7z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm mb-1">Completed Objectives</p>
                                <p class="text-3xl font-bold">{{ $completedObjectives }}</p>
                            </div>
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Faculty Completion Progress -->
                <div class="card mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Faculty Completion Progress</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $facultyWithCompletedObjectives }} out of {{ $facultyWithAnyObjectives }} faculty members have completed all their objectives</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">Department Completion Rate</span>
                                    <span class="text-sm font-bold text-gray-900">{{ round($facultyCompletionRate) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div class="h-4 rounded-full transition-all duration-500
                                        @if($facultyCompletionRate >= 75) bg-green-500
                                        @elseif($facultyCompletionRate >= 50) bg-yellow-500
                                        @elseif($facultyCompletionRate >= 25) bg-orange-500
                                        @else bg-red-500
                                        @endif"
                                         style="width: {{ min($facultyCompletionRate, 100) }}%">
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                                    <span>0%</span>
                                    <span>25%</span>
                                    <span>50%</span>
                                    <span>75%</span>
                                    <span>100%</span>
                                </div>
                            </div>
                        </div>
                        
                        @if($facultyWithAnyObjectives > 0)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-green-600">{{ $facultyWithCompletedObjectives }}</p>
                                    <p class="text-sm text-gray-600">Completed All Objectives</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-orange-600">{{ $facultyWithAnyObjectives - $facultyWithCompletedObjectives }}</p>
                                    <p class="text-sm text-gray-600">In Progress</p>
                                </div>
                                <div class="text-center p-4 bg-gray-50 rounded-lg">
                                    <p class="text-2xl font-bold text-gray-600">{{ $totalFaculty - $facultyWithAnyObjectives }}</p>
                                    <p class="text-sm text-gray-600">No Objectives Yet</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('chairperson.faculty-members') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800">View Faculty Members</h3>
                                        <p class="text-sm text-gray-600">See all faculty members in your department</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('chairperson.department-reports') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800">Department Reports</h3>
                                        <p class="text-sm text-gray-600">View department performance reports</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
