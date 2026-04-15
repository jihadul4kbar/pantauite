<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('navigation.brand')) - {{ __('navigation.tagline') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @guest
        @yield('content')
    @else
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                                    PantauITE
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden sm:ml-6 sm:flex sm:space-x-6">
                                {{-- Dashboard - All roles except End User --}}
                                @if(Auth::user()->hasPermission('view-dashboard'))
                                <a href="{{ route('dashboard') }}"
                                   class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    {{ __('navigation.dashboard') }}
                                </a>
                                @endif

                                {{-- Tickets - All roles --}}
                                @if(Auth::user()->hasPermission('view-own-tickets') || Auth::user()->hasPermission('view-all-tickets'))
                                <a href="{{ route('tickets.index') }}"
                                   class="{{ request()->routeIs('tickets.*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                    {{ __('navigation.tickets') }}
                                </a>
                                @endif

                                {{-- Knowledge Base - All roles --}}
                                @if(Auth::user()->hasPermission('view-kb'))
                                <a href="{{ route('kb.index') }}"
                                   class="{{ request()->routeIs('kb.*') ? 'border-purple-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    Knowledge Base
                                </a>
                                @endif

                                {{-- IT Management Dropdown --}}
                                @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-departments') || Auth::user()->hasPermission('manage-categories') || Auth::user()->hasPermission('manage-users') || Auth::user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets']))
                                <div class="relative inline-block pt-5" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false"
                                       class="{{ (request()->routeIs('assets.*') || request()->routeIs('departments.*') || request()->routeIs('tickets.categories.*') || request()->routeIs('users.*') || request()->routeIs('repair-requests.admin.*')) ? 'border-green-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        IT Management
                                        <svg class="ml-1 h-4 w-4 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="origin-top-left absolute left-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="py-1">
                                            @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-assets'))
                                            <a href="{{ route('assets.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors {{ request()->routeIs('assets.*') ? 'bg-green-50 text-green-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">🖥️</span> Assets
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('manage-departments'))
                                            <a href="{{ route('departments.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors {{ request()->routeIs('departments.*') ? 'bg-green-50 text-green-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">🏢</span> Departments
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('manage-categories'))
                                            <a href="{{ route('tickets.categories.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors {{ request()->routeIs('tickets.categories.*') ? 'bg-green-50 text-green-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">🏷️</span> Ticket Categories
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets']))
                                            <a href="{{ route('repair-requests.admin.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors {{ request()->routeIs('repair-requests.admin.*') ? 'bg-green-50 text-green-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">🔧</span> Verifikasi Perbaikan
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('manage-users'))
                                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors {{ request()->routeIs('users.*') ? 'bg-green-50 text-green-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">👥</span> Users
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Maintenance Dropdown --}}
                                @if(Auth::user()->hasPermission('manage-assets'))
                                <div class="relative inline-block pt-5" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false"
                                       class="{{ str_contains(Route::currentRouteName(), 'maintenance.') ? 'border-emerald-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Maintenance
                                        <svg class="ml-1 h-4 w-4 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="origin-top-left absolute left-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="py-1">
                                            <a href="{{ route('maintenance.schedules.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors {{ request()->routeIs('maintenance.schedules.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">📅</span> Schedules
                                            </a>
                                            <a href="{{ route('maintenance.tasks.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors {{ request()->routeIs('maintenance.tasks.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">🔧</span> Tasks
                                            </a>
                                            <a href="{{ route('maintenance.inventory.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors {{ request()->routeIs('maintenance.inventory.*') ? 'bg-emerald-50 text-emerald-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">📦</span> Inventory
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Reports & Analytics Dropdown --}}
                                @if(Auth::user()->hasPermission('view-reports') || Auth::user()->hasPermission('manage-sla'))
                                <div class="relative inline-block pt-5" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false"
                                       class="{{ (request()->routeIs('reports.*') || request()->routeIs('sla-policies.*')) ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        Analytics
                                        <svg class="ml-1 h-4 w-4 transition-transform duration-150" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="origin-top-left absolute left-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="py-1">
                                            @if(Auth::user()->hasPermission('view-reports'))
                                            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">📊</span> Reports
                                            </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('manage-sla'))
                                            <a href="#" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors {{ request()->routeIs('sla-policies.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                                                <span class="mr-3 text-lg">⏱️</span> SLA Policies
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <!-- User Dropdown -->
                            <div class="ml-3 relative" style="z-index: 9999;">
                                <div>
                                    <button type="button"
                                            class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                            id="user-menu-button">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white font-medium">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    </button>
                                </div>

                                <!-- Dropdown menu -->
                                <div id="user-menu" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" style="z-index: 99999;">
                                    <div class="px-4 py-2 text-xs text-gray-500">
                                        {{ Auth::user()->name }}
                                        <div class="text-gray-400">{{ Auth::user()->role->display_name }}</div>
                                    </div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-6">
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                        <div class="bg-green-50 border-l-4 border-green-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                        <div class="bg-red-50 border-l-4 border-red-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        <script>
            // User menu toggle
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', () => {
                    userMenu.classList.toggle('hidden');
                });
            }
        </script>
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
