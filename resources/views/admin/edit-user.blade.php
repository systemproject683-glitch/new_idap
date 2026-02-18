<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - IDAP System</title>
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
        .input-field {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }
        .input-field:focus {
            border-color: #ff6b35;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
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
                <h1 class="text-2xl font-bold text-gray-800 mt-0">Edit User</h1>
                <p class="text-gray-600 mt-1 mb-0 leading-tight">Update user information for {{ $user->first_name }} {{ $user->last_name }}</p>
            </div>
            <div class="page-header-spacer"></div>
            <div class="px-5">

            <!-- Form Card -->
            <div class="card max-w-2xl">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.update.user', $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Name Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <!-- First Name Field -->
                            <div>
                                <label for="first_name" class="block text-gray-700 text-sm font-medium mb-2">
                                    First Name
                                </label>
                                <input 
                                    type="text" 
                                    id="first_name" 
                                    name="first_name" 
                                    class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter first name"
                                    value="{{ old('first_name', $user->first_name) }}"
                                    required
                                >
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Middle Name Field -->
                            <div>
                                <label for="middle_name" class="block text-gray-700 text-sm font-medium mb-2">
                                    Middle Name
                                </label>
                                <input 
                                    type="text" 
                                    id="middle_name" 
                                    name="middle_name" 
                                    class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter middle name (optional)"
                                    value="{{ old('middle_name', $user->middle_name) }}"
                                >
                                @error('middle_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Last Name Field -->
                            <div>
                                <label for="last_name" class="block text-gray-700 text-sm font-medium mb-2">
                                    Last Name
                                </label>
                                <input 
                                    type="text" 
                                    id="last_name" 
                                    name="last_name" 
                                    class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter last name"
                                    value="{{ old('last_name', $user->last_name) }}"
                                    required
                                >
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Email Field -->
                        <div class="mb-6">
                            <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                                Email Address
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                placeholder="Enter user's email address"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Department Field -->
                        <div class="mb-6">
                            <label for="department" class="block text-gray-700 text-sm font-medium mb-2">
                                Department
                            </label>
                            <select 
                                id="department" 
                                name="department" 
                                class="input-field w-full px-4 py-3 text-gray-700"
                                required
                                onchange="updateRoleOptions()"
                            >
                                <option value="">Select Department</option>
                                <option value="DAFE" {{ old('department', $user->department) == 'DAFE' ? 'selected' : '' }}>
                                    Department of Agriculture and Food Engineering (DAFE)
                                </option>
                                <option value="DCEA" {{ old('department', $user->department) == 'DCEA' ? 'selected' : '' }}>
                                    Department of Civil Engineering (DCEA)
                                </option>
                                <option value="DCEEE" {{ old('department', $user->department) == 'DCEEE' ? 'selected' : '' }}>
                                    Department of Computer, Electronics, and Electrical Engineering (DCEEE)
                                </option>
                                <option value="DIET" {{ old('department', $user->department) == 'DIET' ? 'selected' : '' }}>
                                    Department of Industrial Engineering and Technology (DIET)
                                </option>
                                <option value="DIT" {{ old('department', $user->department) == 'DIT' ? 'selected' : '' }}>
                                    Department of Information Technology (DIT)
                                </option>
                            </select>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Role Field -->
                        <div class="mb-6">
                            <label for="role" class="block text-gray-700 text-sm font-medium mb-2">
                                Role
                            </label>
                            <select 
                                id="role" 
                                name="role" 
                                class="input-field w-full px-4 py-3 text-gray-700"
                                required
                            >
                                <option value="">Select Role</option>
                                <option value="faculty" {{ old('role', $user->role) == 'faculty' ? 'selected' : '' }}>
                                    Faculty Member
                                </option>
                                <option value="chairperson" {{ old('role', $user->role) == 'chairperson' ? 'selected' : '' }}>
                                    Chairperson
                                </option>
                            </select>
                            @error('role')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Field (Optional) -->
                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                                Password <span class="text-gray-500 text-xs">(Leave blank to keep current password)</span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                placeholder="Enter new password (optional)"
                            >
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Confirmation -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                                Confirm Password <span class="text-gray-500 text-xs">(Only if changing password)</span>
                            </label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                placeholder="Confirm new password"
                            >
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex gap-4">
                            <button type="submit" class="btn-primary text-white px-6 py-3 rounded transition">
                                Update User
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-400 transition">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
// Store existing chairpersons by department (excluding current user)
const existingChairpersons = {!! \App\Models\User::where('role', 'chairperson')->where('id', '!=', $user->id)->pluck('department')->toJson() !!};
const currentDepartment = '{{ $user->department }}';
const currentRole = '{{ $user->role }}';
const firstName = '{{ $user->first_name }}';
const lastName = '{{ $user->last_name }}';

function updateRoleOptions() {
    const department = document.getElementById('department').value;
    const roleSelect = document.getElementById('role');
    const chairpersonOption = roleSelect.querySelector('option[value="chairperson"]');
    
    // Enable/disable chairperson option based on existing chairpersons
    if (department && existingChairpersons.includes(department)) {
        // If current user is the chairperson of this department, allow keeping the role
        if (department === currentDepartment && currentRole === 'chairperson') {
            chairpersonOption.disabled = false;
            chairpersonOption.textContent = 'Chairperson (Current)';
        } else {
            chairpersonOption.disabled = true;
            chairpersonOption.textContent = 'Chairperson (Already Assigned)';
        }
    } else {
        chairpersonOption.disabled = false;
        chairpersonOption.textContent = 'Chairperson';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRoleOptions();
});
</script>
