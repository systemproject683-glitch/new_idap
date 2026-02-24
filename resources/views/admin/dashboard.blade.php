<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .card-orange-shadow {
            box-shadow: 0 4px 8px rgba(255, 107, 53, 0.2);
            border-bottom: 1px solid #ff6b35;
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
        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .stat-card {
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(255, 107, 53, 0.3) !important;
        }
        .a4-page {
            box-sizing: border-box;
            width: 100%;
            max-width: 1056px;
            height: auto;
            margin: 0 auto 30px auto;
            background-color: white;
            padding: 96px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            page-break-after: always;
            break-after: always;
            display: flex;
            flex-direction: column;
        }
        .page-content-body {
            flex: 1;
        }
        .page-footer-ref {
            margin-top: auto;
        }
        .a4-page:last-child {
            margin-bottom: 0;
            page-break-after: auto;
            break-after: auto;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('admin.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8 page-content">
            <!-- Header -->
            <div class="header-bar page-header-fixed">
                <h1 class="text-2xl font-bold text-gray-800 mt-0">Admin Dashboard</h1>
                <p class="text-gray-600 mt-1 mb-0 leading-tight">Welcome, {{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}!</p>
            </div>
            <div class="page-header-spacer"></div>
            <div class="px-5">

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="card card-orange-shadow p-6 stat-card">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Users</h3>
                    <p class="text-3xl font-bold text-orange-500">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="card card-orange-shadow p-6 stat-card">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Sessions</h3>
                    <p class="text-3xl font-bold text-green-500">{{ \DB::table('sessions')->count() }}</p>
                </div>
                <div class="card card-orange-shadow p-6 stat-card">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Development Objectives</h3>
                    <p class="text-3xl font-bold text-blue-500">{{ $totalDevelopmentObjectives }}</p>
                </div>
            </div>

            <!-- Recent Activity and Department Distribution -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Recent Activity Card -->
                <div class="card card-orange-shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        @if($recentActivities->count() > 0)
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start">
                                    <div class="w-3 h-3 rounded-full bg-orange-500 mt-2 mr-4 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-gray-800 text-sm">
                                            <span class="font-semibold">{{ $activity['user'] }}</span> 
                                            {{ $activity['action'] }}
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            {{ $activity['time']->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No recent activities</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Department Distribution Card -->
                <div class="card card-orange-shadow p-6">
                    <div class="flex items-center justify-between gap-2 mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Department Distribution</h2>
                        <button id="openFacultyPlanModal" class="btn-primary text-white px-3 py-1.5 rounded text-sm hover:bg-orange-600 transition">
                            View Faculty Plan
                        </button>
                    </div>
                    <div style="position: relative; height: 300px;">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            </div>
            </div>
        </div>
    </div>

    <!-- Faculty and Staff Development Plan Modal -->
    <div id="facultyPlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-h-[95vh] overflow-y-auto" style="max-width: 1400px;">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex items-center justify-between" style="z-index: 10; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                <h2 class="font-family: Arial; text-align: center; font-weight: bold; color: #000; margin: 8px 0; font-size: 13px; letter-spacing: 0.5px;">Faculty and Staff Development and Action Plan</h2>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label for="facultyPlanYearSelect" class="text-xs text-gray-500">Year</label>
                        <select id="facultyPlanYearSelect" class="border border-gray-300 rounded px-2 py-1 text-xs">
                            @if(($facultyPlanAvailableYears ?? collect())->count() > 0)
                                @foreach($facultyPlanAvailableYears as $year)
                                    <option value="{{ $year }}" {{ (int) ($facultyPlanSelectedYear ?? 0) === (int) $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            @else
                                <option value="{{ now()->format('Y') }}" selected>{{ now()->format('Y') }}</option>
                            @endif
                        </select>
                    </div>
                    <button id="closeFacultyPlanModal" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-8" style="background-color: #f5f5f5;">
                <div id="facultyPlanDocument" style="width: 100%; display: flex; flex-direction: column; gap: 0;">
                    <!-- A4 Page -->
                    <div class="a4-page">
                        <div class="page-content-body">
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
                                        <p style="font-family: Arial; font-size: 14px; font-weight: bold; color: #000000; margin-top: 30px;">HUMAN RESOURCE DEVELOPMENT OFFICE</p>
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

                                @php
                                    $facultyPlanDisplayYear = $facultyPlanSelectedYear ?? now()->format('Y');
                                    $allObjectives = ($facultyPlanObjectives ?? collect())->toArray();
                                    $objectivesPerPage = 3;
                                    $paginatedObjectives = array_chunk($allObjectives, $objectivesPerPage);
                                @endphp

                                <!-- Document Title -->
                                <h2 style="font-family: Arial; text-align: center; font-weight: bold; color: #000; margin: 8px 0; font-size: 13px; letter-spacing: 0.5px;">FACULTY AND STAFF DEVELOPMENT AND ACTION PLAN</h2>
                                <div style="text-align: center; margin-bottom: 12px; font-size: 11px;">
                                    <span style="font-weight: bold; color: #000;">{{ $facultyPlanDisplayYear }}</span>
                                </div>

                                <!-- First Page Table -->
                                @if(isset($paginatedObjectives[0]))
                                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; border: 1px solid #000;">
                                        <thead>
                                            <tr style="background-color: #fff;">
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">DEVELOPMENT OBJECTIVES / TARGET</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">ACTION PLAN AND STRATEGIES</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">NAME OF EMPLOYEES</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">BUDGET REQUIREMENT</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;" colspan="4">TARGET PERIOD</th>
                                                <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">SUPPORT REQUIRED</th>
                                            </tr>
                                            <tr style="background-color: #f9f9f9;">
                                                <th colspan="3" style="border: 1px solid #000; padding: 4px 3px;"></th>
                                                <th style="border: 1px solid #000; padding: 4px 3px;"></th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q1</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q2</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q3</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q4</th>
                                                <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($paginatedObjectives[0] as $index => $objective)
                                                <tr>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">{{ $index + 1 }}. {{ $objective['objective'] ?? '' }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">{{ $objective['action_plan'] ?? '' }}</td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">
                                                        @php
                                                            $user = \App\Models\User::find($objective['user_id']);
                                                        @endphp
                                                        {{ $user->name ?? '' }}
                                                    </td>
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #333;">
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
                                                    <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">
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
                                            <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 35px 0;">Prepared by:</p>
                                            <div style="border-top: 1px solid #000; padding-top: 0px; width: 70%;"></div>
                                            <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Dean/Director/Unit Head</p>
                                        </div>
                                        <div style="flex: 1; text-align: left;">
                                            <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 35px 0;">Approved by:</p>
                                            <div style="border-top: 1px solid #000; padding-top: 0px; width: 70%;"></div>
                                            <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Vice President</p>
                                        </div>
                                    </div>
                                @endif

                            </div><!-- close page-content-body -->

                            <!-- Footer -->
                            <div class="page-footer-ref" style="font-family: Arial; font-size: 12px; text-align: right; margin-top: 20px; color: #999;">
                                V02-2025-10-27
                            </div>
                        </div>
                    </div><!-- Close first page -->

                    @for($pageIndex = 1; $pageIndex < count($paginatedObjectives); $pageIndex++)
                        @php
                            $pageObjectives = $paginatedObjectives[$pageIndex];
                            $startIndex = $pageIndex * $objectivesPerPage;
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
                            <h2 style="font-family: Arial; text-align: center; font-weight: bold; color: #000; margin: 8px 0; font-size: 13px; letter-spacing: 0.5px;">FACULTY AND STAFF DEVELOPMENT AND ACTION PLAN</h2>
                            <div style="text-align: center; margin-bottom: 12px; font-size: 11px;">
                                <span style="font-weight: bold; color: #000;">{{ $facultyPlanDisplayYear }}</span>
                            </div>
                        
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; border: 1px solid #000;">
                            <thead>
                                <tr style="background-color: #fff;">
                                    <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">DEVELOPMENT OBJECTIVES / TARGET</th>
                                    <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">ACTION PLAN AND STRATEGIES</th>
                                    <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">NAME OF EMPLOYEES</th>
                                    <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">BUDGET REQUIREMENT</th>
                                    <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;" colspan="4">TARGET PERIOD</th>
                                    <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal; line-height: 1.1;">SUPPORT REQUIRED</th>
                                </tr>
                                <tr style="background-color: #f9f9f9;">
                                    <th colspan="3" style="border: 1px solid #000; padding: 4px 3px;"></th>
                                    <th style="border: 1px solid #000; padding: 4px 3px;"></th>
                                    <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q1</th>
                                    <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q2</th>
                                    <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q3</th>
                                    <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px; text-align: center; font-weight: bold; color: #000; width: 6%;">Q4</th>
                                    <th style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 4px 3px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pageObjectives as $index => $objective)
                                    <tr>
                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">{{ $startIndex + $index + 1 }}. {{ $objective['objective'] ?? '' }}</td>
                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">{{ $objective['action_plan'] ?? '' }}</td>
                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">
                                            @php
                                                $user = \App\Models\User::find($objective['user_id']);
                                            @endphp
                                            {{ $user->name ?? '' }}
                                        </td>
                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #333;">
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
                                        <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: left; color: #333;">
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
                        
                        <!-- Signature Section - Only on last page -->
                        @if($pageIndex == count($paginatedObjectives) - 1)
                            <div style="page-break-inside: avoid; display: flex; justify-content: space-between; margin-top: 32px; font-size: 9px; gap: 40px;">
                                <div style="flex: 1; text-align: left;">
                                    <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 35px 0;">Prepared by:</p>
                                    <div style="border-top: 1px solid #000; padding-top: 0px; width: 70%;"></div>
                                    <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Dean/Director/Unit Head</p>
                                </div>
                                <div style="flex: 1; text-align: left;">
                                    <p style="font-family: Arial; font-size: 13px; color: #000; margin: 0 0 35px 0;">Approved by:</p>
                                    <div style="border-top: 1px solid #000; padding-top: 0px; width: 70%;"></div>
                                    <p style="font-family: Arial; font-size: 13px; font-weight: normal; color: #000; margin: 0px 0 0 0;">Vice President</p>
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
                        <div class="a4-page">
                            <div class="page-content-body">
                                <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9px; border: 1px solid #000;">
                                    <thead>
                                        <tr style="background-color: #fff;">
                                            <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal;">DEVELOPMENT OBJECTIVES / TARGET</th>
                                            <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal;">ACTION PLAN AND STRATEGIES</th>
                                            <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal;">NAME OF EMPLOYEES</th>
                                            <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal;">BUDGET REQUIREMENT</th>
                                            <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal;" colspan="4">TARGET PERIOD</th>
                                            <th style="font-family: Arial; border: 1px solid #000; padding: 4px 3px; text-align: center; color: #000; font-size: 13px; font-weight: normal;">SUPPORT REQUIRED</th>
                                        </tr>
                                        <tr style="background-color: #f9f9f9;">
                                            <th colspan="3" style="border: 1px solid #000; padding: 4px 3px;"></th>
                                            <th style="border: 1px solid #000; padding: 4px 3px;"></th>
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
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; color: #333;">{{ $i }}. ___________________________</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; color: #333;">___________________________</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #333;">__________</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #333;">__________</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center;">☐</td>
                                                <td style="font-family: Arial; font-size: 13px; border: 1px solid #000; padding: 8px 4px; text-align: center; color: #333;">__________</td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- Close empty state page -->
                    @endif

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="border-t border-gray-200 p-4 bg-white flex items-center justify-center gap-3 sticky bottom-0" style="z-index: 10; box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);">
                <button id="closeFacultyPlanModalBtn" class="text-white px-6 py-2 rounded hover:bg-gray-600 transition flex items-center gap-2" style="background-color: #6b7280;">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Close
                </button>
                <button id="printFacultyPlanBtn" class="btn-primary text-white px-6 py-2 rounded hover:bg-orange-600 transition flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4H9a2 2 0 00-2 2v2a2 2 0 002 2h6a2 2 0 002-2v-2a2 2 0 00-2-2zm-6-4a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                    Print
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Department Distribution Chart
        const departmentData = @json($departmentData);
        const ctx = document.getElementById('departmentChart').getContext('2d');
        
        const departments = departmentData.map(d => d.name);
        const usersData = departmentData.map(d => d.users);
        const objectivesData = departmentData.map(d => d.objectives);
        const completedData = departmentData.map(d => d.completed);
        
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: departments,
                datasets: [
                    {
                        label: 'Users',
                        data: usersData,
                        backgroundColor: '#3b82f6',
                        borderColor: '#1e40af',
                        borderWidth: 1
                    },
                    {
                        label: 'Development Objectives',
                        data: objectivesData,
                        backgroundColor: '#f97316',
                        borderColor: '#c2410c',
                        borderWidth: 1
                    },
                    {
                        label: 'Completed',
                        data: completedData,
                        backgroundColor: '#10b981',
                        borderColor: '#047857',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

    <script>
        // Faculty Plan Modal
        const facultyPlanModal = document.getElementById('facultyPlanModal');
        const openFacultyPlanModal = document.getElementById('openFacultyPlanModal');
        const closeFacultyPlanModal = document.getElementById('closeFacultyPlanModal');
        const closeFacultyPlanModalBtn = document.getElementById('closeFacultyPlanModalBtn');
        const printFacultyPlanBtn = document.getElementById('printFacultyPlanBtn');
        const facultyPlanYearSelect = document.getElementById('facultyPlanYearSelect');

        if (openFacultyPlanModal) {
            openFacultyPlanModal.addEventListener('click', () => {
                facultyPlanModal.classList.remove('hidden');
            });
        }

        const facultyPlanParams = new URLSearchParams(window.location.search);
        if (facultyPlanParams.get('openFacultyPlan') === '1') {
            facultyPlanModal.classList.remove('hidden');
        }

        const closeFacultyModal = () => {
            facultyPlanModal.classList.add('hidden');
        };

        if (closeFacultyPlanModal) {
            closeFacultyPlanModal.addEventListener('click', closeFacultyModal);
        }

        if (closeFacultyPlanModalBtn) {
            closeFacultyPlanModalBtn.addEventListener('click', closeFacultyModal);
        }

        facultyPlanModal.addEventListener('click', (event) => {
            if (event.target === facultyPlanModal) {
                closeFacultyModal();
            }
        });

        if (facultyPlanYearSelect) {
            facultyPlanYearSelect.addEventListener('change', (event) => {
                const nextYear = event.target.value;
                const nextParams = new URLSearchParams(window.location.search);
                nextParams.set('fpYear', nextYear);
                nextParams.set('openFacultyPlan', '1');
                window.location.assign(`${window.location.pathname}?${nextParams.toString()}`);
            });
        }

        if (printFacultyPlanBtn) {
            printFacultyPlanBtn.addEventListener('click', () => {
                const documentTarget = document.getElementById('facultyPlanDocument');
                if (!documentTarget) {
                    return;
                }

                const documentContent = documentTarget.innerHTML;
                const printWindow = window.open('', '_blank', 'width=1100,height=800');

                if (!printWindow) {
                    return;
                }

                printWindow.document.write(`<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty and Staff Development and Action Plan</title>
    <script src="https://cdn.tailwindcss.com"><\/script>
    <style>
        @page {
            size: landscape;
            margin: 1in 1in 0.3in 1in;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: white;
            font-family: Arial, sans-serif;
        }

        /* A4 Page Container Styles - screen view */
        .a4-page {
            box-sizing: border-box;
            width: 100%;
            max-width: 1056px;
            margin: 0 auto 30px auto;
            background-color: white;
            padding: 96px;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .page-content-body {
            flex: 1;
        }

        .page-footer-ref {
            margin-top: auto;
        }

        .a4-page:last-child {
            margin-bottom: 0;
        }

        /* Print-specific overrides */
        @media print {
            body {
                background-color: white;
            }

            .a4-page {
                margin: 0;
                padding: 0;
                max-width: 100%;
                width: 100%;
                min-height: 100vh;
                box-shadow: none;
                page-break-after: always;
                break-after: page;
            }

            .a4-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            table {
                page-break-inside: avoid;
                break-inside: avoid;
                width: 100%;
            }

            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    ${documentContent}
</body>
</html>`);

                printWindow.document.close();

                printWindow.onload = () => {
                    setTimeout(() => {
                        printWindow.print();
                    }, 500);
                };
            });
        }
    </script>
</body>
</html>
