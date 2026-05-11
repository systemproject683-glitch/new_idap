<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - L&D Plan</title>
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
            background-color: #fff7ed;
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
                        <p class="text-gray-600 text-base">Admin / <span class="text-orange-600 font-semibold">Create User</span></p>
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


            @if(session('success'))
                <div id="successAlert" class="flex items-center justify-between bg-green-100 text-green-800 px-4 py-3 rounded-xl mb-4">
                    <span>{{ session('success') }}</span>
                    
                    <button onclick="document.getElementById('successAlert').style.display='none'" 
                            class="ml-4 text-green-700 font-bold">
                        ✕
                    </button>
                </div>
            @endif
            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl mx-auto overflow-hidden">
                <!-- Modal-style header -->
                <div class="flex items-center gap-3 px-8 pt-8 pb-6 border-b border-gray-100">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Create New User</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Fill in the details to add a new user to the system</p>
                    </div>
                </div>
                <div class="px-8 py-6 space-y-4">
                    <form method="POST" action="{{ route('admin.store.user') }}">
                        @csrf

                        <!-- Section 1: Personal Information -->
                        <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">1</span>
                                <h3 class="font-semibold text-gray-800 text-sm">Personal Information</h3>
                            </div>
                            <div class="px-5 py-4 space-y-4">
                                <!-- Name Fields -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                        <input type="text" id="first_name" name="first_name"
                                            class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                            placeholder="Enter first name" value="{{ old('first_name') }}" required>
                                        @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                        <input type="text" id="middle_name" name="middle_name"
                                            class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                            placeholder="Optional" value="{{ old('middle_name') }}">
                                        @error('middle_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                        <input type="text" id="last_name" name="last_name"
                                            class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                            placeholder="Enter last name" value="{{ old('last_name') }}" required>
                                        @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <!-- Email Field -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                    <input type="email" id="email" name="email"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="user@example.com" value="{{ old('email') }}" required>
                                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <p class="text-xs text-gray-400">Enter the user's basic information and email address</p>
                            </div>
                        </div>

                        <!-- Section 2: Department and Role -->
                        <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">2</span>
                                <h3 class="font-semibold text-gray-800 text-sm">Department and Role</h3>
                            </div>
                            <div class="px-5 py-4 space-y-4">
                                <!-- Department Field -->
                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                    <select id="department" name="department"
                                        class="input-field w-full px-4 py-2.5 text-gray-700"
                                        required onchange="updateRoleOptions()">
                                        <option value="">Select Department</option>
                                        <option value="DAFE" {{ old('department') == 'DAFE' ? 'selected' : '' }}>Department of Agriculture and Food Engineering (DAFE)</option>
                                        <option value="DCEA" {{ old('department') == 'DCEA' ? 'selected' : '' }}>Department of Civil Engineering (DCEA)</option>
                                        <option value="DCEEE" {{ old('department') == 'DCEEE' ? 'selected' : '' }}>Department of Computer, Electronics, and Electrical Engineering (DCEEE)</option>
                                        <option value="DIET" {{ old('department') == 'DIET' ? 'selected' : '' }}>Department of Industrial Engineering and Technology (DIET)</option>
                                        <option value="DIT" {{ old('department') == 'DIT' ? 'selected' : '' }}>Department of Information Technology (DIT)</option>
                                    </select>
                                    @error('department')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <!-- Role and Academic Rank -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                        <select id="role" name="role"
                                            class="input-field w-full px-4 py-2.5 text-gray-700" required>
                                            <option value="">Select Role</option>
                                            <option value="faculty" {{ old('role') == 'faculty' ? 'selected' : '' }}>Faculty Member</option>
                                            <option value="chairperson" {{ old('role') == 'chairperson' ? 'selected' : '' }}>Chairperson</option>
                                        </select>
                                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="academic_rank" class="block text-sm font-medium text-gray-700 mb-2">Academic Rank</label>
                                        <select id="academic_rank" name="academic_rank"
                                            class="input-field w-full px-4 py-2.5 text-gray-700"
                                            style="display: none;">
                                            <option value="">Select Academic Rank</option>
                                            <option value="University Professor" {{ old('academic_rank') == 'University Professor' ? 'selected' : '' }}>University Professor</option>
                                            <option value="Instructor 1" {{ old('academic_rank') == 'Instructor 1' ? 'selected' : '' }}>Instructor 1</option>
                                        </select>
                                        <input type="text" id="academic_rank_text" name="academic_rank"
                                            class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                            placeholder="e.g., Professor, Associate Professor"
                                            value="{{ old('academic_rank') }}">
                                        @error('academic_rank')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                                <!-- Regularization Date -->
                               <div>
                                    <label for="regularized_at" class="block text-sm font-medium text-gray-700 mb-2">
                                        Regularization Date
                                    </label>

                                    <input 
                                        type="date" 
                                        id="regularized_at" 
                                        name="regularized_at"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 cursor-pointer"
                                        value="{{ old('regularized_at') }}"
                                        onclick="this.showPicker && this.showPicker()"
                                    >

                                    @error('regularized_at')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <p class="text-xs text-gray-400">Assign the user to a department and their role within it</p>
                            </div>
                        </div>

                        <!-- Section 3: Account Security -->
                        <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                                <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">3</span>
                                <h3 class="font-semibold text-gray-800 text-sm">Account Security</h3>
                            </div>
                            <div class="px-5 py-4 space-y-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" id="password" name="password"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter secure password" required>
                                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Re-enter password" required>
                                </div>
                                <p class="text-xs text-gray-400">Create a strong password for the user's account</p>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-2">
                            <button type="submit" class="flex-1 btn-primary text-white py-3 rounded-xl font-semibold transition">
                                Create User
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition inline-block">
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
    setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 5000); 
</script>

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
