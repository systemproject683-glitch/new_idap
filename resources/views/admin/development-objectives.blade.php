<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Objectives Management - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        .sidebar {
            background-color: #585858;
        }
        .sidebar-item:hover {
            background-color: #e55a2b;
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
        .admin-badge {
            background-color: #ff6b35;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('admin.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Development Objectives Management</h1>
                    <p class="text-gray-600 mt-2">Create development objectives that will be available to all faculty members</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Add Objective Form -->
                <div class="card mb-8">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Add New Development Objective</h2>
                        <p class="text-sm text-gray-600 mt-1">These objectives will be available to all faculty members</p>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.development-objectives.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Objective Field -->
                                <div class="mb-6">
                                    <label for="objective" class="block text-gray-700 text-sm font-medium mb-2">
                                        Development Objective/Target
                                    </label>
                                    <input 
                                        type="text" 
                                        id="objective" 
                                        name="objective" 
                                        class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter development objective"
                                        required
                                    >
                                    @error('objective')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Action Plan Field -->
                                <div class="mb-6">
                                    <label for="action_plan" class="block text-gray-700 text-sm font-medium mb-2">
                                        Action Plan
                                    </label>
                                    <textarea 
                                        id="action_plan" 
                                        name="action_plan" 
                                        class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                        rows="3"
                                        placeholder="Describe the action plan for this objective"
                                        required
                                    ></textarea>
                                    @error('action_plan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Max Files Field -->
                                <div class="mb-6">
                                    <label for="max_files" class="block text-gray-700 text-sm font-medium mb-2">
                                        Maximum Files per Faculty Member
                                    </label>
                                    <select 
                                        id="max_files" 
                                        name="max_files" 
                                        class="input-field w-full px-4 py-3 text-gray-700"
                                        required
                                    >
                                        <option value="1">1 File</option>
                                        <option value="2">2 Files</option>
                                        <option value="3">3 Files</option>
                                        <option value="4">4 Files</option>
                                        <option value="5">5 Files</option>
                                        <option value="6">6 Files</option>
                                        <option value="7">7 Files</option>
                                        <option value="8">8 Files</option>
                                        <option value="9">9 Files</option>
                                        <option value="10">10 Files</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Maximum number of files faculty members can upload for this objective
                                    </p>
                                    @error('max_files')
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

                <!-- Admin Objectives List -->
                <div class="card">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">Available Development Objectives</h2>
                        <p class="text-sm text-gray-600 mt-1">These objectives are currently available to all faculty members</p>
                    </div>
                    <div class="p-6">
                        @if($adminObjectives->count() > 0)
                            <div class="space-y-4">
                                @foreach($adminObjectives as $objective)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h3 class="text-lg font-semibold text-gray-800">{{ $objective->objective }}</h3>
                                                    <span class="admin-badge">Admin Created</span>
                                                </div>
                                                <p class="text-gray-600 mb-3">{{ $objective->action_plan }}</p>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-sm text-gray-500">
                                                        Created: {{ $objective->created_at->format('M d, Y') }}
                                                    </span>
                                                    <span class="text-sm text-blue-600">
                                                        Available to all faculty members
                                                    </span>
                                                    <span class="text-sm text-orange-600 font-medium">
                                                        Max Files: {{ $objective->max_files }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex gap-2 ml-4">
                                                <!-- Delete Button -->
                                                <form method="POST" action="{{ route('admin.development-objectives.destroy', $objective->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this objective? This will remove it from all faculty members.')">
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
        </div>
    </div>
</body>
</html>
