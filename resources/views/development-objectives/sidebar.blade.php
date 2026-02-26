<!-- Faculty Member Sidebar -->
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
            <a href="{{ route('development-objectives.index') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('development-objectives.index') && !request()->get('section') ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 4l9 6.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" />
                </svg>
                Home
            </a>

            <a href="{{ route('development-objectives.list', ['section' => 'list']) }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('development-objectives.list') && request()->get('section') !== 'progress' ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9" stroke-width="2"></circle>
                    <circle cx="12" cy="12" r="5" stroke-width="2"></circle>
                    <circle cx="12" cy="12" r="1.5" stroke-width="2" fill="currentColor"></circle>
                </svg>
                Development Objectives
            </a>

            <a href="{{ route('development-objectives.add') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('development-objectives.add') ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                </svg>
                Add New Objective
            </a>

            <a href="{{ route('development-objectives.progress') }}" class="flex items-center px-4 py-2 rounded-lg transition-colors hover:bg-gradient-to-r hover:from-white/25 hover:to-white/0 {{ request()->routeIs('development-objectives.progress') ? 'bg-white bg-opacity-20' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6v12a2 2 0 002 2h12" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14l3-3 3 2 4-5" />
                </svg>
                Progress Tracking
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
            $sidebarName = $sidebarUser->first_name . ' ' . $sidebarUser->last_name ?? 'User';
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
                $initials = 'U';
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
