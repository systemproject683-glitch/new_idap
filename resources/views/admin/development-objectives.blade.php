<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Objectives - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #fff7ed; }
        .sidebar { background-color: #585858; }
        .sidebar-item:hover { background-color: #e55a2b; }
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }
        .header-bar {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 10px 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
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
            height: var(--page-header-height);
        }
        .page-header-spacer {
            height: calc(var(--page-header-height) + var(--page-header-gap));
        }
        select:focus { outline: none; box-shadow: 0 0 0 3px rgba(255,107,53,.15); border-color: #ff6b35; }
        .btn-primary { background-color: #ff6b35; }
        .btn-primary:hover { background-color: #e55a2b; }
        .status-pending      { background:#fef3c7; color:#92400e; white-space:nowrap; }
        .status-in_progress  { background:#dbeafe; color:#1e40af; white-space:nowrap; font-size:0.65rem; }
        .status-completed    { background:#d1fae5; color:#065f46; white-space:nowrap; }
    </style>
</head>
<body class="min-h-screen">
<div class="flex">
    @include('admin.sidebar')

    <div class="flex-1 ml-64 overflow-y-auto">
        <div class="p-8">

            {{-- Fixed Header --}}
            <div class="header-bar page-header-fixed">
                <div class="flex items-center justify-between h-full min-h-16">
                    <div>
                        <p class="text-gray-600 text-base">Admin / <span class="text-orange-600 font-semibold">Development Objectives - L&D Plan</span></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-600 text-base">{{ now()->format('F d, Y') }}</p>
                        <span class="text-gray-300 text-base">|</span>
                        <span id="live-time" class="text-orange-500 font-semibold text-base"></span>
                    </div>
                </div>
            </div>
            <div class="page-header-spacer"></div>

            <div class="px-5">

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- â”€â”€ Filter Card â”€â”€ --}}
                <div class="card p-6 mb-6">
                    <form method="GET" action="{{ route('admin.development-objectives') }}">
                        <div class="flex flex-wrap gap-5 items-end">

                            {{-- Objective Dropdown --}}
                            <div class="flex-1 min-w-[260px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Development Objective</label>
                                <select name="objective"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-700 bg-white transition">
                                    <option value="">— All Objectives —</option>
                                    @foreach($allObjectiveNames as $name)
                                        <option value="{{ $name }}" @selected($selectedObjective === $name)>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Department Dropdown --}}
                            <div class="flex-1 min-w-[200px]">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                                <select name="department"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm text-gray-700 bg-white transition">
                                    <option value="">— All Departments —</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" @selected($selectedDepartment === $dept)>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex gap-3">
                                <button type="submit"
                                        class="btn-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                                    </svg>
                                    Filter
                                </button>
                                @if($selectedObjective || $selectedDepartment)
                                    <a href="{{ route('admin.development-objectives') }}"
                                       class="px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                                        Clear
                                    </a>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>

                {{-- â”€â”€ Results â”€â”€ --}}
                {{-- Always show stats and table --}}
                <div class="flex gap-6">
                <div class="flex-1 min-w-0">

                    {{-- Summary stats --}}
                    @php
                        $total     = $statsTotal;
                        $pending   = $statsPending;
                        $inProg    = $statsInProg;
                        $completed = $statsCompleted;
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="card p-5 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
                                <p class="text-xs text-gray-500">Total Enrolled</p>
                            </div>
                        </div>

                        <div class="card p-5 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $pending }}</p>
                                <p class="text-xs text-gray-500">Pending</p>
                            </div>
                        </div>

                        <div class="card p-5 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $inProg }}</p>
                                <p class="text-xs text-gray-500">In Progress</p>
                            </div>
                        </div>

                        <div class="card p-5 flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $completed }}</p>
                                <p class="text-xs text-gray-500">Completed</p>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="card overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h2 class="text-base font-semibold text-gray-800">Enrolled Faculty</h2>
                                @if($selectedObjective)
                                    <p class="text-sm text-gray-500 mt-0.5 truncate max-w-xl">{{ $selectedObjective }}</p>
                                @endif
                            </div>
                            @if($selectedDepartment)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $selectedDepartment }}
                                </span>
                            @endif
                        </div>

                        @if($facultyRecords->isEmpty())
                            <div class="py-16 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-gray-500 font-medium">No faculty found</p>
                                <p class="text-gray-400 text-sm mt-1">No one is enrolled in the selected filters.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100">
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Faculty Member</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Department</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Academic Rank</th>
                                            @if(!$selectedObjective)
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Objective</th>
                                            @endif
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Files</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Added</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($facultyRecords as $i => $record)
                                            @php $user = $record->user; @endphp
                                            <tr class="hover:bg-orange-50/30 transition">

                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                                                             style="background: linear-gradient(135deg, #FFAA55, #FF6622);">
                                                            {{ $user ? strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) : '??' }}
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold text-gray-800">
                                                                {{ $user ? $user->last_name . ', ' . $user->first_name : 'Unknown' }}
                                                            </p>
                                                            <p class="text-xs text-gray-400">{{ $user?->email }}</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4">
                                                    <span class="inline-block px-2.5 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-medium">
                                                        {{ $user?->department ?? '—' }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4 text-gray-600 text-xs">
                                                    {{ $user?->academic_rank ?? '—' }}
                                                </td>

                                                @if(!$selectedObjective)
                                                <td class="px-6 py-4 text-gray-700 max-w-[200px]">
                                                    <p class="truncate text-xs" title="{{ $record->objective }}">{{ $record->objective }}</p>
                                                </td>
                                                @endif

                                                <td class="px-6 py-4">
                                                    @php
                                                        $statusLabel = match($record->status) {
                                                            'pending'     => 'Pending',
                                                            'in_progress' => 'In Progress',
                                                            'completed'   => 'Completed',
                                                            default       => ucfirst($record->status),
                                                        };
                                                    @endphp
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold status-{{ $record->status }}">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4">
                                                    @php
                                                        $maxFiles = $record->max_files ?? 1;
                                                        $uploaded = $record->total_files ?? 0;
                                                        $approved = $record->approved_files ?? 0;
                                                        $pct      = $maxFiles > 0 ? min(100, round(($approved / $maxFiles) * 100)) : 0;
                                                    @endphp
                                                    <div class="flex items-center gap-2">
                                                        <div class="flex-1 bg-gray-200 rounded-full h-1.5 min-w-[60px]">
                                                            <div class="h-1.5 rounded-full {{ $pct >= 100 ? 'bg-green-500' : 'bg-orange-400' }}"
                                                                 style="width:{{ $pct }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-500 whitespace-nowrap">{{ $approved }}/{{ $maxFiles }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $uploaded }} uploaded, {{ $approved }} approved</p>
                                                </td>

                                                <td class="px-6 py-4 text-xs text-gray-400 whitespace-nowrap">
                                                    {{ $record->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if($facultyRecords->hasPages())
                    <div class="flex items-center justify-between mt-4 px-2">
                        <p class="text-xs text-gray-500">
                            Showing {{ $facultyRecords->firstItem() }}–{{ $facultyRecords->lastItem() }} of {{ $facultyRecords->total() }} records
                        </p>
                        <div class="flex items-center gap-2">
                            @if($facultyRecords->onFirstPage())
                                <span class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-300 bg-gray-100 cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $facultyRecords->previousPageUrl() }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">
                                    Previous
                                </a>
                            @endif

                            @foreach($facultyRecords->getUrlRange(1, $facultyRecords->lastPage()) as $pg => $url)
                                <a href="{{ $url }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition
                                          {{ $pg === $facultyRecords->currentPage()
                                             ? 'text-white bg-orange-500'
                                             : 'text-gray-600 bg-white border border-gray-200 hover:bg-gray-50' }}">
                                    {{ $pg }}
                                </a>
                            @endforeach

                            @if($facultyRecords->hasMorePages())
                                <a href="{{ $facultyRecords->nextPageUrl() }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">
                                    Next
                                </a>
                            @else
                                <span class="px-3 py-1.5 rounded-lg text-xs font-medium text-gray-300 bg-gray-100 cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>{{-- end flex gap-6 --}}

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

