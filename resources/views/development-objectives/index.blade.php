<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
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
                    <h1 class="text-2xl font-bold text-gray-800 mt-0">Development Objectives / Target</h1>
                    <p class="text-gray-600 mt-1 mb-0 leading-tight">Manage your individual development action plan objectives</p>
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

                <!-- Two-Column Layout -->
                <table style="width: 100%; border-collapse: separate; border-spacing: 20px 0;">
                    <tr>
                        <td class="objectives-left-cell" style="width: 60%;">
                            <!-- Left Section - Objectives List -->
                            <div class="card objectives-list-card">
                                <div class="p-6 border-b border-gray-200">
                                    <h2 class="text-xl font-semibold text-gray-800">Your Development Objectives</h2>
                                </div>
                                <div class="p-6 scrollable-card-body">
                                @if($objectives->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($objectives as $objective)
                                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $objective->objective }}</h3>
                                                        <p class="text-gray-600 mb-3">{{ $objective->action_plan }}</p>
                                                        
                                                        <div class="flex items-center gap-4">
                                                            <span class="status-badge status-{{ str_replace('_', '-', $objective->status) }}">
                                                                {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                                            </span>
                                                            <span class="text-sm text-gray-500">
                                                                Created: {{ $objective->created_at->format('M d, Y') }}
                                                            </span>
                                                        </div>
                                                        
                                                        <!-- File Upload Section -->
                                                        <div class="mb-3">
                                                            @if($objective->max_files > 0)
                                                                @php
                                                                    $fileCount = $objective->files->count();
                                                                    $approvedFileCount = $objective->files->where('verification_status', 'approved')->count();
                                                                    $percentage = ($approvedFileCount / $objective->max_files) * 100;
                                                                @endphp
                                                                
                                                                <!-- Progress Bar -->
                                                                <div class="mb-3">
                                                                    <div class="flex justify-between items-center mb-1">
                                                                        <label for="file_{{ $objective->id }}" class="block text-gray-700 text-sm font-medium">
                                                                            Upload File/Certificate 
                                                                        </label>
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="text-xs text-gray-500">
                                                                                {{ $approvedFileCount }}/{{ $objective->max_files }} approved files
                                                                            </span>
                                                                            @if($fileCount > $approvedFileCount)
                                                                                <span class="text-xs text-orange-500">
                                                                                    ({{ $fileCount - $approvedFileCount }} pending)
                                                                                </span>
                                                                            @endif
                                                                            <span class="text-xs font-medium
                                                                                @if($percentage >= 100) text-orange-700
                                                                                @elseif($percentage >= 75) text-orange-600
                                                                                @elseif($percentage >= 50) text-orange-500
                                                                                @else text-orange-400
                                                                                @endif">
                                                                                {{ round($percentage) }}% Complete
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- Progress Bar -->
                                                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                                        <div class="h-2 rounded-full transition-all duration-300
                                                                            @if($percentage >= 100) bg-orange-500
                                                                            @elseif($percentage >= 75) bg-orange-400
                                                                            @elseif($percentage >= 50) bg-orange-300
                                                                            @else bg-orange-200
                                                                            @endif"
                                                                             style="width: {{ min($percentage, 100) }}%">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @else
                                                                    <label for="file_{{ $objective->id }}" class="block text-gray-700 text-sm font-medium mb-2">
                                                                        Upload File/Certificate (Optional)
                                                                    </label>
                                                                @endif
                                                                
                                                                <form method="POST" action="{{ route('development-objectives.upload-file', $objective->id) }}" 
                                                                      enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input 
                                                                        type="file" 
                                                                        id="file_{{ $objective->id }}" 
                                                                        name="file" 
                                                                        class="input-field w-full px-4 py-3 text-gray-700 mb-2"
                                                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                                        @if($objective->max_files > 0 && $objective->files->count() >= $objective->max_files) disabled @endif
                                                                    >
                                                                    <p class="text-xs text-gray-500 mb-2">
                                                                        Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 2MB)
                                                                    </p>
                                                                    <button type="submit" class="btn-primary text-white px-4 py-2 rounded text-sm" 
                                                                            @if($objective->max_files > 0 && $objective->files->count() >= $objective->max_files) disabled @endif>
                                                                        Upload File
                                                                    </button>
                                                                    @if($objective->max_files > 0 && $objective->files->count() >= $objective->max_files)
                                                                        <p class="text-xs text-red-500 mt-1">
                                                                            Maximum file limit reached ({{ $objective->max_files }} files)
                                                                        </p>
                                                                    @endif
                                                                    @error('file')
                                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                                    @enderror
                                                                </form>
                                                        </div>
                                                        
                                                        <!-- File Display -->
                                                            @if($objective->files->count() > 0)
                                                                <div class="mb-3">
                                                                    <div class="space-y-2">
                                                                        @foreach($objective->files as $file)
                                                                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded border">
                                                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                                </svg>
                                                                                                          <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                                                                              target="_blank" 
                                                                                                              class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                                                                                    {{ $file->file_name }}
                                                                                </a>
                                                                                
                                                                                <!-- Verification Status Badge -->
                                                                                @if($file->verification_status === 'pending')
                                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                                                                        Pending Verification
                                                                                    </span>
                                                                                @elseif($file->verification_status === 'approved')
                                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-200 text-orange-900">
                                                                                        ✓ Approved
                                                                                    </span>
                                                                                @elseif($file->verification_status === 'rejected')
                                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                                                        ✗ Rejected
                                                                                    </span>
                                                                                @endif
                                                                                
                                                                                <!-- Rejection Reason -->
                                                                                @if($file->verification_status === 'rejected' && $file->rejection_reason)
                                                                                    <div class="text-xs text-red-600 mt-1">
                                                                                        Reason: {{ $file->rejection_reason }}
                                                                                    </div>
                                                                                @endif
                                                                                
                                                                                <!-- Delete Button - Only for rejected and pending files -->
                                                                                @if($file->verification_status !== 'approved')
                                                                                    <form method="POST" action="{{ route('development-objectives.delete-file', $objective->id) }}" 
                                                                                          class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <input type="hidden" name="file_id" value="{{ $file->id }}">
                                                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm" title="Delete file">
                                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                                            </svg>
                                                                                        </button>
                                                                                    </form>
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-gray-500">No development objectives found. Add your first objective using the form!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td style="width: 40%; vertical-align: top;">
                        <div class="right-column-sticky">
                        <!-- Right Section - Add New Objective Form -->
                        <div class="card">
                            <div class="p-6 border-b border-gray-200">
                                <h2 id="add-objective-header" class="text-xl font-semibold text-gray-800">Add New Objective</h2>
                            </div>
                            <div class="p-6">
                                <form method="POST" action="{{ route('development-objectives.store') }}">
                                    @csrf
                                    <!-- Objective Dropdown -->
                                    <div class="mb-5">
                                        <label for="objective" class="block text-gray-700 text-sm font-medium mb-2">
                                            Development Objective/Target
                                        </label>
                                        <div class="custom-select" data-custom-select="objective">
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
                                            <button type="button" class="custom-select-trigger input-field w-full px-4 py-3 text-gray-700">
                                                <span class="custom-select-label">Select Objective</span>
                                                <svg class="w-4 h-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.188l3.71-3.96a.75.75 0 111.08 1.04l-4.24 4.52a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            <div class="custom-select-menu">
                                                <button type="button" class="custom-select-option" data-select-value="">Select Objective</button>
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
                                            class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                            placeholder="Enter your custom objective name..."
                                        >
                                        @error('custom_objective')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Action Plan -->
                                    <div class="mb-5">
                                        <label for="action_plan" class="block text-gray-700 text-sm font-medium mb-2">
                                            Action Plan
                                        </label>
                                        <textarea 
                                            id="action_plan" 
                                            name="action_plan" 
                                            class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                            rows="4"
                                            placeholder="Describe your action plan..."
                                            required
                                        ></textarea>
                                        @error('action_plan')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div>
                                        <button type="submit" class="btn-primary text-white w-full px-6 py-3 rounded transition">
                                            Add Objective
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @php
                            $totalObjectives = $objectives->count();
                            $completedObjectives = $objectives->where('status', 'completed')->count();
                            $inProgressObjectives = $objectives->where('status', 'in_progress')->count();
                        @endphp
                        <div class="card mt-6">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">Objectives Summary</h2>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600">Total Objectives</span>
                                    <span class="text-lg font-semibold text-gray-800">{{ $totalObjectives }}</span>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600">Completed</span>
                                    <span class="text-lg font-semibold text-green-600">{{ $completedObjectives }}</span>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600">In Progress</span>
                                    <span class="text-lg font-semibold text-blue-600">{{ $inProgressObjectives }}</span>
                                </div>
                            </div>
                        </div>
                        </div>
                    </td>
                </tr>
            </table>
            </div>
        </div>
    </div>
</body>
</html>

<script>
// Store predefined objectives with their action plans
const predefinedObjectives = @json($predefinedObjectives);

// Store admin objectives with their action plans
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
    
    // If "Other" is selected, show custom input and clear action plan
    if (selectedObjective === 'Other') {
        customObjectiveContainer.style.display = 'block';
        actionPlanTextarea.value = '';
        actionPlanTextarea.placeholder = 'Describe your action plan for this objective...';
        customObjectiveInput.required = true;
        return;
    }
    
    // Hide custom input for other options
    customObjectiveContainer.style.display = 'none';
    customObjectiveInput.required = false;
    actionPlanTextarea.placeholder = 'Describe your action plan...';
    
    // Check predefined objectives first
    if (selectedObjective && predefinedObjectives[selectedObjective]) {
        actionPlanTextarea.value = predefinedObjectives[selectedObjective];
        return;
    }
    
    // Check admin objectives
    const adminObjective = adminObjectives.find(obj => obj.objective === selectedObjective);
    if (adminObjective) {
        actionPlanTextarea.value = adminObjective.action_plan;
        return;
    }
    
    // Clear if no match found
    actionPlanTextarea.value = '';
}

function initCustomSelect() {
    const select = document.getElementById('objective');
    const wrapper = document.querySelector('[data-custom-select="objective"]');
    if (!select || !wrapper) {
        return;
    }

    const trigger = wrapper.querySelector('.custom-select-trigger');
    const label = wrapper.querySelector('.custom-select-label');
    const menu = wrapper.querySelector('.custom-select-menu');
    const options = wrapper.querySelectorAll('[data-select-value]');

    const updateLabel = () => {
        const selected = select.options[select.selectedIndex];
        label.textContent = selected && selected.textContent.trim()
            ? selected.textContent.trim()
            : 'Select Objective';
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
}

document.addEventListener('DOMContentLoaded', () => {
    const alertPopup = document.getElementById('alert-popup');
    if (!alertPopup) {
        initCustomSelect();
        return;
    }

    setTimeout(() => {
        alertPopup.classList.add('alert-hidden');
    }, 2000);

    initCustomSelect();
});

</script>
