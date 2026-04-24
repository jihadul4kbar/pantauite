@extends('layouts.app')

@section('title', __('navigation.dashboard'))

@section('content')
<div class="max-w-7xl mx-auto pt-20 py-2 lg:py-4">
    <!-- Welcome Header: UCD (Personalized & Contextual) -->
    <div class="relative mb-6 lg:mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl lg:rounded-3xl shadow-xl overflow-hidden group">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-20 transition-transform duration-1000 group-hover:scale-110">
            <div class="absolute top-0 right-0 w-64 lg:w-96 h-64 lg:h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
            <div class="absolute bottom-0 left-0 w-48 lg:w-64 h-48 lg:h-64 bg-green-200 rounded-full blur-3xl transform -translate-x-16 translate-y-16"></div>
        </div>

        <div class="relative px-6 py-8 lg:px-10 lg:py-12">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-center space-x-4 lg:space-x-6">
                    <div class="flex-shrink-0 w-16 h-16 lg:w-20 lg:h-20 bg-white/20 backdrop-blur-xl rounded-2xl lg:rounded-3xl flex items-center justify-center border border-white/30 shadow-2xl transition-transform group-hover:rotate-3">
                        <svg class="w-10 h-10 lg:w-12 lg:h-12 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-4xl font-extrabold text-white tracking-tight drop-shadow-sm">
                            {{ __('dashboard.welcome_back', ['name' => Auth::user()->name]) }}
                        </h1>
                        <p class="mt-1 lg:mt-2 text-green-50/90 text-sm lg:text-lg font-medium">
                            {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} <span class="mx-1 hidden lg:inline opacity-50">|</span> <span class="block lg:inline">{{ __('dashboard.subtitle') }}</span>
                        </p>
                    </div>
                </div>
                <div class="w-full md:w-auto">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 lg:p-5 border border-white/20 shadow-inner group-hover:bg-white/20 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-xs lg:text-sm font-bold text-white uppercase tracking-widest opacity-80">{{ __('dashboard.role') }}</span>
                        </div>
                        <p class="mt-1 text-lg lg:text-xl font-black text-white drop-shadow-sm">{{ Auth::user()->role->display_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid: Responsive 2-cols on mobile, 4-cols on desktop -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mb-8">
        <!-- Tickets Card -->
        <a href="{{ route('tickets.index') }}" class="group relative bg-white rounded-2xl lg:rounded-3xl p-4 lg:p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:border-emerald-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 lg:w-14 lg:h-14 bg-emerald-50 rounded-xl lg:rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <span class="flex items-center text-[10px] lg:text-xs font-bold text-slate-400 group-hover:text-emerald-500 transition-colors uppercase tracking-widest">Live</span>
            </div>
            <p class="text-xs lg:text-sm font-bold text-slate-500 mb-1">{{ __('dashboard.total_tickets') }}</p>
            <div class="flex items-end space-x-2">
                <h3 class="text-2xl lg:text-4xl font-black text-slate-900">{{ $stats['tickets']['total'] }}</h3>
                <span class="text-[10px] lg:text-xs font-bold text-emerald-500 mb-1.5">+{{ $stats['tickets']['open'] }}</span>
            </div>
        </a>

        <!-- Assets Card -->
        @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-assets'))
        <a href="{{ route('assets.index') }}" class="group relative bg-white rounded-2xl lg:rounded-3xl p-4 lg:p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:border-blue-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 lg:w-14 lg:h-14 bg-blue-50 rounded-xl lg:rounded-2xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="flex items-center text-[10px] lg:text-xs font-bold text-slate-400 group-hover:text-blue-500 transition-colors uppercase tracking-widest">Asset</span>
            </div>
            <p class="text-xs lg:text-sm font-bold text-slate-500 mb-1">{{ __('dashboard.total_assets') }}</p>
            <div class="flex items-end space-x-2">
                <h3 class="text-2xl lg:text-4xl font-black text-slate-900">{{ $stats['assets']['total'] }}</h3>
                <span class="text-[10px] lg:text-xs font-bold text-blue-500 mb-1.5">{{ $stats['assets']['deployed'] }}</span>
            </div>
        </a>
        @endif

        <!-- SLA Compliance Card -->
        @if(Auth::user()->hasPermission('manage-sla'))
        <div class="group relative bg-white rounded-2xl lg:rounded-3xl p-4 lg:p-6 shadow-sm border border-slate-100 transition-all duration-300">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 lg:w-14 lg:h-14 bg-orange-50 rounded-xl lg:rounded-2xl flex items-center justify-center text-orange-600 transition-all shadow-sm">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="flex items-center text-[10px] lg:text-xs font-bold text-slate-400 uppercase tracking-widest">SLA</span>
            </div>
            <p class="text-xs lg:text-sm font-bold text-slate-500 mb-1">Performance</p>
            <div class="flex items-end space-x-2">
                <h3 class="text-2xl lg:text-4xl font-black text-slate-900">{{ $stats['sla']['compliance'] }}%</h3>
                <span class="text-[10px] lg:text-xs font-bold text-orange-500 mb-1.5">{{ $stats['sla']['on_track'] }} OK</span>
            </div>
        </div>
        @endif

        <!-- Repair Requests Card -->
        @if(Auth::user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets']))
        <a href="{{ route('repair-requests.admin.index') }}" class="group relative bg-white rounded-2xl lg:rounded-3xl p-4 lg:p-6 shadow-sm border border-slate-100 hover:shadow-2xl hover:border-purple-200 transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 lg:w-14 lg:h-14 bg-purple-50 rounded-xl lg:rounded-2xl flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-sm">
                    <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <span class="flex items-center text-[10px] lg:text-xs font-bold text-slate-400 group-hover:text-purple-500 transition-colors uppercase tracking-widest">Req</span>
            </div>
            <p class="text-xs lg:text-sm font-bold text-slate-500 mb-1">Repairs</p>
            <div class="flex items-end space-x-2">
                <h3 class="text-2xl lg:text-4xl font-black text-slate-900">{{ $stats['repair_requests']['pending'] }}</h3>
                <span class="text-[10px] lg:text-xs font-bold text-purple-500 mb-1.5">New</span>
            </div>
        </a>
        @endif
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Priority Distribution Chart -->
        <div class="bg-white shadow-sm rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Prioritas Tiket</h2>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="bg-white shadow-sm rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Kategori Tiket</h2>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- SLA Performance Timeline -->
    <div class="bg-white shadow-sm rounded-2xl p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900">SLA Performance (7 Hari Terakhir)</h2>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="text-xs text-gray-600">Met SLA</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="text-xs text-gray-600">Breached</span>
                </div>
            </div>
        </div>
        <div class="relative h-72">
            <canvas id="slaTimelineChart"></canvas>
        </div>
    </div>

        <!-- Quick Actions Section -->
        <div class="mb-8 bg-white shadow-sm hover:shadow-lg rounded-2xl transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('dashboard.quick_actions') }}</h2>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @if(Auth::user()->hasPermission('create-tickets'))
                    <a href="{{ route('tickets.create') }}" class="group relative overflow-hidden p-5 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-green-200 opacity-20 rounded-full blur-xl transform translate-x-8 -translate-y-8"></div>
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-md">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-900 mb-1">{{ __('dashboard.new_ticket') }}</p>
                            <p class="text-xs text-gray-600">{{ __('dashboard.new_ticket_desc') }}</p>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('manage-kb'))
                    <a href="{{ route('kb.create') }}" class="group relative overflow-hidden p-5 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-green-200 opacity-20 rounded-full blur-xl transform translate-x-8 -translate-y-8"></div>
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-md">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-900 mb-1">{{ __('dashboard.add_article') }}</p>
                            <p class="text-xs text-gray-600">{{ __('dashboard.add_article_desc') }}</p>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('manage-kb'))
                    <a href="{{ route('kb.categories.index') }}" class="group relative overflow-hidden p-5 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-green-200 opacity-20 rounded-full blur-xl transform translate-x-8 -translate-y-8"></div>
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-md">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-900 mb-1">{{ __('dashboard.kb_categories') }}</p>
                            <p class="text-xs text-gray-600">{{ __('dashboard.kb_categories_desc') }}</p>
                        </div>
                    </a>
                    @endif

                    @if(Auth::user()->hasPermission('view-own-tickets') || Auth::user()->hasPermission('view-all-tickets'))
                    <a href="{{ route('tickets.index') }}" class="group relative overflow-hidden p-5 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl hover:from-green-100 hover:to-emerald-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-green-200 opacity-20 rounded-full blur-xl transform translate-x-8 -translate-y-8"></div>
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-md">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <p class="font-semibold text-gray-900 mb-1">{{ __('dashboard.view_tickets') }}</p>
                            <p class="text-xs text-gray-600">{{ __('dashboard.view_tickets_desc') }}</p>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feature Modules Grid -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('dashboard.feature_modules') }}</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Tickets Module -->
                @if(Auth::user()->hasPermission('view-own-tickets') || Auth::user()->hasPermission('view-all-tickets'))
                <div class="group bg-white shadow-sm hover:shadow-xl rounded-2xl p-6 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('navigation.tickets') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('dashboard.view_tickets_desc') }}</p>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700 hover:underline">{{ __('dashboard.view_tickets') }}</a>
                                <span class="text-gray-300">•</span>
                                @if(Auth::user()->hasPermission('create-tickets'))
                                <a href="{{ route('tickets.create') }}" class="text-sm font-medium text-green-600 hover:text-green-700 hover:underline">{{ __('dashboard.new_ticket') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Knowledge Base Module -->
                @if(Auth::user()->hasPermission('view-kb'))
                <div class="group bg-white shadow-sm hover:shadow-xl rounded-2xl p-6 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('navigation.knowledge_base') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('dashboard.add_article_desc') }}</p>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('kb.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700 hover:underline">{{ __('dashboard.view_tickets') }}</a>
                                <span class="text-gray-300">•</span>
                                @if(Auth::user()->hasPermission('manage-kb'))
                                <a href="{{ route('kb.create') }}" class="text-sm font-medium text-green-600 hover:text-green-700 hover:underline">{{ __('dashboard.new_ticket') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Asset Management Module -->
                @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-assets'))
                <a href="{{ route('assets.index') }}" class="group bg-white shadow-sm hover:shadow-xl rounded-2xl p-6 transition-all duration-300 transform hover:-translate-y-1 block">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('navigation.assets') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">{{ __('dashboard.view_tickets_desc') }}</p>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-green-600 group-hover:text-green-700 hover:underline">{{ __('dashboard.view_tickets') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endif

                <!-- Reports Module (Coming Soon) -->
                @if(Auth::user()->hasPermission('view-reports'))
                <div class="bg-white shadow-sm rounded-2xl p-6 opacity-75">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ __('navigation.reports') }}</h3>
                                <span class="ml-2 px-3 py-1 text-xs font-semibold bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full shadow-sm">Coming Soon</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">{{ __('dashboard.view_tickets_desc') }}</p>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-400 italic">Module under development</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- System Status Banner -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 shadow-lg rounded-2xl overflow-hidden">
            <div class="px-6 py-5">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-white mb-1">
                            {{ __('dashboard.subtitle') }}
                        </h3>
                        <p class="text-sm text-green-100">
                            PantauITE v1.0 — {{ __('dashboard.subtitle') }}
                        </p>
                    </div>
                    <div class="hidden sm:flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg rounded-lg px-4 py-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-gray-800">{{ __('common.active') }}</span>
                    </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    console.log('Chart.js loaded:', typeof Chart !== 'undefined');
    
    // Add animation on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-4');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.group').forEach((el) => {
        el.classList.add('transition-all', 'duration-500');
        observer.observe(el);
    });

    // Chart.js Configuration
    console.log('Chart data:', @json($chartData ?? []));
    const chartColors = {
        priority: {
            critical: 'rgba(239, 68, 68, 0.8)',
            high: 'rgba(249, 115, 22, 0.8)',
            medium: 'rgba(251, 191, 36, 0.8)',
            low: 'rgba(34, 197, 94, 0.8)',
        },
        category: [
            'rgba(59, 130, 246, 0.8)',
            'rgba(147, 51, 234, 0.8)',
            'rgba(236, 72, 153, 0.8)',
            'rgba(20, 184, 166, 0.8)',
            'rgba(99, 102, 241, 0.8)',
        ],
        sla: {
            met: 'rgba(34, 197, 94, 0.8)',
            breached: 'rgba(239, 68, 68, 0.8)',
        }
    };

    // Priority Chart (Doughnut)
    const priorityElement = document.getElementById('priorityChart');
    if (priorityElement) {
    const priorityCtx = priorityElement.getContext('2d');
    const priorityDataRaw = @json($chartData['priority'] ?? []);
    const priorityData = priorityDataRaw || {};
    console.log('Priority data:', priorityData);
    
    new Chart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(priorityData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
            datasets: [{
                data: Object.values(priorityData),
                backgroundColor: [
                    chartColors.priority.critical,
                    chartColors.priority.high,
                    chartColors.priority.medium,
                    chartColors.priority.low,
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.raw / total) * 100).toFixed(1);
                            return context.label + ': ' + context.raw + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
    } else {
        console.error('Priority chart canvas not found');
    }

    // Category Chart (Bar)
    const categoryElement = document.getElementById('categoryChart');
    if (categoryElement) {
    const categoryCtx = categoryElement.getContext('2d');
    const categoryDataRaw = @json($chartData['category'] ?? []);
    const categoryData = categoryDataRaw || {};
    console.log('Category data:', categoryData);
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(categoryData),
            datasets: [{
                label: 'Jumlah Tiket',
                data: Object.values(categoryData),
                backgroundColor: chartColors.category,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { size: 11 }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        font: { size: 11 }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    } else {
        console.error('Category chart canvas not found');
    }

    // SLA Timeline Chart (Stacked Bar)
    const slaElement = document.getElementById('slaTimelineChart');
    if (slaElement) {
    const slaCtx = slaElement.getContext('2d');
    const slaDataRaw = @json($chartData['sla_timeline'] ?? []);
    const slaData = slaDataRaw || {};
    console.log('SLA data:', slaData);
    const labels = Object.keys(slaData);
    const metData = labels.map(label => slaData[label].met);
    const breachedData = labels.map(label => slaData[label].breached);
    
    new Chart(slaCtx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Met SLA',
                    data: metData,
                    backgroundColor: chartColors.sla.met,
                    borderRadius: 8,
                    borderSkipped: false,
                },
                {
                    label: 'Breached',
                    data: breachedData,
                    backgroundColor: chartColors.sla.breached,
                    borderRadius: 8,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    cornerRadius: 8,
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        font: { size: 11 }
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { size: 11 }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
    } else {
        console.error('SLA timeline chart canvas not found');
    }
</script>
@endpush
@endsection
