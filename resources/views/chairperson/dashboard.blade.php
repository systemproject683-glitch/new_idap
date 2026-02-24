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
        .card-orange-shadow {
            box-shadow: 0 4px 8px rgba(255, 107, 53, 0.2);
            border-bottom: 1px solid #ff6b35;
        }
        .stat-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(255, 107, 53, 0.3) !important;
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
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 mt-0">{{ Auth::user()->department }} Department</h1>
                            <p class="text-gray-600 mt-1 mb-0 leading-tight">Overview of your department's faculty members and objectives</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Welcome back,</p>
                            <p class="text-lg font-medium text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="page-header-spacer"></div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="card card-orange-shadow p-6 stat-card">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Faculty Members</h3>
                        <p class="text-3xl font-bold text-orange-500">{{ $totalFaculty }}</p>
                    </div>

                    <div class="card card-orange-shadow p-6 stat-card">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Faculty Completed</h3>
                        <p class="text-3xl font-bold text-green-500">{{ $facultyWithCompletedObjectives }}/{{ $facultyWithAnyObjectives }}</p>
                    </div>

                    <div class="card card-orange-shadow p-6 stat-card">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Objectives</h3>
                        <p class="text-3xl font-bold text-blue-500">{{ $activeObjectives }}</p>
                    </div>

                    <div class="card card-orange-shadow p-6 stat-card">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Completed Objectives</h3>
                        <p class="text-3xl font-bold text-purple-500">{{ $completedObjectives }}</p>
                    </div>
                </div>

                <!-- Faculty Completion Progress & Quick Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="lg:col-span-2">
                        <div class="card h-full">
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
                    </div>

                    <!-- Quick Stats -->
                    <div class="card">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Quick Stats</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Objectives</span>
                                <span class="text-2xl font-bold text-gray-800">@php
                                    $totalObjectives = $activeObjectives + $completedObjectives;
                                @endphp
                                {{ $totalObjectives }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Completed</span>
                                <span class="text-2xl font-bold text-green-600">{{ $completedObjectives }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">In Progress</span>
                                <span class="text-2xl font-bold text-blue-600">@php
                                    $inProgressCount = ($activeObjectives ?? 0) - ($completedObjectives ?? 0);
                                    $inProgressCount = max(0, $inProgressCount);
                                @endphp
                                {{ $inProgressCount }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Not Started</span>
                                <span class="text-2xl font-bold text-gray-600">@php
                                    $notStartedCount = ($totalFaculty - $facultyWithAnyObjectives) ?? 0;
                                @endphp
                                {{ $notStartedCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
