<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Members - IDAP System</title>
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
        .table-hover:hover {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        @include('chairperson.sidebar')

        <!-- Main Content -->
        <div class="flex-1 ml-64 overflow-y-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Faculty Members</h1>
                    <p class="text-gray-600 mt-2">View and manage faculty members in the {{ Auth::user()->department }} department</p>
                </div>

                <!-- Faculty Members Table -->
                <div class="card">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-800">All Faculty Members</h2>
                            <div class="text-sm text-gray-500">
                                Total: {{ $facultyMembers->total() }} members
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($facultyMembers->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Email</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Department</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($facultyMembers as $faculty)
                                            <tr class="border-b border-gray-100 table-hover">
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                            <span class="text-xs font-medium text-gray-600">
                                                                {{ strtoupper(substr($faculty->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $faculty->name }}</p>
                                                            <p class="text-sm text-gray-500">{{ $faculty->role }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 text-gray-600">{{ $faculty->email }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                        {{ $faculty->department }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('chairperson.faculty-member-details', $faculty->id) }}" 
                                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                        View Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6 flex justify-center">
                                {{ $facultyMembers->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Faculty Members Found</h3>
                                <p class="text-gray-600">No faculty members found in your department.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
