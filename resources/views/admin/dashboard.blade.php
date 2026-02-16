<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #fff7ed;
        }
        .sidebar {
            background-color: #ff6b35;
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
                <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome, {{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}!</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Users</h3>
                    <p class="text-3xl font-bold text-orange-500">{{ $users->total() }}</p>
                </div>
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Sessions</h3>
                    <p class="text-3xl font-bold text-green-500">{{ \DB::table('sessions')->count() }}</p>
                </div>
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">System Status</h3>
                    <p class="text-3xl font-bold text-blue-500">Online</p>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Users Management</h2>
                        <a href="{{ route('admin.create.user') }}" class="btn-primary text-white px-4 py-2 rounded transition">
                            Add New User
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">ID</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Department</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Role</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Created At</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $user->id }}</td>
                                            <td class="py-3 px-4">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</td>
                                            <td class="py-3 px-4">{{ $user->email }}</td>
                                            <td class="py-3 px-4">
                                                @switch($user->department)
                                                    @case('DAFE')
                                                        Department of Agriculture and Food Engineering (DAFE)
                                                        @break
                                                    @case('DCEA')
                                                        Department of Civil Engineering (DCEA)
                                                        @break
                                                    @case('DCEEE')
                                                        Department of Computer, Electronics, and Electrical Engineering (DCEEE)
                                                        @break
                                                    @case('DIET')
                                                        Department of Industrial Engineering and Technology (DIET)
                                                        @break
                                                    @case('DIT')
                                                        Department of Information Technology (DIT)
                                                        @break
                                                    @default
                                                        <span class="text-gray-500">Not Assigned</span>
                                                @endswitch
                                            </td>
                                            <td class="py-3 px-4">
                                                @if($user->role === 'chairperson')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                        Chairperson
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Faculty Member
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">{{ $user->created_at->format('M d, Y') }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('admin.edit.user', $user->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.delete.user', $user->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                                        Delete
                                                    </button>
                                                </form>
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
</body>
</html>
