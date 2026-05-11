<!-- Faculty Member Sidebar -->
@php
    $sidebarUser   = auth()->user();
    $sidebarName   = ($sidebarUser->first_name ?? '') . ' ' . ($sidebarUser->last_name ?? '');
    $sidebarDept   = $sidebarUser->department ?? 'Department';
    $initials      = strtoupper(substr($sidebarUser->first_name ?? 'U', 0, 1))
                   . strtoupper(substr($sidebarUser->last_name  ?? '',  0, 1));
    if ($initials === '') $initials = 'U';

    $isHome     = request()->routeIs('development-objectives.index') && !request()->get('section');
    $isObjList  = request()->routeIs('development-objectives.list') && request()->get('section') !== 'progress';
    $isAdd      = request()->routeIs('development-objectives.add');
    $isProgress = request()->routeIs('development-objectives.progress');
    $isSummary  = request()->routeIs('development-objectives.summary');
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
            <a href="{{ route('development-objectives.index') }}"
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

            {{-- Development Objectives --}}
            <a href="{{ route('development-objectives.list', ['section' => 'list']) }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isObjList ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isObjList)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isObjList ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"   stroke-width="2"></circle>
                        <circle cx="12" cy="12" r="5"   stroke-width="2"></circle>
                        <circle cx="12" cy="12" r="1.5" stroke-width="2" fill="currentColor"></circle>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isObjList ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Development Objectives </span
            </a>

            {{-- Add New Objective --}}
            <a href="{{ route('development-objectives.add') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isAdd ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isAdd)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isAdd ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isAdd ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Add New Objective</span>
            </a>

            {{-- REPORTS label --}}
            <p class="px-3 mt-4 mb-2 text-[10px] font-bold tracking-widest uppercase"
               style="color: #FFCC99;">Reports</p>

            {{-- Progress Tracking --}}
            <a href="{{ route('development-objectives.progress') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isProgress ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isProgress)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isProgress ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6v12a2 2 0 002 2h12"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 14l3-3 3 2 4-5"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isProgress ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Progress Tracking</span>
            </a>

            {{-- Summary of L&D --}}
            <a href="{{ route('development-objectives.summary') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isSummary ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isSummary)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isSummary ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isSummary ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Summary of L&amp;D</span>
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
                        {{ $sidebarDept }} &middot; Faculty
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
