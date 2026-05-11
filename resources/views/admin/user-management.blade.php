<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - L&D Plan</title>
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
        .btn-primary {
            background-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e55a2b;
        }
        .table-header {
            background-color: #f9fafb;
            border-bottom: 2px solid #ff6b35;
        }
        table thead th {
            background-color: #f9fafb;
            border-bottom: 2px solid #ff6b35;
        }
        table tbody tr {
            transition: all 0.2s ease;
        }
        table tbody tr:hover {
            background-color: #fef3e2;
        }
        .role-badge {
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.5px;
        }
        .role-badge.chairperson {
            color: #ff6b35;
            
        }
        .role-badge.faculty {
            color: #3b82f6;
            
        }
        .action-links a, .action-links button {
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal.show {
            display: block;
        }
        .modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header {
            padding: 32px 32px 24px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .modal-close {
            color: #9ca3af;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background-color: #f3f4f6;
            color: #374151;
        }
        .modal-body {
            padding: 24px 32px 32px;
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
                        <p class="text-gray-600 text-base">Admin / <span class="text-orange-600 font-semibold">User Management</span></p>
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

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="mb-4 flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap items-center gap-3">
                    <!-- Department Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_department" class="text-sm font-medium text-gray-600 whitespace-nowrap">Department:</label>
                        <select id="filter_department" name="department"
                                onchange="this.form.submit()"
                                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent cursor-pointer"
                                style="min-width:150px;">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ $selectedDepartment === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Filter -->
                    <div class="flex items-center gap-2">
                        <label for="filter_role" class="text-sm font-medium text-gray-600 whitespace-nowrap">Role:</label>
                        <select id="filter_role" name="role"
                                onchange="this.form.submit()"
                                class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent cursor-pointer"
                                style="min-width:150px;">
                            <option value="">All Roles</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ $selectedRole === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    @if($selectedDepartment || $selectedRole)
                        <a href="{{ route('admin.users') }}"
                           class="text-sm text-orange-500 hover:text-orange-700 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear filters
                        </a>
                    @endif
                </form>

                <span class="ml-auto text-sm text-gray-500 font-medium">
                    {{ $users->total() }} {{ Str::plural('user', $users->total()) }} found
                </span>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="p-6">
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="table-header">
                                    <tr>
                                        <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Name</th>
                                        <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Email</th>
                                        <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Department</th>
                                        <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Role</th>
                                        <th class="text-left py-2 px-4 font-semibold text-gray-800 text-sm">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="border-b border-gray-100">
                                            <td class="py-4 px-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                                         style="background: linear-gradient(135deg, #FFAA55, #FF6622); box-shadow: 0 2px 6px rgba(0,0,0,.20);">
                                                        <span>{{ strtoupper(substr($user->first_name, 0, 1)) . strtoupper(substr($user->last_name, 0, 1)) }}</span>
                                                    </div>
                                                    <div class="font-medium text-gray-900 text-sm">
                                                        {{ $user->first_name }}<span class="mx-1">{{ $user->middle_name }}</span>{{ $user->last_name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-gray-600 text-sm">{{ $user->email }}</td>
                                            <td class="py-4 px-4">
                                                <div class="text-gray-700 text-sm">
                                                    {{ $user->department ?? 'Not Assigned' }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                @if($user->role === 'chairperson')
                                                    <span class="role-badge chairperson text-xs">
                                                        Chairperson
                                                    </span>
                                                @else
                                                    <span class="role-badge faculty text-xs">
                                                        Faculty Member
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="action-links flex gap-2 text-sm">
                                                    <button 
                                                        onclick="openEditModal({{ $user->id }}, '{{ $user->first_name }}', '{{ $user->middle_name }}', '{{ $user->last_name }}', '{{ $user->email }}', '{{ $user->department }}', '{{ $user->role }}', '{{ $user->academic_rank }}', '{{ optional($user->regularized_at)->format('Y-m-d') }}')" 
                                                        class="text-blue-600 hover:text-blue-800 hover:underline cursor-pointer bg-transparent border-0 p-0"
                                                    >
                                                        Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('admin.delete.user', $user->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 hover:underline">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No users found. <a href="{{ route('admin.create.user') }}" class="text-orange-500 hover:text-orange-600">Add your first user</a>.</p>
                        </div>
                    @endif
                </div>
            </div>

            </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="flex items-center gap-3">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 text-orange-600 flex-shrink-0">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Edit User</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Update the user's information below</p>
                    </div>
                </div>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Personal Information -->
                    <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                            <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">1</span>
                            <h3 class="font-semibold text-gray-800 text-sm">Personal Information</h3>
                        </div>
                        <div class="px-5 py-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <input type="text" id="edit_first_name" name="first_name"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter first name" required>
                                </div>
                                <div>
                                    <label for="edit_middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                    <input type="text" id="edit_middle_name" name="middle_name"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Optional">
                                </div>
                                <div>
                                    <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <input type="text" id="edit_last_name" name="last_name"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="Enter last name" required>
                                </div>
                            </div>
                            <div>
                                <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" id="edit_email" name="email"
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="user@example.com" required>
                            </div>
                            <p class="text-xs text-gray-400">Update the user's basic information and email address</p>
                        </div>
                    </div>

                    <!-- Section 2: Department and Role -->
                    <div class="rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100">
                            <span class="h-7 w-7 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500 font-bold text-xs flex-shrink-0">2</span>
                            <h3 class="font-semibold text-gray-800 text-sm">Department and Role</h3>
                        </div>
                        <div class="px-5 py-4 space-y-4">
                            <div>
                                <label for="edit_department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <select id="edit_department" name="department"
                                    class="input-field w-full px-4 py-2.5 text-gray-700"
                                    required onchange="updateModalRoleOptions()">
                                    <option value="">Select Department</option>
                                    <option value="DAFE">Department of Agriculture and Food Engineering (DAFE)</option>
                                    <option value="DCEA">Department of Civil Engineering (DCEA)</option>
                                    <option value="DCEEE">Department of Computer, Electronics, and Electrical Engineering (DCEEE)</option>
                                    <option value="DIET">Department of Industrial Engineering and Technology (DIET)</option>
                                    <option value="DIT">Department of Information Technology (DIT)</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                    <select id="edit_role" name="role"
                                        class="input-field w-full px-4 py-2.5 text-gray-700" required>
                                        <option value="">Select Role</option>
                                        <option value="faculty">Faculty Member</option>
                                        <option value="chairperson">Chairperson</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="edit_academic_rank" class="block text-sm font-medium text-gray-700 mb-2">Academic Rank</label>
                                    <select id="edit_academic_rank" name="academic_rank"
                                        class="input-field w-full px-4 py-2.5 text-gray-700"
                                        style="display: none;">
                                        <option value="">Select Academic Rank</option>
                                        <option value="University Professor">University Professor</option>
                                        <option value="Instructor 1">Instructor 1</option>
                                    </select>
                                    <input type="text" id="edit_academic_rank_text" name="academic_rank"
                                        class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                        placeholder="e.g., Professor, Associate Professor">
                                </div>
                            </div>
                            <div>
                                <label for="edit_regularized_at" class="block text-sm font-medium text-gray-700 mb-2">Regularization Date</label>
                                <input type="date" id="edit_regularized_at" name="regularized_at"
                                    class="input-field w-full px-4 py-2.5 text-gray-700">
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
                                <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-gray-400 text-xs">(Leave blank to keep current password)</span></label>
                                <input type="password" id="edit_password" name="password"
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Enter new password (optional)">
                            </div>
                            <div>
                                <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-gray-400 text-xs">(Only if changing password)</span></label>
                                <input type="password" id="edit_password_confirmation" name="password_confirmation"
                                    class="input-field w-full px-4 py-2.5 text-gray-700 placeholder-gray-400"
                                    placeholder="Confirm new password">
                            </div>
                            <p class="text-xs text-gray-400">Update the user's password if needed</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="flex-1 btn-primary text-white py-3 rounded-xl font-semibold transition">
                            Update User
                        </button>
                        <button type="button" onclick="closeEditModal()" class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Store existing chairpersons by department
        const existingChairpersons = {!! \App\Models\User::where('role', 'chairperson')->pluck('department')->toJson() !!};
        let currentEditingUserId = null;
        let currentEditingUserDept = null;
        let currentEditingUserRole = null;

        function openEditModal(userId, firstName, middleName, lastName, email, department, role, academicRank, regularizedAt) {
            // Store current editing user info
            currentEditingUserId = userId;
            currentEditingUserDept = department;
            currentEditingUserRole = role;

            // Set form action
            document.getElementById('editUserForm').action = `/admin/update-user/${userId}`;
            
            // Populate form fields
            document.getElementById('edit_first_name').value = firstName;
            document.getElementById('edit_middle_name').value = middleName || '';
            document.getElementById('edit_last_name').value = lastName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_department').value = department;
            document.getElementById('edit_role').value = role;
            
            // Set academic rank based on role
            if (role === 'faculty' && (academicRank === 'University Professor' || academicRank === 'Instructor 1')) {
                document.getElementById('edit_academic_rank').value = academicRank || '';
                document.getElementById('edit_academic_rank_text').value = '';
            } else {
                document.getElementById('edit_academic_rank').value = '';
                document.getElementById('edit_academic_rank_text').value = academicRank || '';
            }
            
            document.getElementById('edit_regularized_at').value = regularizedAt || '';
            
            // Clear password fields
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            
            // Update role options based on department
            updateModalRoleOptions();
            
            // Update academic rank field
            updateModalAcademicRankField();
            
            // Show modal
            document.getElementById('editModal').classList.add('show');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
            currentEditingUserId = null;
            currentEditingUserDept = null;
            currentEditingUserRole = null;
        }

        function updateModalRoleOptions() {
            const department = document.getElementById('edit_department').value;
            const roleSelect = document.getElementById('edit_role');
            const chairpersonOption = roleSelect.querySelector('option[value="chairperson"]');
            
            // Enable/disable chairperson option based on existing chairpersons
            if (department && existingChairpersons.includes(department)) {
                // If current user is the chairperson of this department, allow keeping the role
                if (department === currentEditingUserDept && currentEditingUserRole === 'chairperson') {
                    chairpersonOption.disabled = false;
                    chairpersonOption.textContent = 'Chairperson (Current)';
                } else {
                    chairpersonOption.disabled = true;
                    chairpersonOption.textContent = 'Chairperson (Already Assigned)';
                    // If chairperson was selected, switch to faculty
                    if (roleSelect.value === 'chairperson') {
                        roleSelect.value = 'faculty';
                    }
                }
            } else {
                chairpersonOption.disabled = false;
                chairpersonOption.textContent = 'Chairperson';
            }
            
            // Update academic rank field when role changes
            updateModalAcademicRankField();
        }
        
        function updateModalAcademicRankField() {
            const roleSelect = document.getElementById('edit_role');
            const academicRankSelect = document.getElementById('edit_academic_rank');
            const academicRankText = document.getElementById('edit_academic_rank_text');
            
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

        // Add event listener for role change in edit modal
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('edit_role').addEventListener('change', updateModalAcademicRankField);
        });

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
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
