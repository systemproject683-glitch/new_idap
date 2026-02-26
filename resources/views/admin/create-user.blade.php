<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - IDAP System</title>
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
            border-radius: 14px;
            background-color: #ffedd5;
        }
        .input-field:focus {
            border-color: #ff6b35;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
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
                <h1 class="text-2xl font-bold text-gray-800 mt-0">Create New User</h1>
                <p class="text-gray-600 mt-1 mb-0 leading-tight">Add a new user to the IDAP system</p>
            </div>
            <div class="page-header-spacer"></div>
            <div class="px-5">

            <!-- Form Card -->
            <div class="card max-w-3xl mx-auto">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Create New User</h2>
                    <p class="text-gray-600 text-sm mt-1">Fill in the information below to add a new user to the system</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.store.user') }}">
                        @csrf
                        
                        <!-- Step 1: Personal Information -->
                        <div class="form-step">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="step-badge">1</span>
                                <h3 class="step-title">Personal Information</h3>
                            </div>
                            
                            <!-- Name Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <!-- First Name Field -->
                                <div>
                                    <label for="first_name" class="block text-gray-700 text-sm font-medium mb-2">
                                        First Name
                                    </label>
                                    <input 
                                        type="text" 
                                        id="first_name" 
                                        name="first_name" 
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter first name"
                                        value="{{ old('first_name') }}"
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
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Optional"
                                        value="{{ old('middle_name') }}"
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
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter last name"
                                        value="{{ old('last_name') }}"
                                        required
                                    >
                                    @error('last_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                                    Email Address
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="user@example.com"
                                    value="{{ old('email') }}"
                                    required
                                >
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="step-helper">Enter the user's basic information and email address</p>
                        </div>
                        
                        <!-- Step 2: Department and Role -->
                        <div class="form-step">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="step-badge">2</span>
                                <h3 class="step-title">Department and Role</h3>
                            </div>
                            
                            <!-- Department Field -->
                            <div class="mb-4">
                                <label for="department" class="block text-gray-700 text-sm font-medium mb-2">
                                    Department
                                </label>
                                <select 
                                    id="department" 
                                    name="department" 
                                    class="input-field w-full px-4 py-2.5 text-gray-700"
                                    required
                                    onchange="updateRoleOptions()"
                                >
                                    <option value="">Select Department</option>
                                    <option value="DAFE" {{ old('department') == 'DAFE' ? 'selected' : '' }}>
                                        Department of Agriculture and Food Engineering (DAFE)
                                    </option>
                                    <option value="DCEA" {{ old('department') == 'DCEA' ? 'selected' : '' }}>
                                        Department of Civil Engineering (DCEA)
                                    </option>
                                    <option value="DCEEE" {{ old('department') == 'DCEEE' ? 'selected' : '' }}>
                                        Department of Computer, Electronics, and Electrical Engineering (DCEEE)
                                    </option>
                                    <option value="DIET" {{ old('department') == 'DIET' ? 'selected' : '' }}>
                                        Department of Industrial Engineering and Technology (DIET)
                                    </option>
                                    <option value="DIT" {{ old('department') == 'DIT' ? 'selected' : '' }}>
                                        Department of Information Technology (DIT)
                                    </option>
                                </select>
                                @error('department')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Role and Academic Rank Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Role Field -->
                                <div>
                                    <label for="role" class="block text-gray-700 text-sm font-medium mb-2">
                                        Role
                                    </label>
                                    <select 
                                        id="role" 
                                        name="role" 
                                        class="input-field w-full px-4 py-2.5 text-gray-700"
                                        required
                                    >
                                        <option value="">Select Role</option>
                                        <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>
                                            Faculty Member
                                        </option>
                                        <option value="chairperson" {{ old('role') == 'chairperson' ? 'selected' : '' }}>
                                            Chairperson
                                        </option>
                                    </select>
                                    @error('role')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Academic Rank Field -->
                                <div>
                                    <label for="academic_rank" class="block text-gray-700 text-sm font-medium mb-2">
                                        Academic Rank
                                    </label>
                                    <select 
                                        id="academic_rank" 
                                        name="academic_rank" 
                                        class="input-field w-full px-4 py-2.5 text-gray-700"
                                        style="display: none;"
                                    >
                                        <option value="">Select Academic Rank</option>
                                        <option value="University Professor" {{ old('academic_rank') == 'University Professor' ? 'selected' : '' }}>University Professor</option>
                                        <option value="Instructor 1" {{ old('academic_rank') == 'Instructor 1' ? 'selected' : '' }}>Instructor 1</option>
                                    </select>
                                    <input 
                                        type="text" 
                                        id="academic_rank_text" 
                                        name="academic_rank" 
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="e.g., Professor, Associate Professor"
                                        value="{{ old('academic_rank') }}"
                                    >
                                    @error('academic_rank')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="regularized_at" class="block text-gray-700 text-sm font-medium mb-2">
                                    Regularization Date
                                </label>
                                <input
                                    type="date"
                                    id="regularized_at"
                                    name="regularized_at"
                                    class="input-field w-full px-4 py-2.5 text-gray-700"
                                    value="{{ old('regularized_at') }}"
                                >
                                @error('regularized_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="step-helper">Assign the user to a department and their role within it</p>
                        </div>
                        
                        <!-- Step 3: Account Security -->
                        <div class="form-step">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="step-badge">3</span>
                                <h3 class="step-title">Account Security</h3>
                            </div>
                            
                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                                    Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter secure password"
                                    required
                                >
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                                    Confirm Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Re-enter password"
                                    required
                                >
                            </div>
                            <p class="step-helper">Create a strong password for the user's account</p>
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex gap-4 pt-4">
                            <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-medium transition">
                                Create User
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-400 transition inline-block">
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
// Store existing chairpersons by department
const existingChairpersons = @json(\App\Models\User::where('role', 'chairperson')->pluck('department')->toArray());

function updateRoleOptions() {
    const department = document.getElementById('department').value;
    const roleSelect = document.getElementById('role');
    const chairpersonOption = roleSelect.querySelector('option[value="chairperson"]');
    
    // Reset role selection
    roleSelect.value = '';
    
    // Enable/disable chairperson option based on existing chairpersons
    if (department && existingChairpersons.includes(department)) {
        chairpersonOption.disabled = true;
        chairpersonOption.textContent = 'Chairperson (Already Assigned)';
    } else {
        chairpersonOption.disabled = false;
        chairpersonOption.textContent = 'Chairperson';
    }
}

function updateAcademicRankField() {
    const roleSelect = document.getElementById('role');
    const academicRankSelect = document.getElementById('academic_rank');
    const academicRankText = document.getElementById('academic_rank_text');
    
    if (roleSelect.value === 'faculty') {
        // Show dropdown for faculty
        academicRankSelect.style.display = 'block';
        academicRankSelect.removeAttribute('disabled');
        academicRankSelect.name = 'academic_rank';
        academicRankText.style.display = 'none';
        academicRankText.setAttribute('disabled', 'disabled');
        academicRankText.name = 'academic_rank_text';
    } else {
        // Show text input for chairperson or when no role selected
        academicRankSelect.style.display = 'none';
        academicRankSelect.setAttribute('disabled', 'disabled');
        academicRankSelect.name = 'academic_rank_disabled';
        academicRankText.style.display = 'block';
        academicRankText.removeAttribute('disabled');
        academicRankText.name = 'academic_rank';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRoleOptions();
    updateAcademicRankField();
    
    // Add event listener for role changes
    document.getElementById('role').addEventListener('change', updateAcademicRankField);
});
</script>
