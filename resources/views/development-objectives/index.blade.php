<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Development Objectives - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/development-objectives-index.css') }}">
    <style>
        @keyframes titleBounce {
            0%, 100% { transform: translateY(0); }
            25% { transform: translateY(-5px); }
            75% { transform: translateY(-2px); }
        }
        .objectives-list button {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }
        .objectives-list button:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 16px 0 rgba(234, 88, 12, 0.12);
            background-color: #fff7ed;
            z-index: 10;
            position: relative;
        }
        .objectives-list button:hover .flex-1 > div:first-child {
            animation: titleBounce 0.4s ease;
            color: #ea580c;
        }
        .objectives-list button:hover .rounded-lg {
            background-color: #fed7aa;
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
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">CEIT / <span class="text-orange-600 font-semibold">Dashboard</span></p>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                        <!-- Total Objectives Card -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="h-1 bg-amber-800"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="h-5 w-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                    <p class="text-gray-600 text-sm font-medium text-amber-800">Total Objectives</p>
                                </div>
                                <div class="text-4xl font-bold text-amber-800 mb-2">{{ $totalObjectives }}</div>
                                <p class="text-gray-500 text-sm">{{ $totalObjectives === 0 ? 'No data yet' : ($totalObjectives === 1 ? '1 objective' : $totalObjectives . ' objectives') }}</p>
                            </div>
                        </div>

                        <!-- Pending Card -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="h-1" style="background:#ff6b35;"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="h-5 w-5" style="color:#ff6b35;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-medium" style="color:#ff6b35;">Pending</p>
                                </div>
                                <div class="text-4xl font-bold mb-2" style="color:#ff6b35;">{{ $pendingObjectives }}</div>
                                <p class="text-gray-500 text-sm">Awaiting action</p>
                            </div>
                        </div>

                        <!-- In Progress Card -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="h-1 bg-blue-500"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <p class="text-gray-600 text-sm font-medium text-blue-500">In Progress</p>
                                </div>
                                <div class="text-4xl font-bold text-blue-500 mb-2">{{ $inProgressObjectives }}</div>
                                <p class="text-gray-500 text-sm">Active objectives</p>
                            </div>
                        </div>

                        <!-- Completed Card -->
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="h-1 bg-green-500"></div>
                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-4">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p class="text-gray-600 text-sm font-medium text-green-500">Completed</p>
                                </div>
                                <div class="text-4xl font-bold text-green-500 mb-2">{{ $completedObjectives }}</div>
                                <p class="text-gray-500 text-sm">Finished goals</p>
                            </div>
                        </div>
                    </div>



                    <!-- ═══════════════════════════════════════════════════════
                         TWO COLUMN LAYOUT: OBJECTIVES (70%) + TRACKING (30%)
                    ═══════════════════════════════════════════════════════ -->
                    <div class="flex gap-8 mb-10">
                        <!-- LEFT COLUMN: Development Objectives / Targets (70%) -->
                        <div class="flex-1" style="flex-basis: 70%;">
                            <div class="mb-10">
                                <div class="flex items-center gap-2 mb-5">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <h2 class="text-lg font-bold">Development Objectives / Targets - L&D Plan</h2>
                                    <span class="text-gray-500 text-sm ml-auto">10 available tracks</span>
                                </div>
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <div class="space-y-0 objectives-list">
                                        <!-- ── GRADUATE STUDIES (has sub-objectives → opens modal) ── -->
                                        <button type="button"
                                            onclick="openSubModal('Graduate Studies', ['Master','Doctorate','Post-Doctor'])"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">01</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 13.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-3.5c0-.424.09-.835.25-1.21L12 14z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Graduate Studies</div>
                                                <p class="text-sm text-gray-500">Master · Doctorate · Post-Doctor</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Academic</div>
                                        </button>

                                        <!-- ── ASEAN ENGINEER / ARCHITECT ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('ASEAN Engineer/Architect')"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">02</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                                    <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-width="2"/>
                                                    <polyline points="7 5 7 13 17 13 17 5" stroke="currentColor" stroke-width="2"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">ASEAN Engineer/Architect</div>
                                                <p class="text-sm text-gray-500">Professional engineering excellence</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Engineering</div>
                                        </button>

                                        <!-- ── FACULTY & STAFF EXCHANGE PROGRAM ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Faculty & Staff Exchange Program')"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">03</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20H7a4 4 0 01-4-4v-1a4 4 0 014-4h10a4 4 0 014 4v1a4 4 0 01-4 4z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7a3 3 0 100-6 3 3 0 000 6z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Faculty & Staff Exchange Program</div>
                                                <p class="text-sm text-gray-500">Cross-institution collaboration</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">International</div>
                                        </button>

                                        <!-- ── INDUSTRY IMMERSION PROGRAM ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Industry Immersion Program')"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">04</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 21V9l7-4 7 4v12"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 21v-6h6v6"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Industry Immersion Program</div>
                                                <p class="text-sm text-gray-500">Applied industry experience</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Industry</div>
                                        </button>

                                        <!-- ── MEMBERSHIP IN INTERNATIONAL ORGANIZATION & NETWORKS ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Membership in International Organization & Networks')"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">05</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <circle cx="12" cy="5" r="3" stroke-width="2" />
                                                    <circle cx="5" cy="19" r="3" stroke-width="2" />
                                                    <circle cx="19" cy="19" r="3" stroke-width="2" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.5 7.5l-2.5 7M14.5 7.5l2.5 7M8 17h8"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Membership in International Org. & Networks</div>
                                                <p class="text-sm text-gray-500">Global professional engagement</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Networking</div>
                                        </button>

                                        <!-- ── PROFESSORIAL CHAIR ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Professorial Chair')"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">06</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 13.5V17a2 2 0 01-2 2H5a2 2 0 01-2-2v-3.5c0-.424.09-.835.25-1.21L12 14z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Professorial Chair</div>
                                                <p class="text-sm text-gray-500">Academic leadership recognition</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Academic</div>
                                        </button>

                                        <!-- ── CONDUCT RESEARCHES & EXTENSION ACTIVITIES ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Conduct Researches & Extension Activities')"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">07</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Conduct Researches & Extension</div>
                                                <p class="text-sm text-gray-500">Research and community engagement</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Research</div>
                                        </button>

                                        <!-- ── PAPER PRESENTATION (has sub-objectives → opens modal) ── -->
                                        <button type="button"
                                            onclick="openSubModal('Paper Presentation', ['Local','International'])"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">08</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12.414V7a4 4 0 014-4z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Paper Presentation</div>
                                                <p class="text-sm text-gray-500">Local · International</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Publication</div>
                                        </button>

                                        <!-- ── TRAINING / SEMINAR (has sub-objectives → opens modal) ── -->
                                        <button type="button"
                                            onclick="openSubModal('Training/Seminar', ['Local','International'])"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">09</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Training/Seminar</div>
                                                <p class="text-sm text-gray-500">Local · International</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Development</div>
                                        </button>

                                        <!-- ── SKILLS PROFICIENCY CERTIFICATION (has sub-objectives → opens modal) ── -->
                                        <button type="button"
                                            onclick="openSubModal('Skills Proficiency Certification', ['Local','International'])"
                                            class="w-full flex items-center gap-4 p-5 border-b border-gray-100 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">10</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0A5 5 0 017 12z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Skills Proficiency Certification</div>
                                                <p class="text-sm text-gray-500">Local · International</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Certification</div>
                                        </button>

                                        <!-- ── OTHER (PLEASE SPECIFY) ── -->
                                        <button type="button"
                                            onclick="window.location='{{ route('development-objectives.add') }}?objective=' + encodeURIComponent('Other')"
                                            class="w-full flex items-center gap-4 p-5 text-left hover:bg-orange-50 transition group focus:outline-none">
                                            <div class="flex-shrink-0 text-gray-400 font-semibold text-sm w-8">11</div>
                                            <div class="flex-shrink-0 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">Other (Please Specify)</div>
                                                <p class="text-sm text-gray-500">Enter a custom development objective</p>
                                            </div>
                                            <div class="flex-shrink-0 text-orange-600 text-sm font-medium">Custom</div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN: Tracking (30%) -->
                        <div class="flex-1 sticky top-24 self-start" style="flex-basis: 30%;">
                            <div class="space-y-6">
                                <!-- Your Progress Card -->
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

                                <!-- Objective Breakdown Card -->
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
                                    @php
                                        $arcR          = 70;
                                        $arcHalf       = M_PI * $arcR;          // half-circumference ≈ 219.9
                                        $arcFull       = 2 * M_PI * $arcR;      // full circumference
                                        $arcCompleted  = $totalObjectives > 0 ? ($completedObjectives  / $totalObjectives) * $arcHalf : 0;
                                        $arcInProgress = $totalObjectives > 0 ? ($inProgressObjectives / $totalObjectives) * $arcHalf : 0;
                                        $arcPending    = $totalObjectives > 0 ? ($pendingObjectives    / $totalObjectives) * $arcHalf : 0;
                                    @endphp
                                    <div class="p-6">
                                        <!-- Arc / semicircle gauge -->
                                        <div class="flex justify-center">
                                            <svg viewBox="0 0 200 118" class="w-full max-w-[200px]" aria-hidden="true">
                                                <!-- Background track — always full half-arc, no gap -->
                                                <circle cx="100" cy="100" r="{{ $arcR }}" fill="none"
                                                        stroke="#e5e7eb" stroke-width="20"
                                                        stroke-dasharray="{{ $arcHalf }} {{ $arcFull }}"
                                                        transform="rotate(-180 100 100)" />

                                                <!-- Completed — green, leftmost -->
                                                @if($arcCompleted > 0.05)
                                                <circle cx="100" cy="100" r="{{ $arcR }}" fill="none"
                                                        stroke="#22c55e" stroke-width="20" stroke-linecap="butt"
                                                        stroke-dasharray="{{ $arcCompleted }} {{ $arcFull }}"
                                                        stroke-dashoffset="0"
                                                        transform="rotate(-180 100 100)" />
                                                @endif

                                                <!-- In Progress — blue -->
                                                @if($arcInProgress > 0.05)
                                                <circle cx="100" cy="100" r="{{ $arcR }}" fill="none"
                                                        stroke="#3b82f6" stroke-width="20" stroke-linecap="butt"
                                                        stroke-dasharray="{{ $arcInProgress }} {{ $arcFull }}"
                                                        stroke-dashoffset="{{ -$arcCompleted }}"
                                                        transform="rotate(-180 100 100)" />
                                                @endif

                                                <!-- Pending — orange -->
                                                @if($arcPending > 0.05)
                                                <circle cx="100" cy="100" r="{{ $arcR }}" fill="none"
                                                        stroke="#ff6b35" stroke-width="20" stroke-linecap="butt"
                                                        stroke-dasharray="{{ $arcPending }} {{ $arcFull }}"
                                                        stroke-dashoffset="{{ -($arcCompleted + $arcInProgress) }}"
                                                        transform="rotate(-180 100 100)" />
                                                @endif

                                                <!-- Rounded end-caps overlay (always on top) -->
                                                @php
                                                    // Left end cap: always at start of arc (left tip = angle 0° in rotated coords = bottom-left of circle)
                                                    // Arc starts at angle 180° (left) and ends at 0° (right) in standard SVG
                                                    // Left cap dot
                                                    $capLX = round(100 + $arcR * cos(deg2rad(180)), 3);
                                                    $capLY = round(100 + $arcR * sin(deg2rad(180)), 3);
                                                    // Right cap dot
                                                    $capRX = round(100 + $arcR * cos(deg2rad(0)), 3);
                                                    $capRY = round(100 + $arcR * sin(deg2rad(0)), 3);

                                                    // Determine color of left cap (start of arc = completed color if exists, else in-progress, else pending, else gray)
                                                    $leftCapColor = $arcCompleted > 0.05 ? '#22c55e' : ($arcInProgress > 0.05 ? '#3b82f6' : ($arcPending > 0.05 ? '#ff6b35' : '#e5e7eb'));
                                                    // Right cap = color of last segment
                                                    $rightCapColor = $arcPending > 0.05 ? '#ff6b35' : ($arcInProgress > 0.05 ? '#3b82f6' : ($arcCompleted > 0.05 ? '#22c55e' : '#e5e7eb'));
                                                @endphp
                                                <circle cx="{{ $capLX }}" cy="{{ $capLY }}" r="10" fill="{{ $leftCapColor }}" />
                                                <circle cx="{{ $capRX }}" cy="{{ $capRY }}" r="10" fill="{{ $rightCapColor }}" />

                                                <!-- Total count -->
                                                <text x="100" y="110" text-anchor="middle"
                                                      font-size="26" font-weight="700" fill="#ea580c"
                                                      font-family="ui-sans-serif, system-ui, sans-serif">{{ $totalObjectives }}</text>
                                            </svg>
                                        </div>
                                        <!-- Legend -->
                                        <div class="space-y-2 mt-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="h-3 w-3 rounded-full" style="background:#22c55e;"></span>
                                                    <span class="text-sm text-gray-600">Completed</span>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700">
                                                    {{ $completedObjectives }} ({{ $completedPercentRounded }}%)
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="h-3 w-3 rounded-full" style="background:#3b82f6;"></span>
                                                    <span class="text-sm text-gray-600">In Progress</span>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700">
                                                    {{ $inProgressObjectives }} ({{ $inProgressPercentRounded }}%)
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="h-3 w-3 rounded-full" style="background:#ff6b35;"></span>
                                                    <span class="text-sm text-gray-600">Pending</span>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700">
                                                    {{ $pendingObjectives }} ({{ $pendingPercentRounded }}%)
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 pt-1">Percentages based on total objectives</p>
                                        </div>
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

    <script>
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
