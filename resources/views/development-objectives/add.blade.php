<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Objective - L&D Plan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/development-objectives-add.css') }}">
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
                            <p class="text-gray-600 text-base">CEIT / <span class="text-orange-600 font-semibold">Add Objective</span></p>
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

                <div class="bg-white rounded-2xl shadow-2xl max-w-2xl mx-auto overflow-hidden">
                    <!-- Modal-style header -->
                    <div class="flex items-center gap-3 px-8 pt-8 pb-6 border-b border-gray-100">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Add New Objective</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Fill in the details to create a development goal</p>
                        </div>
                    </div>
                    <div class="px-8 py-6 space-y-4">
                        <form method="POST" action="{{ route('development-objectives.store') }}">
                            @csrf
                            <!-- Objective Dropdown -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-visible relative z-10">
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">1</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Development Objective/Target</h3>
                                </div>
                                <div class="px-5 py-4">
                                @php
                                    $preSelected = old('objective', request('objective'));
                                    $lockedObjective = request()->has('objective');
                                    $lockedLabel = $preSelected === 'Other'
                                        ? 'Other (Specify your own objective)'
                                        : $preSelected;
                                @endphp
                                @if($lockedObjective)
                                    <div class="input-field w-full px-4 py-2.5 text-gray-700 bg-orange-50">
                                        {{ $lockedLabel }}
                                    </div>
                                    <input type="hidden" id="objective" name="objective" value="{{ $preSelected }}">
                                
                                @else
                                    <div class="custom-select w-full" data-custom-select="objective">
                                        <select
                                            id="objective"
                                            name="objective"
                                            class="custom-select-native"
                                            required
                                            onchange="updateActionPlan()"
                                        >
                                            <option value="">Select Objective</option>
                                            @if(count($predefinedObjectives) > 0)
                                                <optgroup label="Predefined Objectives">
                                                    @foreach($predefinedObjectives as $objective => $actionPlan)
                                                        <option value="{{ $objective }}" {{ $preSelected === $objective ? 'selected' : '' }}>{{ $objective }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                            @if($adminObjectives->count() > 0)
                                                @foreach($adminObjectives as $objective)
                                                    <option value="{{ $objective->objective }}" {{ $preSelected === $objective->objective ? 'selected' : '' }}>{{ $objective->objective }}</option>
                                                @endforeach
                                            @endif
                                            <option value="Other" {{ $preSelected === 'Other' ? 'selected' : '' }}>Other (Specify your own objective)</option>
                                        </select>
                                        <button type="button" class="custom-select-trigger input-field w-full px-4 py-2.5 text-gray-700">
                                            <span class="custom-select-label">Select Objective</span>
                                            <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <div class="custom-select-menu">
                                            @if(count($predefinedObjectives) > 0)
                                                <div class="custom-select-group">Predefined Objectives</div>
                                                @foreach($predefinedObjectives as $objective => $actionPlan)
                                                    <button type="button" class="custom-select-option" data-select-value="{{ $objective }}">{{ $objective }}</button>
                                                @endforeach
                                            @endif
                                            @if($adminObjectives->count() > 0)
                                                @foreach($adminObjectives as $objective)
                                                    <button type="button" class="custom-select-option" data-select-value="{{ $objective->objective }}">{{ $objective->objective }}</button>
                                                @endforeach
                                            @endif
                                            <button type="button" class="custom-select-option" data-select-value="Other">Other (Specify your own objective)</button>
                                        </div>
                                    </div>
                                    <p class="step-helper">Choose a development objective that aligns with your goals</p>
                                @endif
                                @error('objective')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                </div>
                            </div>

                            <!-- Custom Objective Input (Hidden by default) -->
                            <div id="custom_objective_container" class="rounded-xl border border-orange-100 shadow-sm overflow-hidden" style="display: {{ $preSelected === 'Other' ? 'block' : 'none' }};">
                                <div class="flex items-center gap-3 px-5 py-3 bg-orange-50 border-b border-orange-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-100 flex items-center justify-center text-orange-500 flex-shrink-0">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </span>
                                    <label for="custom_objective" class="font-semibold text-gray-800 text-sm">Custom Objective</label>
                                </div>
                                <div class="px-5 py-4">
                                    <input
                                        type="text"
                                        id="custom_objective"
                                        name="custom_objective"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter your custom objective name..."
                                        {{ $preSelected === 'Other' ? 'required' : '' }}
                                    >
                                    @error('custom_objective')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                           

                            <!-- Action Plan -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">2</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Action Plan</h3>
                                </div>
                                <div class="px-5 py-4">
                                    <textarea
                                        id="action_plan"
                                        name="action_plan"
                                        class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                        rows="3"
                                        placeholder="Describe your action plan..."
                                        required
                                    ></textarea>
                                    <p class="text-xs text-gray-400 mt-1">Provide a comprehensive description of your action plan</p>
                                    @error('action_plan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                             <!-- Title of Activity Attended -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">3</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Title of Activity Attended</h3>
                                </div>
                                <div class="px-5 py-4">
                                    <input
                                        type="text"
                                        id="title"
                                        name="title"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter the title of the activity or event you attended..."
                                        required
                                    >
                                    <p class="text-xs text-gray-400 mt-1">Specify the name or title of the workshop, seminar, training, or activity</p>
                                    @error('title')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Number of Hours -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">4</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Number of Hours</h3>
                                </div>
                                <div class="px-5 py-4">
                                    <input
                                        type="number"
                                        id="number_of_hours"
                                        name="number_of_hours"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter number of hours..."
                                        min="0"
                                        step="1"
                                        required
                                    >
                                    <p class="text-xs text-gray-400 mt-1">Enter the estimated hours required for this objective</p>
                                    @error('number_of_hours')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Budget Requirement -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">5</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Budget Requirement</h3>
                                </div>
                                <div class="px-5 py-4">
                                    <input
                                        type="number"
                                        id="budget_requirement"
                                        name="budget_requirement"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter budget requirement..."
                                        min="0"
                                        step="0.01"
                                    >
                                    <p class="text-xs text-gray-400 mt-1">Enter the estimated budget required for this objective</p>
                                    @error('budget_requirement')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Target Period -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-visible relative" data-section-card>
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">6</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Target Period</h3>
                                </div>
                                <div class="px-5 py-4">
                                <div class="custom-select w-full" data-custom-select="target_period">
                                    <select
                                        id="target_period"
                                        name="target_period"
                                        class="custom-select-native"
                                    >
                                        <option value="" selected disabled hidden>Select target period</option>
                                        <option value="Q1">Q1</option>
                                        <option value="Q2">Q2</option>
                                        <option value="Q3">Q3</option>
                                        <option value="Q4">Q4</option>
                                    </select>
                                    <button type="button" class="custom-select-trigger input-field w-full px-4 py-2.5 text-gray-700">
                                        <span class="custom-select-label">Select target period</span>
                                        <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="custom-select-menu">
                                        <button type="button" class="custom-select-option" data-select-value="Q1">Q1</button>
                                        <button type="button" class="custom-select-option" data-select-value="Q2">Q2</button>
                                        <button type="button" class="custom-select-option" data-select-value="Q3">Q3</button>
                                        <button type="button" class="custom-select-option" data-select-value="Q4">Q4</button>
                                    </div>
                                </div>
                                    <p class="text-xs text-gray-400 mt-1">Select the quarter when you plan to complete this objective</p>
                                    @error('target_period')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Target Date From and To -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-visible relative" data-section-card>
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">7</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Target Dates (Month)</h3>
                                </div>
                                <div class="px-5 py-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="target_date_from" class="block text-sm font-medium text-gray-700 mb-2">From (Month)</label>
                                        <div class="custom-select custom-select-white w-full" data-custom-select="target_date_from">
                                            <select id="target_date_from" name="target_date_from" class="custom-select-native">
                                                <option value="" selected disabled hidden>Select month</option>
                                                <option value="January">January</option>
                                                <option value="February">February</option>
                                                <option value="March">March</option>
                                                <option value="April">April</option>
                                                <option value="May">May</option>
                                                <option value="June">June</option>
                                                <option value="July">July</option>
                                                <option value="August">August</option>
                                                <option value="September">September</option>
                                                <option value="October">October</option>
                                                <option value="November">November</option>
                                                <option value="December">December</option>
                                            </select>
                                            <button type="button" class="custom-select-trigger input-field w-full px-4 py-2.5 text-gray-700">
                                                <span class="custom-select-label">Select month</span>
                                                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div class="custom-select-menu">
                                                <button type="button" class="custom-select-option" data-select-value="January">January</button>
                                                <button type="button" class="custom-select-option" data-select-value="February">February</button>
                                                <button type="button" class="custom-select-option" data-select-value="March">March</button>
                                                <button type="button" class="custom-select-option" data-select-value="April">April</button>
                                                <button type="button" class="custom-select-option" data-select-value="May">May</button>
                                                <button type="button" class="custom-select-option" data-select-value="June">June</button>
                                                <button type="button" class="custom-select-option" data-select-value="July">July</button>
                                                <button type="button" class="custom-select-option" data-select-value="August">August</button>
                                                <button type="button" class="custom-select-option" data-select-value="September">September</button>
                                                <button type="button" class="custom-select-option" data-select-value="October">October</button>
                                                <button type="button" class="custom-select-option" data-select-value="November">November</button>
                                                <button type="button" class="custom-select-option" data-select-value="December">December</button>
                                            </div>
                                        </div>
                                        @error('target_date_from')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="target_date_to" class="block text-sm font-medium text-gray-700 mb-2">To (Month)</label>
                                        <div class="custom-select custom-select-white w-full" data-custom-select="target_date_to">
                                            <select id="target_date_to" name="target_date_to" class="custom-select-native">
                                                <option value="" selected disabled hidden>Select month</option>
                                                <option value="January">January</option>
                                                <option value="February">February</option>
                                                <option value="March">March</option>
                                                <option value="April">April</option>
                                                <option value="May">May</option>
                                                <option value="June">June</option>
                                                <option value="July">July</option>
                                                <option value="August">August</option>
                                                <option value="September">September</option>
                                                <option value="October">October</option>
                                                <option value="November">November</option>
                                                <option value="December">December</option>
                                            </select>
                                            <button type="button" class="custom-select-trigger input-field w-full px-4 py-2.5 text-gray-700">
                                                <span class="custom-select-label">Select month</span>
                                                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div class="custom-select-menu">
                                                <button type="button" class="custom-select-option" data-select-value="January">January</button>
                                                <button type="button" class="custom-select-option" data-select-value="February">February</button>
                                                <button type="button" class="custom-select-option" data-select-value="March">March</button>
                                                <button type="button" class="custom-select-option" data-select-value="April">April</button>
                                                <button type="button" class="custom-select-option" data-select-value="May">May</button>
                                                <button type="button" class="custom-select-option" data-select-value="June">June</button>
                                                <button type="button" class="custom-select-option" data-select-value="July">July</button>
                                                <button type="button" class="custom-select-option" data-select-value="August">August</button>
                                                <button type="button" class="custom-select-option" data-select-value="September">September</button>
                                                <button type="button" class="custom-select-option" data-select-value="October">October</button>
                                                <button type="button" class="custom-select-option" data-select-value="November">November</button>
                                                <button type="button" class="custom-select-option" data-select-value="December">December</button>
                                            </div>
                                        </div>
                                        @error('target_date_to')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                    <p class="text-xs text-gray-400 mt-1">Specify the month range for target completion (optional)</p>
                                </div>
                            </div>

                            <!-- Support Required -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">8</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Support Required</h3>
                                </div>
                                <div class="px-5 py-4">
                                    <input
                                        type="text"
                                        id="support_required"
                                        name="support_required"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter support required..."
                                    >
                                    <p class="text-xs text-gray-400 mt-1">Describe any support or resources needed to achieve this objective</p>
                                    @error('support_required')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- File Count Selection -->
                            <div class="rounded-xl border border-gray-100 shadow-sm overflow-visible relative" data-section-card>
                                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                    <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">9</span>
                                    <h3 class="font-semibold text-gray-800 text-sm">Number of Files to Upload</h3>
                                </div>
                                <div class="px-5 py-4">
                                <div class="custom-select custom-select-dropup w-full" data-custom-select="max_files" data-dropup="true">
                                    <select
                                        id="max_files"
                                        name="max_files"
                                        class="custom-select-native"
                                        required
                                    >
                                        <option value="" selected disabled hidden>Select number of files</option>
                                        <option value="1">1 File</option>
                                        <option value="2">2 Files</option>
                                        <option value="3">3 Files</option>
                                    </select>
                                    <button type="button" class="custom-select-trigger input-field w-full px-4 py-2.5 text-gray-700">
                                        <span class="custom-select-label">Select number of files</span>
                                        <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="custom-select-menu">
                                        <button type="button" class="custom-select-option" data-select-value="1">1 File</button>
                                        <button type="button" class="custom-select-option" data-select-value="2">2 Files</button>
                                        <button type="button" class="custom-select-option" data-select-value="3">3 Files</button>
                                    </div>
                                </div>
                                    <p class="text-xs text-gray-400 mt-1">Select how many files you plan to upload for this objective</p>
                                    @error('max_files')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="mt-2 w-full py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm transition shadow-sm">
                                Add Objective
                            </button>
                        </form>
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
const predefinedObjectives = @json($predefinedObjectives);
const adminObjectives = @json($adminObjectives->map(function($obj) {
    return [
        'objective' => $obj->objective,
        'action_plan' => $obj->action_plan
    ];
}));

function updateActionPlan() {
    const objectiveSelect = document.getElementById('objective');
    const actionPlanTextarea = document.getElementById('action_plan');
    const customObjectiveContainer = document.getElementById('custom_objective_container');
    const customObjectiveInput = document.getElementById('custom_objective');

    const selectedObjective = objectiveSelect.value;

    if (selectedObjective === 'Other') {
        customObjectiveContainer.style.display = 'block';
        actionPlanTextarea.value = '';
        actionPlanTextarea.placeholder = 'Describe your action plan for this objective...';
        customObjectiveInput.required = true;
        return;
    }

    customObjectiveContainer.style.display = 'none';
    customObjectiveInput.required = false;
    actionPlanTextarea.placeholder = 'Describe your action plan...';

    if (selectedObjective && predefinedObjectives[selectedObjective]) {
        actionPlanTextarea.value = predefinedObjectives[selectedObjective];
        return;
    }

    const adminObjective = adminObjectives.find(obj => obj.objective === selectedObjective);
    if (adminObjective) {
        actionPlanTextarea.value = adminObjective.action_plan;
        return;
    }

    actionPlanTextarea.value = '';
}

function initCustomSelects() {
    const wrappers = document.querySelectorAll('[data-custom-select]');
    wrappers.forEach((wrapper) => {
        const select = wrapper.querySelector('select');
        if (!select) {
            return;
        }

        const trigger = wrapper.querySelector('.custom-select-trigger');
        const label = wrapper.querySelector('.custom-select-label');
        const options = wrapper.querySelectorAll('[data-select-value]');
        const placeholder = label ? label.textContent.trim() : '';

        const updateLabel = () => {
            const selected = select.options[select.selectedIndex];
            label.textContent = selected && selected.textContent.trim()
                ? selected.textContent.trim()
                : placeholder;
        };

        const sectionCard = wrapper.closest('[data-section-card]');

        const closeMenu = () => {
            wrapper.classList.remove('open');
            if (sectionCard) sectionCard.style.zIndex = '';
        };

        trigger.addEventListener('click', () => {
            const isOpening = !wrapper.classList.contains('open');
            if (isOpening) {
                document.querySelectorAll('[data-custom-select].open').forEach(other => {
                    if (other !== wrapper) {
                        other.classList.remove('open');
                        const otherCard = other.closest('[data-section-card]');
                        if (otherCard) otherCard.style.zIndex = '';
                    }
                });
                if (sectionCard) sectionCard.style.zIndex = '50';
                const isHardDropup = wrapper.dataset.dropup === 'true';
                if (!isHardDropup) {
                    const rect = wrapper.getBoundingClientRect();
                    const spaceBelow = window.innerHeight - rect.bottom;
                    const spaceAbove = rect.top;
                    const optionCount = wrapper.querySelectorAll('[data-select-value]').length;
                    const estimatedHeight = Math.min(240, optionCount * 38 + 12);
                    if (spaceBelow < estimatedHeight + 8 && spaceAbove > spaceBelow) {
                        wrapper.classList.add('custom-select-dropup');
                    } else {
                        wrapper.classList.remove('custom-select-dropup');
                    }
                }
            } else {
                if (sectionCard) sectionCard.style.zIndex = '';
            }
            wrapper.classList.toggle('open');
        });

        options.forEach((option) => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-select-value') || '';
                select.value = value;
                select.dispatchEvent(new Event('change'));
                updateLabel();
                closeMenu();
            });
        });

        document.addEventListener('click', (event) => {
            if (!wrapper.contains(event.target)) {
                closeMenu();
            }
        });

        updateLabel();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const alertPopup = document.getElementById('alert-popup');
    if (alertPopup) {
        setTimeout(() => {
            alertPopup.classList.add('alert-hidden');
        }, 2000);
    }

    initCustomSelects();

    // Pre-select objective coming from the query string (e.g., from Graduate Studies modal)
    // but do NOT auto-fill the action plan - faculty member must enter their own
    const preSelected = new URLSearchParams(window.location.search).get('objective');
    if (preSelected) {
        const sel = document.getElementById('objective');
        if (sel) {
            sel.value = preSelected;
            // Update label without triggering updateActionPlan (so action plan is NOT auto-filled)
            document.querySelectorAll('[data-custom-select]').forEach(wrapper => {
                const wrapSel = wrapper.querySelector('select');
                const lbl     = wrapper.querySelector('.custom-select-label');
                if (wrapSel && lbl && wrapSel.id === 'objective') {
                    const opt = wrapSel.options[wrapSel.selectedIndex];
                    if (opt && opt.textContent.trim()) lbl.textContent = opt.textContent.trim();
                }
            });

            if (preSelected === 'Other') {
                sel.dispatchEvent(new Event('change'));
            }
        }
    }
});
</script>
