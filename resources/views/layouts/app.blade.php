<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('navigation.brand')) - {{ __('navigation.tagline') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Fixed Header Width Adjustments */
        @media (min-width: 1024px) {
            .header-expanded { left: 18rem; width: calc(100% - 18rem); }
            .header-collapsed { left: 0; width: 100%; }
            .sidebar-expanded { width: 18rem; }
            .sidebar-collapsed { width: 0; overflow: hidden; border-right-width: 0; }
            .content-expanded { padding-left: 18rem; }
            .content-collapsed { padding-left: 0; }
        }

        /* UCD: Focus Ring Improvements */
        button:focus-visible, a:focus-visible {
            outline: 2px solid #10b981;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 min-h-screen font-sans antialiased text-slate-900">
    @guest
        @yield('content')
    @else
        <div x-data="{ 
                sidebarOpen: false, 
                sidebarDesktopOpen: true,
                userMenuOpen: false,
                toggleSidebar() {
                    if (window.innerWidth < 1024) {
                        this.sidebarOpen = !this.sidebarOpen;
                    } else {
                        this.sidebarDesktopOpen = !this.sidebarDesktopOpen;
                    }
                }
             }" 
             class="relative min-h-screen">
            
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" 
                 x-cloak
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[110] lg:hidden"></div>

            <!-- Sidebar -->
            <aside x-cloak
                   :class="{
                       'translate-x-0': sidebarOpen,
                       '-translate-x-full': !sidebarOpen,
                       'lg:translate-x-0': true,
                       'sidebar-expanded': sidebarDesktopOpen,
                       'sidebar-collapsed': !sidebarDesktopOpen
                   }"
                   class="fixed inset-y-0 left-0 z-[120] w-72 bg-white border-r border-slate-200 shadow-2xl transition-all duration-300 ease-in-out lg:shadow-none">
                
                <!-- Sidebar Header -->
                <div class="h-20 flex items-center px-6 border-b border-slate-100 bg-gradient-to-r from-green-600 to-emerald-600 overflow-hidden whitespace-nowrap">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white tracking-tight">Pantau<span class="font-extrabold">ITE</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="p-6 space-y-1.5 overflow-y-auto max-h-[calc(100vh-5rem)] custom-scrollbar">
                    {{-- Dashboard --}}
                    @if(Auth::user()->hasPermission('view-dashboard'))
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-emerald-600' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>{{ __('navigation.dashboard') }}</span>
                    </a>
                    @endif

                    {{-- Tickets --}}
                    @if(Auth::user()->hasPermission('view-own-tickets') || Auth::user()->hasPermission('view-all-tickets'))
                    <a href="{{ route('tickets.index') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('tickets.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-emerald-600' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('tickets.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <span>{{ __('navigation.tickets') }}</span>
                    </a>
                    @endif

                    {{-- Knowledge Base --}}
                    @if(Auth::user()->hasPermission('view-kb'))
                    <a href="{{ route('kb.index') }}"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('kb.*') ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-emerald-600' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('kb.*') ? 'text-emerald-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Knowledge Base</span>
                    </a>
                    @endif

                    <div class="pt-4 pb-2 px-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Management</span>
                    </div>

                    {{-- IT Management Dropdown --}}
                    @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-departments') || Auth::user()->hasPermission('manage-categories') || Auth::user()->hasPermission('manage-users') || Auth::user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets']))
                    <div x-data="{ open: {{ (request()->routeIs('assets.*') || request()->routeIs('departments.*') || request()->routeIs('tickets.categories.*') || request()->routeIs('users.*') || request()->routeIs('repair-requests.admin.*')) ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-emerald-600">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>IT Management</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-1 ml-4 border-l-2 border-slate-100 pl-2 space-y-1">
                            @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-assets'))
                            <a href="{{ route('assets.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('assets.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Assets</a>
                            @endif
                            @if(Auth::user()->hasPermission('manage-vendors') || Auth::user()->hasPermission('manage-assets'))
                            <a href="{{ route('vendors.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('vendors.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Vendors</a>
                            @endif
                            @if(Auth::user()->hasPermission('manage-departments'))
                            <a href="{{ route('departments.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('departments.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Departments</a>
                            @endif
                            @if(Auth::user()->hasPermission('manage-categories'))
                            <a href="{{ route('tickets.categories.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('tickets.categories.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Ticket Categories</a>
                            @endif
                            @if(Auth::user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets']))
                            <a href="{{ route('repair-requests.admin.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('repair-requests.admin.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Verifikasi Perbaikan</a>
                            @endif
                            @if(Auth::user()->hasPermission('manage-users'))
                            <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('users.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Users</a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Maintenance Dropdown --}}
                    @if(Auth::user()->hasPermission('manage-assets'))
                    <div x-data="{ open: {{ str_contains(Route::currentRouteName(), 'maintenance.') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-emerald-600">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>Maintenance</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-1 ml-4 border-l-2 border-slate-100 pl-2 space-y-1">
                            <a href="{{ route('maintenance.schedules.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('maintenance.schedules.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Schedules</a>
                            <a href="{{ route('maintenance.tasks.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('maintenance.tasks.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Tasks</a>
                            <a href="{{ route('maintenance.inventory.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('maintenance.inventory.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Inventory</a>
                        </div>
                    </div>
                    @endif

                    {{-- Analytics Dropdown --}}
                    @if(Auth::user()->hasPermission('view-reports') || Auth::user()->hasPermission('manage-sla'))
                    <div x-data="{ open: {{ (request()->routeIs('reports.*') || request()->routeIs('sla-policies.*')) ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 text-slate-600 hover:bg-slate-50 hover:text-emerald-600">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Analytics</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-collapse class="mt-1 ml-4 border-l-2 border-slate-100 pl-2 space-y-1">
                            @if(Auth::user()->hasPermission('view-reports'))
                            <a href="{{ route('reports.index') }}" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('reports.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">Reports</a>
                            @endif
                            @if(Auth::user()->hasPermission('manage-sla'))
                            <a href="#" class="block px-4 py-2 text-sm transition-colors {{ request()->routeIs('sla-policies.*') ? 'text-emerald-600 font-semibold' : 'text-slate-500 hover:text-emerald-600' }}">SLA Policies</a>
                            @endif
                        </div>
                    </div>
                    @endif
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div :class="sidebarDesktopOpen ? 'content-expanded' : 'content-collapsed'"
                 class="min-h-screen flex flex-col transition-[padding] duration-300 ease-in-out relative z-10">
                
                <!-- Top Header -->
                <header :class="sidebarDesktopOpen ? 'header-expanded' : 'header-collapsed'"
                        class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 lg:px-10 fixed top-0 left-0 right-0 z-[150] transition-all duration-300 ease-in-out">
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <button @click="toggleSidebar()" 
                                aria-label="Toggle Sidebar"
                                class="p-3 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-emerald-600 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div class="flex flex-col">
                            <h1 class="text-lg lg:text-2xl font-bold text-slate-800 truncate max-w-[150px] md:max-w-none">@yield('title', 'Dashboard')</h1>
                            <div class="hidden md:flex items-center text-[10px] text-slate-400 uppercase tracking-widest font-bold">
                                <span>ITSM Platform</span>
                                <span class="mx-2">•</span>
                                <span class="text-emerald-500">Live Status</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 lg:space-x-6">
                        <!-- Search (Desktop) -->
                        <div class="hidden md:block relative group">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" 
                                   class="block w-48 lg:w-80 pl-10 pr-3 py-2 border border-slate-200 rounded-xl bg-slate-50 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                                   placeholder="Search anything...">
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" 
                                    @click.outside="userMenuOpen = false"
                                    class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-50 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-200 ring-2 ring-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden lg:block text-left">
                                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                                    <p class="text-xs font-medium text-slate-500 uppercase tracking-tighter">{{ Auth::user()->role->display_name }}</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- User Menu Content -->
                            <div x-show="userMenuOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                                 class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl ring-1 ring-slate-200 z-50 overflow-hidden"
                                 style="display: none;">
                                <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="p-2">
                                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="{{ route('password.change') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                        <span>Change Password</span>
                                    </a>
                                </div>
                                <div class="p-2 border-t border-slate-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm text-red-600 hover:bg-red-50 transition-colors font-semibold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            <span>Sign Out</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6 lg:p-10 mt-20">
                    <div class="max-w-[1600px] mx-auto">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    @endguest



    @stack('scripts')

    <!-- Toast & Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show toast notification for success/error messages
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            const ErrorToast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            ErrorToast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        // SweetAlert confirmation for delete buttons
        function confirmDelete(event, message = 'Are you sure you want to permanently delete this item? This action cannot be undone.') {
            event.preventDefault();
            const form = event.target.closest('form') || event.target;

            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (form.tagName === 'FORM') {
                        form.submit();
                    } else {
                        // If it's a button inside a form, submit the parent form
                        const parentForm = form.closest('form');
                        if (parentForm) {
                            parentForm.submit();
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
