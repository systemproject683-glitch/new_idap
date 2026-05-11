<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Objectives - L&D Plan</title>
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
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-in-progress {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Main Content (Full Width) -->
    <div class="container mx-auto px-8 py-8 max-w-6xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Development Objectives</h1>
            <p class="text-gray-600 mt-2">Manage your individual development action plan objectives</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Add Objective Form -->
        <div class="card mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Add New Objective</h2>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('development-objectives.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Objective Dropdown -->
                        <div class="mb-6">
                            <label for="objective" class="block text-gray-700 text-sm font-medium mb-2">
                                Development Objective/Target
                            </label>
                            <select 
                                id="objective" 
                                name="objective" 
                                class="input-field w-full px-4 py-3 text-gray-700"
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
                            </select>
                            @error('objective')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Action Plan -->
                        <div class="mb-6">
                            <label for="action_plan" class="block text-gray-700 text-sm font-medium mb-2">
                                Action Plan
                            </label>
                            <textarea 
                                id="action_plan" 
                                name="action_plan" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                rows="3"
                                placeholder="Describe your action plan..."
                                required
                            ></textarea>
                            @error('action_plan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="btn-primary text-white px-6 py-3 rounded transition">
                            Add Objective
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Objectives List -->
        <div class="card">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Your Development Objectives - L&D Plan</h2>
            </div>
            <div class="p-6">
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
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <!-- Status Update Buttons -->
                                        @if($objective->status !== 'in_progress')
                                            <form method="POST" action="{{ route('development-objectives.update-status', $objective->id) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="in_progress">
                                                <button type="submit" class="btn-warning text-white px-3 py-1 rounded text-sm">
                                                    Start
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($objective->status !== 'completed')
                                            <form method="POST" action="{{ route('development-objectives.update-status', $objective->id) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn-success text-white px-3 py-1 rounded text-sm">
                                                    Complete
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <form method="POST" action="{{ route('development-objectives.destroy', $objective->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this objective?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger text-white px-3 py-1 rounded text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No development objectives found. Add your first objective above!</p>
                    </div>
                @endif
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
    
    const selectedObjective = objectiveSelect.value;
    
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
</script>
