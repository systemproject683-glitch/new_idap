<!-- Admin Sidebar -->
@php
    $sidebarUser  = Auth::guard('admin')->user();
    $sidebarName  = ($sidebarUser->first_name ?? '') . ' ' . ($sidebarUser->last_name ?? '');
    $sidebarDept  = $sidebarUser->department ?? '';
    $initials     = strtoupper(substr($sidebarUser->first_name ?? 'A', 0, 1))
                  . strtoupper(substr($sidebarUser->last_name  ?? '',  0, 1));
    if ($initials === '') $initials = 'A';

    $isHome       = request()->routeIs('admin.dashboard');
    $isUsers      = request()->routeIs('admin.users');
    $isCreate     = request()->routeIs('admin.create.user');
    $isDevObj     = request()->routeIs('admin.development-objectives');
    $isActivities = request()->routeIs('admin.recent-activities');
@endphp

<aside class="w-64 text-white h-screen fixed left-0 top-0 flex flex-col overflow-hidden"
       style="background: linear-gradient(160deg, #FF7A28 0%, #E85510 55%, #D44008 100%);">

    {{-- Layered texture overlays --}}
    <div class="absolute inset-0 pointer-events-none" style="
        background:
            radial-gradient(ellipse at 115% -8%, rgba(255,200,100,.45) 0%, transparent 52%),
            linear-gradient(135deg, rgba(255,200,130,.22) 0%, transparent 55%),
            linear-gradient(to top left, rgba(180,50,0,.20) 0%, transparent 50%);
    "></div>
    <div class="absolute inset-0 pointer-events-none" style="
        background: repeating-linear-gradient(
            135deg,
            transparent 0px, transparent 60px,
            rgba(255,255,255,.05) 60px, rgba(255,255,255,.05) 61px
        );
    "></div>

    {{-- Scrollable inner column --}}
    <div class="relative flex flex-col h-full overflow-y-auto">

        {{-- ── Brand Header ── --}}
        <div class="px-5 pt-6 pb-5 flex-shrink-0"
             style="border-bottom: 1px solid rgba(255,176,124,.15);">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center font-bold text-white text-[10px] flex-shrink-0"
                     style="background: linear-gradient(135deg, #FFAA55, #FF6622);
                            box-shadow: 0 2px 8px rgba(0,0,0,.20);">
                    CvSU
                </div>
                <div>
                    <div class="font-bold text-white text-xl leading-tight tracking-wide">CEIT</div>
                    <div class="text-[10px] font-semibold leading-tight uppercase tracking-wider"
                         style="color: #FFD4B3;">Learning &amp; Development Plan</div>
                </div>
            </div>
        </div>

        {{-- ── Navigation ── --}}
        <nav class="flex-1 px-3 pt-5 pb-3">

            {{-- MAIN MENU label --}}
            <p class="px-3 mb-2 text-[10px] font-bold tracking-widest uppercase"
               style="color: #FFCC99;">Main Menu</p>

            {{-- Home --}}
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isHome ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isHome)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isHome ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10.5L12 4l9 6.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isHome ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Home</span>
            </a>

            {{-- MANAGEMENT label --}}
            <p class="px-3 mt-4 mb-2 text-[10px] font-bold tracking-widest uppercase"
               style="color: #FFCC99;">Management</p>

            {{-- Users Management --}}
            <a href="{{ route('admin.users') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isUsers ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isUsers)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isUsers ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isUsers ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Users Management</span>
            </a>

            {{-- Create User --}}
            <a href="{{ route('admin.create.user') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isCreate ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isCreate)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isCreate ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isCreate ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Create User</span>
            </a>

            {{-- Development Objectives --}}
            <a href="{{ route('admin.development-objectives') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isDevObj ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isDevObj)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isDevObj ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isDevObj ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Development Objectives - L&D Plan</span>
            </a>

            {{-- Recent Activities --}}
            <a href="{{ route('admin.recent-activities') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isActivities ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isActivities)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isActivities ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isActivities ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Recent Activities</span>
            </a>

        </nav>

        {{-- ── Footer ── --}}
        <div class="flex-shrink-0 px-4 py-4"
             style="background: rgba(0,0,0,.10); border-top: 1px solid rgba(255,176,124,.12);">

            {{-- User card --}}
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                     style="background: linear-gradient(135deg, #FFAA55, #FF6622);
                            box-shadow: 0 2px 6px rgba(0,0,0,.20);">
                    {{ $initials }}
                </div>
                <div class="leading-tight min-w-0">
                    <div class="text-sm font-semibold text-white truncate">{{ trim($sidebarName) }}</div>
                    <div class="text-xs truncate" style="color: #FFD4B3;">
                        Admin
                    </div>
                </div>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="sidebar-logout w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                        style="border: 1px solid rgba(255,100,60,.25);
                               background: rgba(255,80,40,.08);
                               color: rgba(255,180,150,.80);">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>{{-- /inner column --}}
</aside>

<style>
.sidebar-nav-item:hover {
    background: rgba(255,255,255,.08) !important;
}
.sidebar-nav-item:hover span.w-8 {
    background: rgba(255,255,255,.12) !important;
}
.sidebar-nav-item[style*="linear-gradient"]:hover {
    background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14)) !important;
}
.sidebar-logout:hover {
    background: rgba(255,80,40,.18) !important;
    color: #ffffff !important;
}
</style>

@include('partials.page-loader')
