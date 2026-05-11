<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chairperson Dashboard - L&D Plan</title>
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

        .header-bar {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        :root {
            --page-header-height: 84px;
            --page-header-gap: 6px;
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
                            <p class="text-gray-600 text-base">Chairperson / <span class="text-orange-600 font-semibold">Dashboard</span></p>
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

                <div class="px-5">

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">

                    <!-- Total Faculty Members -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-1" style="background: #ff6b35;"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="h-5 w-5" style="color: #ff6b35;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H7a4 4 0 01-4-4v-1a4 4 0 014-4h10a4 4 0 014 4v1a4 4 0 01-4 4zM12 7a4 4 0 100-8 4 4 0 000 8z" />
                                </svg>
                                <p class="text-sm font-medium" style="color: #ff6b35;">Total Faculty Members</p>
                            </div>
                            <div class="text-4xl font-bold mb-2" style="color: #ff6b35;">{{ $totalFaculty }}</div>
                            <p class="text-gray-500 text-sm">{{ $totalFaculty === 0 ? 'No faculty yet' : ($totalFaculty === 1 ? '1 faculty member' : $totalFaculty . ' faculty members') }}</p>
                        </div>
                    </div>

                    <!-- Faculty Completed -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-1 bg-green-500"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium text-green-500">Faculty Completed</p>
                            </div>
                            <div class="text-4xl font-bold text-green-500 mb-2">{{ $facultyWithCompletedObjectives }}<span class="text-2xl text-gray-400">/{{ $facultyWithAnyObjectives }}</span></div>
                            <p class="text-gray-500 text-sm">Completed all objectives</p>
                        </div>
                    </div>

                    <!-- Active Objectives -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-1 bg-blue-500"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <p class="text-sm font-medium text-blue-500">Active Objectives</p>
                            </div>
                            <div class="text-4xl font-bold text-blue-500 mb-2">{{ $activeObjectives }}</div>
                            <p class="text-gray-500 text-sm">Currently in progress</p>
                        </div>
                    </div>

                    <!-- Completed Objectives -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-1 bg-purple-500"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <p class="text-sm font-medium text-purple-500">Completed Objectives</p>
                            </div>
                            <div class="text-4xl font-bold text-purple-500 mb-2">{{ $completedObjectives }}</div>
                            <p class="text-gray-500 text-sm">Finished goals</p>
                        </div>
                    </div>

                </div>

                <!-- Faculty Completion Progress & Quick Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow overflow-hidden h-full">
                            <div class="h-1" style="background: #ff6b35;"></div>
                            <div class="p-6 border-b border-gray-100">
                                <h2 class="text-xl font-semibold text-gray-800">Faculty Completion Progress</h2>
                                <p class="text-sm text-gray-500 mt-1">{{ $facultyWithCompletedObjectives }} out of {{ $facultyWithAnyObjectives }} faculty members have completed all their objectives</p>
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

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                    <div class="text-center p-4 bg-green-50 rounded-lg">
                                        <p class="text-2xl font-bold text-green-600">{{ $facultyWithCompletedObjectives }}</p>
                                        <p class="text-sm text-gray-600">Completed All Objectives</p>
                                    </div>
                                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                                        <p class="text-2xl font-bold text-orange-600">{{ $facultyWithAnyObjectives - $facultyWithCompletedObjectives }}</p>
                                        <p class="text-sm text-gray-600">In Progress</p>
                                    </div>
                                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                                        <p class="text-2xl font-bold text-gray-500">{{ $totalFaculty - $facultyWithAnyObjectives }}</p>
                                        <p class="text-sm text-gray-600">No Objectives Yet</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="h-1 bg-blue-500"></div>
                        <div class="p-6 border-b border-gray-100">
                            <h2 class="text-xl font-semibold text-gray-800">Quick Stats</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            @php
                                $totalObjectives = $activeObjectives + $completedObjectives;
                                $inProgressCount = max(0, ($activeObjectives ?? 0) - ($completedObjectives ?? 0));
                                $notStartedCount = ($totalFaculty - $facultyWithAnyObjectives) ?? 0;
                            @endphp
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total Objectives</span>
                                <span class="text-2xl font-bold text-gray-800">{{ $totalObjectives }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Completed</span>
                                <span class="text-2xl font-bold text-green-600">{{ $completedObjectives }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">In Progress</span>
                                <span class="text-2xl font-bold text-blue-600">{{ $inProgressCount }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Not Started</span>
                                <span class="text-2xl font-bold text-gray-500">{{ $notStartedCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                </div>{{-- end px-5 --}}
            </div>
        </div>
    </div>
</body>
</html>
