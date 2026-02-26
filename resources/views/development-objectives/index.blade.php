<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Development Objectives - IDAP System</title>
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
        
        .objectives-left-cell {
            height: 100%;
            vertical-align: top;
        }

        .objectives-list-card {
            display: flex;
            flex-direction: column;
            height: 100%;
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
        .custom-select {
            position: relative;
        }
        .custom-select-native {
            position: absolute;
            inset: 0;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }
        .custom-select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            background-color: #ffffff;
            text-align: left;
            cursor: pointer;
        }
        .custom-select-menu {
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 4px);
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
            padding: 6px 0;
            z-index: 30;
            max-height: 240px;
            overflow-y: auto;
            display: none;
        }
        .custom-select.open .custom-select-menu {
            display: block;
        }
        .custom-select-option {
            display: block;
            width: 100%;
            padding: 8px 16px;
            font-size: 0.95rem;
            color: #1f2937;
            text-align: left;
            background: transparent;
            cursor: pointer;
        }
        .custom-select-option:hover,
        .custom-select-option:focus {
            background-color: #fed7aa;
            color: #7c2d12;
            outline: none;
        }
        .custom-select-group {
            padding: 6px 16px 4px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6b7280;
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
                    <h1 class="text-2xl font-bold text-gray-800 mt-0">Dashboard</h1>
                    <p class="text-gray-600 mt-1 mb-0 leading-tight">Stay on top of your progress and milestones</p>
                </div>
                <div class="page-header-spacer"></div>

                @php
                    $totalObjectives = $objectives->count();
                    $pendingObjectives = $objectives->where('status', 'pending')->count();
                    $completedObjectives = $objectives->where('status', 'completed')->count();
                    $inProgressObjectives = $objectives->where('status', 'in_progress')->count();
                    $progressPercent = $totalObjectives > 0
                        ? round(($completedObjectives / $totalObjectives) * 100)
                        : 0;
                    $pendingPercent = $totalObjectives > 0 ? ($pendingObjectives / $totalObjectives) * 100 : 0;
                    $inProgressPercent = $totalObjectives > 0 ? ($inProgressObjectives / $totalObjectives) * 100 : 0;
                    $completedPercent = $totalObjectives > 0 ? ($completedObjectives / $totalObjectives) * 100 : 0;
                    $pendingPercentRounded = $totalObjectives > 0 ? round($pendingPercent) : 0;
                    $inProgressPercentRounded = $totalObjectives > 0 ? round($inProgressPercent) : 0;
                    $completedPercentRounded = $totalObjectives > 0 ? round($completedPercent) : 0;
                    $pieRadius = 60;
                    $pieCircumference = 2 * M_PI * $pieRadius;
                    $pieSegments = [];
                    $segmentOffset = 0;

                    $pieDefinition = [
                        ['label' => 'Pending', 'count' => $pendingObjectives, 'color' => '#f59e0b'],
                        ['label' => 'In Progress', 'count' => $inProgressObjectives, 'color' => '#3b82f6'],
                        ['label' => 'Completed', 'count' => $completedObjectives, 'color' => '#22c55e'],
                    ];

                    foreach ($pieDefinition as $segment) {
                        $length = $totalObjectives > 0
                            ? ($segment['count'] / $totalObjectives) * $pieCircumference
                            : 0;
                        $percent = $totalObjectives > 0
                            ? round(($segment['count'] / $totalObjectives) * 100)
                            : 0;

                        $pieSegments[] = [
                            'label' => $segment['label'],
                            'count' => $segment['count'],
                            'color' => $segment['color'],
                            'length' => $length,
                            'offset' => $segmentOffset,
                            'percent' => $percent,
                        ];
                        $segmentOffset += $length;
                    }
                @endphp

                <div class="px-5">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                        <div class="card p-5 border-l-4 border-orange-500">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="h-4 w-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                <p class="text-sm">Total Objectives</p>
                            </div>
                            <p class="text-2xl font-semibold text-gray-800 mt-2">{{ $totalObjectives }}</p>
                        </div>
                        <div class="card p-5 border-l-4 border-orange-500">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                    <circle cx="12" cy="12" r="9" stroke-width="2" />
                                </svg>
                                <p class="text-sm">Pending</p>
                            </div>
                            <p class="text-2xl font-semibold text-amber-600 mt-2">{{ $pendingObjectives }}</p>
                        </div>
                        <div class="card p-5 border-l-4 border-orange-500">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h7" />
                                </svg>
                                <p class="text-sm">In Progress</p>
                            </div>
                            <p class="text-2xl font-semibold text-blue-600 mt-2">{{ $inProgressObjectives }}</p>
                        </div>
                        <div class="card p-5 border-l-4 border-orange-500">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <p class="text-sm">Completed</p>
                            </div>
                            <p class="text-2xl font-semibold text-green-600 mt-2">{{ $completedObjectives }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                        <a href="{{ route('development-objectives.list') }}" class="card p-8 border-l-4 border-orange-500 transition hover:shadow-lg hover:scale-105 transform h-80 flex flex-col items-start group">
                            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                                    <circle cx="12" cy="12" r="5" stroke-width="2"></circle>
                                    <circle cx="12" cy="12" r="1.5" stroke-width="2" fill="currentColor"></circle>
                                </svg>
                            </div>
                            <div class="text-base font-bold text-orange-600">Development Objectives</div>
                            <p class="text-gray-600 mt-3 text-base">Jump to your current objectives list and details.</p>
                            <div class="mt-auto pt-6 text-orange-600 opacity-0 transition duration-200 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-2 text-sm font-semibold">
                                    Explore
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('development-objectives.add') }}" class="card p-8 border-l-4 border-orange-500 transition hover:shadow-lg hover:scale-105 transform h-80 flex flex-col items-start group">
                            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                </svg>
                            </div>
                            <div class="text-base font-bold text-orange-600">Add Objective</div>
                            <p class="text-gray-600 mt-3 text-base">Create a new development objective and action plan.</p>
                            <div class="mt-auto pt-6 text-orange-600 opacity-0 transition duration-200 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-2 text-sm font-semibold">
                                    Explore
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                        <a href="{{ route('development-objectives.progress') }}" class="card p-8 border-l-4 border-orange-500 hover:scale-105 transform transition h-80 flex flex-col items-start group">
                            <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6v12a2 2 0 002 2h12" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6l-8 8-4-4" />
                                </svg>
                            </div>
                            <div class="text-base font-bold text-orange-600">Progress Tracking</div>
                            <p class="text-gray-600 mt-3 text-base">Monitor objective status, uploads, and completion.</p>
                            <div class="mt-auto pt-6 text-orange-600 opacity-0 transition duration-200 group-hover:opacity-100">
                                <span class="inline-flex items-center gap-2 text-sm font-semibold">
                                    Explore
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6l6 6-6 6" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- ═══════════════════════════════════════════════════════
                         HARDCODED DEVELOPMENT OBJECTIVES / TARGETS
                    ═══════════════════════════════════════════════════════ -->
                    <div class="mb-10">
                        <div class="flex items-center gap-2 mb-5">
                            <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h2 class="text-lg font-bold text-orange-600">Development Objectives / Targets</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

                            <!-- ── GRADUATE STUDIES (has sub-objectives → opens modal) ── -->
                            <button type="button"
                                onclick="openSubModal('Graduate Studies', ['Master','Doctorate','Post-Doctor'])"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Graduation cap icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 13.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-3.5c0-.424.09-.835.25-1.21L12 14z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Graduate Studies</div>
                                <p class="text-gray-500 text-xs mt-1">Master · Doctorate · Post-Doctor</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Choose sub-target
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── ASEAN ENGINEER / ARCHITECT ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('ASEAN Engineer/Architect')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Building icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                        <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2"/>
                                        <polyline points="7 5 7 13 17 13 17 5" stroke="currentColor" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">ASEAN Engineer/Architect</div>
                                <p class="text-gray-500 text-xs mt-1">Professional engineering excellence</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Select
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── FACULTY & STAFF EXCHANGE PROGRAM ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Faculty & Staff Exchange Program')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- People exchange icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H7a4 4 0 01-4-4v-1a4 4 0 014-4h10a4 4 0 014 4v1a4 4 0 01-4 4z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7a3 3 0 100-6 3 3 0 000 6z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 8l2 2-2 2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-3"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12l-2 2 2 2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 14h3"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Faculty &amp; Staff Exchange Program</div>
                                <p class="text-gray-500 text-xs mt-1">Cross-institution collaboration</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Select
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── INDUSTRY IMMERSION PROGRAM ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Industry Immersion Program')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Industry icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 21V9l7-4 7 4v12"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 21v-6h6v6"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Industry Immersion Program</div>
                                <p class="text-gray-500 text-xs mt-1">Applied industry experience</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Select
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── MEMBERSHIP IN INTERNATIONAL ORGANIZATION & NETWORKS ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Membership in International Organization & Networks')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Network icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <circle cx="12" cy="5" r="3" stroke-width="2" />
                                        <circle cx="5" cy="19" r="3" stroke-width="2" />
                                        <circle cx="19" cy="19" r="3" stroke-width="2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 7.5l-2.5 7M14.5 7.5l2.5 7M8 17h8"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Membership in International Organization &amp; Networks</div>
                                <p class="text-gray-500 text-xs mt-1">Global professional engagement</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Select
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── PROFESSORIAL CHAIR ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Professorial Chair')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Academic honor icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 13.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-3.5c0-.424.09-.835.25-1.21L12 14z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Professorial Chair</div>
                                <p class="text-gray-500 text-xs mt-1">Academic leadership recognition</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Select
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── CONDUCT RESEARCHES & EXTENSION ACTIVITIES ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Conduct Researches & Extension Activities')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Research/beaker icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Conduct Researches &amp; Extension Activities</div>
                                <p class="text-gray-500 text-xs mt-1">Research and community engagement</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Select
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── PAPER PRESENTATION (has sub-objectives → opens modal) ── -->
                            <button type="button"
                                onclick="openSubModal('Paper Presentation', ['Local','International'])"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Presentation icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12.414V7a4 4 0 014-4z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Paper Presentation</div>
                                <p class="text-gray-500 text-xs mt-1">Local · International</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Choose sub-target
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── TRAINING / SEMINAR (has sub-objectives → opens modal) ── -->
                            <button type="button"
                                onclick="openSubModal('Training/Seminar', ['Local','International'])"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Seminar/training icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Training/Seminar</div>
                                <p class="text-gray-500 text-xs mt-1">Local · International</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Choose sub-target
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── SKILLS PROFICIENCY CERTIFICATION (has sub-objectives → opens modal) ── -->
                            <button type="button"
                                onclick="openSubModal('Skills Proficiency Certification', ['Local','International'])"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Certificate/badge icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0A5 5 0 017 12z"/>
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Skills Proficiency Certification</div>
                                <p class="text-gray-500 text-xs mt-1">Local · International</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Choose sub-target
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── OTHER (PLEASE SPECIFY) ── -->
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Other')"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <!-- Pencil icon -->
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                                    </svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Other (Please Specify)</div>
                                <p class="text-gray-500 text-xs mt-1">Enter a custom development objective</p>
                                <div class="mt-3 flex items-center gap-1 text-orange-500 text-xs font-semibold opacity-0 group-hover:opacity-100 transition">
                                    Enter
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>

                            <!-- ── Placeholder cards for future targets ── -->
                            <!-- You can copy the pattern above for targets without sub-objectives -->
                            <!-- Example of a single-target card (no modal): -->
                            {{-- 
                            <button type="button"
                                onclick="window.location='{{ route('development-objectives.add') }}?objective=Research+Publication'"
                                class="card p-6 border-l-4 border-orange-400 text-left hover:shadow-lg hover:scale-105 transform transition group focus:outline-none focus:ring-2 focus:ring-orange-400">
                                <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </div>
                                <div class="font-bold text-gray-800 text-sm">Research Publication</div>
                            </button>
                            --}}

                        </div>
                    </div>
                    <!-- ═══════════════════════════════════════════════════════ -->

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
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
                        <div class="card border-l-4 border-orange-500">
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-center gap-2 text-orange-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a9 9 0 109 9h-9V3z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 3.1A9 9 0 0120.9 11H13V3.1z" />
                                    </svg>
                                    <h2 class="text-lg font-semibold text-orange-600">Objective Breakdown</h2>
                                </div>
                            </div>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <div class="flex items-center justify-center relative">
                                    <div class="relative h-40 w-40">
                                        <svg id="objectives-pie" class="h-40 w-40" viewBox="0 0 160 160" aria-hidden="true">
                                            <circle cx="80" cy="80" r="{{ $pieRadius }}" stroke="#e5e7eb" stroke-width="18" fill="none" />
                                            @foreach($pieSegments as $segment)
                                                @if($segment['length'] > 0)
                                                    <circle
                                                        class="pie-segment"
                                                        cx="80"
                                                        cy="80"
                                                        r="{{ $pieRadius }}"
                                                        stroke="{{ $segment['color'] }}"
                                                        stroke-width="18"
                                                        fill="none"
                                                        stroke-dasharray="{{ $segment['length'] }} {{ $pieCircumference - $segment['length'] }}"
                                                        stroke-dashoffset="{{ -$segment['offset'] }}"
                                                        transform="rotate(-90 80 80)"
                                                        data-label="{{ $segment['label'] }}"
                                                        data-count="{{ $segment['count'] }}"
                                                        data-percent="{{ $segment['percent'] }}"
                                                    />
                                                @endif
                                            @endforeach
                                            <text x="80" y="84" text-anchor="middle" class="text-sm font-semibold fill-gray-700">
                                                {{ $totalObjectives }}
                                            </text>
                                        </svg>
                                        <div id="pie-tooltip" class="absolute hidden px-3 py-2 rounded bg-gray-800 text-white text-xs shadow-lg"></div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="h-3 w-3 rounded-full bg-amber-500"></span>
                                            <span class="text-sm text-gray-600">Pending</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $pendingObjectives }} ({{ $pendingPercentRounded }}%)
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="h-3 w-3 rounded-full bg-blue-500"></span>
                                            <span class="text-sm text-gray-600">In Progress</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $inProgressObjectives }} ({{ $inProgressPercentRounded }}%)
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="h-3 w-3 rounded-full bg-green-500"></span>
                                            <span class="text-sm text-gray-600">Completed</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $completedObjectives }} ({{ $completedPercentRounded }}%)
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Percentages based on total objectives
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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

            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════
         SUB-OBJECTIVE MODAL
    ══════════════════════════════════════════════════════════════ -->
    <div id="sub-objective-modal"
         class="fixed inset-0 z-50 flex items-center justify-center hidden"
         aria-modal="true" role="dialog" aria-labelledby="sub-modal-title">

        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             onclick="closeSubModal()"></div>

        <!-- Dialog panel -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 animate-modal">

            <!-- Close button -->
            <button type="button" onclick="closeSubModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Header -->
            <div class="flex items-center gap-3 mb-6">
                <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 13.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-3.5c0-.424.09-.835.25-1.21L12 14z"/>
                    </svg>
                </div>
                <div>
                    <h2 id="sub-modal-title" class="text-lg font-bold text-gray-800"></h2>
                    <p class="text-xs text-gray-500 mt-0.5">Select a sub-target to continue</p>
                </div>
            </div>

            <!-- Sub-objective choices (rendered by JS) -->
            <div id="sub-modal-options" class="space-y-3"></div>

            <!-- Cancel -->
            <button type="button" onclick="closeSubModal()"
                    class="mt-6 w-full py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500 hover:bg-gray-50 transition font-medium">
                Cancel
            </button>
        </div>
    </div>

    <style>
        @keyframes modal-in {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1)    translateY(0);    }
        }
        .animate-modal { animation: modal-in 0.18s ease both; }
    </style>

</body>
</html>

<script>
/* ── Sub-objective modal ─────────────────────────────────────────── */
function openSubModal(parentTitle, subOptions) {
    const modal  = document.getElementById('sub-objective-modal');
    const title  = document.getElementById('sub-modal-title');
    const opts   = document.getElementById('sub-modal-options');

    title.textContent = parentTitle;
    opts.innerHTML    = '';

    const addRoute = @json(route('development-objectives.add'));

    subOptions.forEach(sub => {
        const btn = document.createElement('button');
        btn.type  = 'button';
        btn.className =
            'w-full flex items-center justify-between px-5 py-4 rounded-xl ' +
            'border border-gray-100 bg-white hover:bg-orange-50 hover:border-orange-300 ' +
            'transition group text-left shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-400';

        btn.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="h-8 w-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 group-hover:bg-orange-100 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
                <span class="font-semibold text-gray-800 text-sm">${sub}</span>
            </div>
            <svg class="h-4 w-4 text-gray-300 group-hover:text-orange-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>`;

        btn.addEventListener('click', () => {
            const fullObjective = encodeURIComponent(parentTitle + ' – ' + sub);
            window.location.href = addRoute + '?objective=' + fullObjective;
        });

        opts.appendChild(btn);
    });

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Re-trigger animation
    const panel = modal.querySelector('.animate-modal');
    panel.style.animation = 'none';
    panel.offsetHeight; // reflow
    panel.style.animation = '';
}

function closeSubModal() {
    document.getElementById('sub-objective-modal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSubModal();
});
/* ─────────────────────────────────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
    const alertPopup = document.getElementById('alert-popup');
    if (alertPopup) {
        setTimeout(() => {
            alertPopup.classList.add('alert-hidden');
        }, 2000);
    }

    const pieChart = document.getElementById('objectives-pie');
    const pieTooltip = document.getElementById('pie-tooltip');

    if (!pieChart || !pieTooltip) {
        return;
    }

    const segments = pieChart.querySelectorAll('.pie-segment');

    const setTooltipPosition = (event) => {
        const rect = pieChart.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;
        pieTooltip.style.left = `${x + 12}px`;
        pieTooltip.style.top = `${y + 12}px`;
    };

    segments.forEach((segment) => {
        segment.addEventListener('mouseenter', (event) => {
            const label = segment.getAttribute('data-label');
            const count = segment.getAttribute('data-count');
            const percent = segment.getAttribute('data-percent');

            pieTooltip.textContent = `${label}: ${count} (${percent}%)`;
            pieTooltip.classList.remove('hidden');
            setTooltipPosition(event);
        });

        segment.addEventListener('mousemove', (event) => {
            setTooltipPosition(event);
        });

        segment.addEventListener('mouseleave', () => {
            pieTooltip.classList.add('hidden');
        });
    });
});

</script>
