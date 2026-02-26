<!-- Chairperson Sidebar -->
<aside
  class="w-64 text-white h-screen fixed left-0 top-0 overflow-y-auto"
    style="background-image: url('{{ asset('images/sidebar-bg.jpg') }}'); background-size: cover; background-position: 55% 45%;"
>
    <div class="p-4 h-full flex flex-col" style="background-color: rgba(255, 107, 53, 0.35);">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-white">CEIT</h1>
            <p class="text-sm text-white">Learning Development Plan</p>
        </div>
        
        <nav class="space-y-2 flex-1 text-[14px]">
            <a href="{{ route('chairperson.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('chairperson.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 4l9 6.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" />
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('chairperson.faculty-members') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('chairperson.faculty-members') ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Faculty Members
            </a>
            
            <a href="{{ route('chairperson.department-reports') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('chairperson.department-reports') ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Reports
            </a>
            
            <div class="pt-4 mt-4 border-t border-white border-opacity-30">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 rounded-lg hover:bg-red-600 hover:bg-opacity-80 transition-colors w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>

        @php
            $sidebarUser = auth()->user();
            $sidebarName = $sidebarUser->first_name . ' ' . $sidebarUser->last_name ?? 'Chairperson';
            $sidebarDepartment = $sidebarUser->department ?? 'Department';
            
            // Generate initials from first name and last name
            $firstName = $sidebarUser->first_name ?? '';
            $lastName = $sidebarUser->last_name ?? '';
            $initials = '';
            
            if (!empty($firstName)) {
                $initials .= strtoupper(substr($firstName, 0, 1));
            }
            if (!empty($lastName)) {
                $initials .= strtoupper(substr($lastName, 0, 1));
            }
            
            if ($initials === '') {
                $initials = 'C';
            }
        @endphp

        <div class="mt-auto pt-4 border-t border-white border-opacity-30">
            <div class="flex items-center gap-3 px-3 py-3 rounded-lg bg-white bg-opacity-10">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold"
                     style="background-color: #ff6b35;">
                    {{ $initials }}
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-semibold text-white">{{ $sidebarName }}</div>
                    <div class="text-xs text-white text-opacity-80">{{ $sidebarDepartment }}</div>
                </div>
            </div>
        </div>
    </div>
</aside>
