<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Activities - L&D Plan</title>
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
        .activity-row {
            transition: background-color 0.15s ease, transform 0.15s ease;
            border-left: 3px solid transparent;
        }
        .activity-row:hover {
            background-color: #fff7ed;
            border-left-color: #ff6b35;
            transform: translateX(3px);
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
                    <div class="flex items-center justify-between h-full min-h-16">
                        <div>
                            <p class="text-gray-600 text-base">Admin / <span class="text-orange-600 font-semibold">Recent Activities</span></p>
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
                    <!-- Page Title & Filters -->
                    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Recent Activities</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Showing all activities from
                                <span class="font-semibold text-orange-500">{{ now()->subMonth()->format('F d, Y') }}</span>
                                to
                                <span class="font-semibold text-orange-500">{{ now()->format('F d, Y') }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Department Dropdown -->
                            <form method="GET" action="{{ route('admin.recent-activities') }}" class="flex items-center gap-2">
                                <label for="department" class="text-sm font-medium text-gray-600 whitespace-nowrap">Department:</label>
                                <select id="department" name="department"
                                        onchange="this.form.submit()"
                                        class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent cursor-pointer"
                                        style="min-width:130px;">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ $selectedDepartment === $dept ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold"
                                  style="background:#fff7ed; color:#ff6b35; border:1px solid #ffcba4;">
                                {{ $activities->count() }} {{ Str::plural('activity', $activities->count()) }}
                            </span>
                        </div>
                    </div>

                    <!-- Activities Card -->
                    <div class="card card-orange-shadow overflow-hidden">
                        @if($activities->count() > 0)
                            <!-- Table Header -->
                            <div class="grid grid-cols-12 gap-4 px-6 py-3 text-xs font-bold uppercase tracking-widest text-gray-500"
                                 style="background:#fff7ed; border-bottom:1px solid #ffe0c8;">
                                <div class="col-span-4">User</div>
                                <div class="col-span-2">Department</div>
                                <div class="col-span-3">Action</div>
                                <div class="col-span-3">Date & Time</div>
                            </div>

                            <!-- Activity Rows -->
                            <div class="divide-y divide-gray-100">
                                @foreach($activities as $index => $activity)
                                    <div class="activity-row grid grid-cols-12 gap-4 px-6 py-4 items-center">
                                        <!-- User -->
                                        <div class="col-span-4 flex items-center gap-3">
                                            @php
                                                $nameParts = explode(' ', $activity['user']);
                                                $initials = strtoupper(substr($nameParts[0], 0, 1));
                                                if (count($nameParts) > 1) {
                                                    $initials .= strtoupper(substr(end($nameParts), 0, 1));
                                                }
                                            @endphp
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                                 style="background: linear-gradient(135deg, #FFAA55, #FF6622);">
                                                {{ $initials }}
                                            </div>
                                            <span class="text-sm font-semibold text-gray-800">{{ $activity['user'] }}</span>
                                        </div>

                                        <!-- Department -->
                                        <div class="col-span-2">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold"
                                                  style="background:#fff7ed; color:#ff6b35; border:1px solid #ffcba4;">
                                                {{ $activity['department'] }}
                                            </span>
                                        </div>

                                        <!-- Action -->
                                        <div class="col-span-3">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-orange-400 flex-shrink-0"></span>
                                                <span class="text-sm text-gray-700">{{ $activity['action'] }}</span>
                                            </div>
                                            @if(!empty($activity['objective_title']))
                                                <p class="text-xs text-gray-400 mt-0.5 ml-4 truncate" title="{{ $activity['objective_title'] }}">
                                                    {{ Str::limit($activity['objective_title'], 60) }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Date & Time -->
                                        <div class="col-span-3">
                                            <p class="text-sm text-gray-700">{{ $activity['time']->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $activity['time']->format('h:i A') }} &middot; {{ $activity['time']->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-20">
                                <svg class="w-16 h-16 text-orange-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-400 text-lg font-medium">No activities in the past month</p>
                                <p class="text-gray-300 text-sm mt-1">Activities will appear here as users interact with the system.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Live clock
        function updateTime() {
            const now = new Date();
            const h = now.getHours() % 12 || 12;
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
            document.getElementById('live-time').textContent = `${h}:${m}:${s} ${ampm}`;
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
