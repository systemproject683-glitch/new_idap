<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - IDAP System</title>
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
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        .modal-header {
            background-color: #ff6b35;
            color: white;
            padding: 20px 24px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-close {
            color: white;
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
            border-radius: 4px;
            transition: background-color 0.2s;
        }
        .modal-close:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .modal-body {
            padding: 24px;
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
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mt-0">Users Management</h1>
                        <p class="text-gray-600 mt-1 mb-0 leading-tight">Manage all users in the IDAP system</p>
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
                                                    <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white font-normal text-sm">{{ strtoupper(substr($user->first_name, 0, 1)) . strtoupper(substr($user->last_name, 0, 1)) }}</span>
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
                                                        onclick="openEditModal({{ $user->id }}, '{{ $user->first_name }}', '{{ $user->middle_name }}', '{{ $user->last_name }}', '{{ $user->email }}', '{{ $user->department }}', '{{ $user->role }}')" 
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
                <h2 class="text-lg font-semibold">Edit User</h2>
                <button class="modal-close" onclick="closeEditModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <!-- First Name Field -->
                        <div>
                            <label for="edit_first_name" class="block text-gray-700 text-sm font-medium mb-2">
                                First Name
                            </label>
                            <input 
                                type="text" 
                                id="edit_first_name" 
                                name="first_name" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                placeholder="Enter first name"
                                required
                            >
                        </div>
                        
                        <!-- Middle Name Field -->
                        <div>
                            <label for="edit_middle_name" class="block text-gray-700 text-sm font-medium mb-2">
                                Middle Name
                            </label>
                            <input 
                                type="text" 
                                id="edit_middle_name" 
                                name="middle_name" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                placeholder="Enter middle name (optional)"
                            >
                        </div>
                        
                        <!-- Last Name Field -->
                        <div>
                            <label for="edit_last_name" class="block text-gray-700 text-sm font-medium mb-2">
                                Last Name
                            </label>
                            <input 
                                type="text" 
                                id="edit_last_name" 
                                name="last_name" 
                                class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                                placeholder="Enter last name"
                                required
                            >
                        </div>
                    </div>
                    
                    <!-- Email Field -->
                    <div class="mb-6">
                        <label for="edit_email" class="block text-gray-700 text-sm font-medium mb-2">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="edit_email" 
                            name="email" 
                            class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                            placeholder="Enter user's email address"
                            required
                        >
                    </div>
                    
                    <!-- Department Field -->
                    <div class="mb-6">
                        <label for="edit_department" class="block text-gray-700 text-sm font-medium mb-2">
                            Department
                        </label>
                        <select 
                            id="edit_department" 
                            name="department" 
                            class="input-field w-full px-4 py-3 text-gray-700"
                            required
                            onchange="updateModalRoleOptions()"
                        >
                            <option value="">Select Department</option>
                            <option value="DAFE">Department of Agriculture and Food Engineering (DAFE)</option>
                            <option value="DCEA">Department of Civil Engineering (DCEA)</option>
                            <option value="DCEEE">Department of Computer, Electronics, and Electrical Engineering (DCEEE)</option>
                            <option value="DIET">Department of Industrial Engineering and Technology (DIET)</option>
                            <option value="DIT">Department of Information Technology (DIT)</option>
                        </select>
                    </div>
                    
                    <!-- Role Field -->
                    <div class="mb-6">
                        <label for="edit_role" class="block text-gray-700 text-sm font-medium mb-2">
                            Role
                        </label>
                        <select 
                            id="edit_role" 
                            name="role" 
                            class="input-field w-full px-4 py-3 text-gray-700"
                            required
                        >
                            <option value="">Select Role</option>
                            <option value="faculty">Faculty Member</option>
                            <option value="chairperson">Chairperson</option>
                        </select>
                    </div>
                    
                    <!-- Password Field (Optional) -->
                    <div class="mb-6">
                        <label for="edit_password" class="block text-gray-700 text-sm font-medium mb-2">
                            Password <span class="text-gray-500 text-xs">(Leave blank to keep current password)</span>
                        </label>
                        <input 
                            type="password" 
                            id="edit_password" 
                            name="password" 
                            class="input-field w-full px-4 py-3 text-gray-700 placeholder-gray-400"
                            placeholder="Enter new password (optional)"
                        >
                    </div>
                    
                    <!-- Password Confirmation -->
                    <div class="mb-6">
                        <label for="edit_password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                            Confirm Password <span class="text-gray-500 text-xs">(Only if changing password)</span>
                        </label>
                        <input 
                            type="password" 
                            id="edit_password_confirmation" 
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
                        <button type="button" onclick="closeEditModal()" class="bg-gray-300 text-gray-700 px-6 py-3 rounded hover:bg-gray-400 transition">
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

        function openEditModal(userId, firstName, middleName, lastName, email, department, role) {
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
            
            // Clear password fields
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
            
            // Update role options based on department
            updateModalRoleOptions();
            
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
        }

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
</body>
</html>
