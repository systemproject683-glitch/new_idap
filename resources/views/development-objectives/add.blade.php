<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Objective - IDAP System</title>
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

        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .input-field {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background-color: #ffedd5;
        }
        .input-field:focus {
            border-color: #ff6b35;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
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
            background-color: #ffedd5;
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

        .form-step {
            padding: 8px 0 20px;
        }

        .form-step + .form-step {
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            background-color: #ff6b35;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .step-title {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }

        .step-helper {
            margin-top: 8px;
            font-size: 0.8rem;
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
                    <h1 class="text-2xl font-bold text-gray-800 mt-0">Add Objective</h1>
                    <p class="text-gray-600 mt-1 mb-0 leading-tight">Define your development goals and create an action plan</p>
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

                <div class="card max-w-3xl mx-auto">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Add New Objective</h2>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('development-objectives.store') }}">
                            @csrf
                            <!-- Objective Dropdown -->
                            <div class="form-step">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="step-badge">1</span>
                                    <h3 class="step-title">Development Objective/Target</h3>
                                </div>
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
                                                    <option value="{{ $objective }}">{{ $objective }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        @if($adminObjectives->count() > 0)
                                            @foreach($adminObjectives as $objective)
                                                <option value="{{ $objective->objective }}">{{ $objective->objective }}</option>
                                            @endforeach
                                        @endif
                                        <option value="Other">Other (Specify your own objective)</option>
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
                                @error('objective')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Custom Objective Input (Hidden by default) -->
                            <div id="custom_objective_container" class="mb-5" style="display: none;">
                                <label for="custom_objective" class="block text-gray-700 text-sm font-medium mb-2">
                                    Custom Objective
                                </label>
                                <input
                                    type="text"
                                    id="custom_objective"
                                    name="custom_objective"
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter your custom objective name..."
                                >
                                @error('custom_objective')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Plan -->
                            <div class="form-step">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="step-badge">2</span>
                                    <h3 class="step-title">Action Plan</h3>
                                </div>
                                <textarea
                                    id="action_plan"
                                    name="action_plan"
                                    class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                    rows="3"
                                    placeholder="Describe your action plan..."
                                    required
                                ></textarea>
                                <p class="step-helper">Provide a comprehensive description of your action plan</p>
                                @error('action_plan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Budget Requirement -->
                            <div class="form-step">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="step-badge">3</span>
                                    <h3 class="step-title">Budget Requirement</h3>
                                </div>
                                <input
                                    type="number"
                                    id="budget_requirement"
                                    name="budget_requirement"
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter budget requirement..."
                                    min="0"
                                    step="0.01"
                                >
                                <p class="step-helper">Enter the estimated budget required for this objective</p>
                                @error('budget_requirement')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Target Period -->
                            <div class="form-step">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="step-badge">4</span>
                                    <h3 class="step-title">Target Period</h3>
                                </div>
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
                                <p class="step-helper">Select the quarter when you plan to complete this objective</p>
                                @error('target_period')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Support Required -->
                            <div class="form-step">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="step-badge">5</span>
                                    <h3 class="step-title">Support Required</h3>
                                </div>
                                <input
                                    type="text"
                                    id="support_required"
                                    name="support_required"
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter support required..."
                                >
                                <p class="step-helper">Describe any support or resources needed to achieve this objective</p>
                                @error('support_required')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File Count Selection -->
                            <div class="form-step">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="step-badge">6</span>
                                    <h3 class="step-title">Number of Files to Upload</h3>
                                </div>
                                <div class="custom-select w-full" data-custom-select="max_files">
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
                                <p class="step-helper">Select how many files you plan to upload for this objective</p>
                                @error('max_files')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="w-full">
                                <button type="submit" class="btn-primary text-white w-full px-6 py-2.5 rounded-xl transition">
                                    Add Objective
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        const closeMenu = () => {
            wrapper.classList.remove('open');
        };

        trigger.addEventListener('click', () => {
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
});
</script>
