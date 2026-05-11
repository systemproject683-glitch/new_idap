<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Progress Tracking - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/development-objectives-progress.css') }}">
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
                            <p class="text-gray-600 text-base">CEIT / <span class="text-orange-600 font-semibold">Progress Tracking</span></p>
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

                <div class="grid grid-cols-1 lg:grid-cols-[65%_35%] gap-6">
                    <div>
                        <div class="space-y-4">
                            @if($objectives->count() > 0)
                                @foreach($objectives as $objective)
                                    <a href="{{ route('development-objectives.list') }}#objective-{{ $objective->id }}" class="card border border-gray-200 rounded-lg p-4 hover:shadow-md transition transform hover:scale-[1.02] block">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="relative">
                                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                                        <span class="text-[#ff6b35]">Target:</span> {{ $objective->objective }}
                                                    </h3>
                                                    <div class="absolute top-0 right-0 z-10 flex flex-row-reverse items-center gap-0">
                                                        <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                            {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="text-gray-600 mb-3">
                                                    <span class="text-[#ff6b35]">Action Plan:</span> {{ $objective->action_plan }}
                                                </p>
                                                <hr></hr>

                                                <div class="flex items-center gap-4 mb-4 mt-3">
                                                    <span class="text-sm text-gray-500">
                                                        Created: {{ $objective->created_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500">
                                                        Hours: {{ $objective->number_of_hours !== null ? $objective->number_of_hours . ' hrs' : 'N/A' }}
                                                    </span>
                                                </div>

                                                @if($objective->max_files > 0)
                                                    @php
                                                        $approvedFileCount = $objective->files->where('verification_status', 'approved')->count();
                                                        $percentage = ($approvedFileCount / $objective->max_files) * 100;
                                                    @endphp
                                                    <div class="mb-2">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <span class="text-xs text-gray-500">
                                                                {{ $approvedFileCount }}/{{ $objective->max_files }} approved files
                                                            </span>
                                                            <span class="text-xs font-medium
                                                                @if($objective->status === 'completed') text-green-700
                                                                @elseif($objective->status === 'in_progress') text-blue-600
                                                                @else text-orange-500
                                                                @endif">
                                                                {{ round($percentage) }}% Complete
                                                            </span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="h-2 rounded-full transition-all duration-300
                                                                @if($objective->status === 'completed') bg-green-500
                                                                @elseif($objective->status === 'in_progress') bg-blue-500
                                                                @else bg-orange-400
                                                                @endif"
                                                                style="width: {{ min($percentage, 100) }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <p class="text-xs text-gray-500">No file requirements for this objective.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="card p-8 text-center">
                                    <p class="text-gray-500">No development objectives found. Add your first objective from the Add Objective page.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6 right-column-sticky">
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
            </div>
        </div>
    </div>
    <script>
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

<script>
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
