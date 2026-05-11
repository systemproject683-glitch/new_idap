<!-- Chairperson Sidebar -->
@php
    $sidebarUser  = auth()->user();
    $sidebarName  = ($sidebarUser->first_name ?? '') . ' ' . ($sidebarUser->last_name ?? '');
    $sidebarDept  = $sidebarUser->department ?? 'Department';
    $initials     = strtoupper(substr($sidebarUser->first_name ?? 'C', 0, 1))
                  . strtoupper(substr($sidebarUser->last_name  ?? '',  0, 1));
    if ($initials === '') $initials = 'C';

    $isDashboard  = request()->routeIs('chairperson.dashboard');
    $isFaculty    = request()->routeIs('chairperson.faculty-members');
    $isReports    = request()->routeIs('chairperson.department-reports');
    $isSummary    = request()->routeIs('chairperson.summary-lnd');
    $isDevObj     = request()->routeIs('chairperson.development-objectives');
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

            {{-- Dashboard --}}
            <a href="{{ route('chairperson.dashboard') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isDashboard ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isDashboard)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isDashboard ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10.5L12 4l9 6.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isDashboard ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Dashboard</span>
            </a>

            {{-- Faculty Members --}}
            <a href="{{ route('chairperson.faculty-members') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isFaculty ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isFaculty)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isFaculty ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isFaculty ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Faculty Members</span>
            </a>

            {{-- REPORTS label --}}
            <p class="px-3 mt-4 mb-2 text-[10px] font-bold tracking-widest uppercase"
               style="color: #FFCC99;">Reports</p>

            {{-- Reports --}}
            <a href="{{ route('chairperson.department-reports') }}"
               class="sidebar-nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl mb-1 transition-all duration-200"
               style="{{ $isReports ? 'background: linear-gradient(90deg, rgba(255,255,255,.28), rgba(255,255,255,.14));' : '' }}">
                @if($isReports)
                    <span class="absolute left-0 top-2 bottom-2 w-[3px] rounded-full"
                          style="background:#FFFFFF; box-shadow:0 0 8px rgba(255,255,255,.80);"></span>
                @endif
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                      style="background: {{ $isReports ? 'rgba(255,255,255,.20)' : 'rgba(255,255,255,.07)' }};">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isReports ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Reports</span>
            </a>

            {{-- Summary L&D --}}
            <a href="{{ route('chairperson.summary-lnd') }}"
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
                              d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </span>
                <span class="text-sm font-medium"
                      style="color: {{ $isSummary ? '#FFFFFF' : 'rgba(255,255,255,.70)' }};">Summary L&amp;D</span>
            </a>

            {{-- Development Objectives --}}
            <a href="{{ route('chairperson.development-objectives') }}"
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
                        {{ $sidebarDept }} &middot; Chairperson
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateLiveClock() {
        var el = document.getElementById('live-time');
        if (!el) return;
        var now = new Date();
        var h = now.getHours();
        var m = String(now.getMinutes()).padStart(2, '0');
        var s = String(now.getSeconds()).padStart(2, '0');
        var ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        el.textContent = h + ':' + m + ':' + s + ' ' + ampm;
    }
    updateLiveClock();
    setInterval(updateLiveClock, 1000);
});
</script>
