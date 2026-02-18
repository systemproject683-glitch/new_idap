<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - IDAP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <div class="p-8 page-content">
            <!-- Header -->
            <div class="header-bar page-header-fixed">
                <h1 class="text-2xl font-bold text-gray-800 mt-0">Admin Dashboard</h1>
                <p class="text-gray-600 mt-1 mb-0 leading-tight">Welcome, {{ Auth::guard('admin')->user()->first_name }} {{ Auth::guard('admin')->user()->last_name }}!</p>
            </div>
            <div class="page-header-spacer"></div>
            <div class="px-5">

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
                    <p class="text-3xl font-bold text-orange-500">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Active Sessions</h3>
                    <p class="text-3xl font-bold text-green-500">{{ \DB::table('sessions')->count() }}</p>
                </div>
                <div class="card p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Development Objectives</h3>
                    <p class="text-3xl font-bold text-blue-500">{{ $totalDevelopmentObjectives }}</p>
                </div>
            </div>

            <!-- Recent Activity and Department Distribution -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Recent Activity Card -->
                <div class="card p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
                    <div class="space-y-4">
                        @if($recentActivities->count() > 0)
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start">
                                    <div class="w-3 h-3 rounded-full bg-orange-500 mt-2 mr-4 flex-shrink-0"></div>
                                    <div class="flex-1">
                                        <p class="text-gray-800 text-sm">
                                            <span class="font-semibold">{{ $activity['user'] }}</span> 
                                            {{ $activity['action'] }}
                                        </p>
                                        <p class="text-gray-500 text-xs mt-1">
                                            {{ $activity['time']->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500">No recent activities</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Department Distribution Card -->
                <div class="card p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Department Distribution</h2>
                    <div style="position: relative; height: 300px;">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>

            </div>
            </div>
        </div>
    </div>
    
    <script>
        // Department Distribution Chart
        const departmentData = @json($departmentData);
        const ctx = document.getElementById('departmentChart').getContext('2d');
        
        const departments = departmentData.map(d => d.name);
        const usersData = departmentData.map(d => d.users);
        const objectivesData = departmentData.map(d => d.objectives);
        const completedData = departmentData.map(d => d.completed);
        
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: departments,
                datasets: [
                    {
                        label: 'Users',
                        data: usersData,
                        backgroundColor: '#3b82f6',
                        borderColor: '#1e40af',
                        borderWidth: 1
                    },
                    {
                        label: 'Development Objectives',
                        data: objectivesData,
                        backgroundColor: '#f97316',
                        borderColor: '#c2410c',
                        borderWidth: 1
                    },
                    {
                        label: 'Completed',
                        data: completedData,
                        backgroundColor: '#10b981',
                        borderColor: '#047857',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
