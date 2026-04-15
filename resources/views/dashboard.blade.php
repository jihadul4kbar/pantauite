@extends('layouts.app')

@section('title', __('navigation.dashboard'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Header with Gradient Background -->
        <div class="relative mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-200 rounded-full blur-3xl transform -translate-x-16 translate-y-16"></div>
            </div>

            <div class="relative px-8 py-10">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl md:text-4xl font-bold text-white">
                                    {{ __('dashboard.welcome_back', ['name' => Auth::user()->name]) }}
                                </h1>
                                <p class="mt-1 text-green-100 text-sm md:text-base">
                                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }} • {{ __('dashboard.subtitle') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 flex-shrink-0">
                        <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-lg px-6 py-3 text-white">
                            <p class="text-xs opacity-80 text-gray-800">{{ __('dashboard.role') }}</p>
                            <p class="font-semibold text-gray-800">{{ Auth::user()->role->display_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Tickets Card -->
            <a href="{{ route('tickets.index') }}" class="group relative bg-white overflow-hidden shadow-sm hover:shadow-xl rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 group-hover:text-green-100 transition-colors mb-1">{{ __('dashboard.total_tickets') }}</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-white transition-colors">{{ $stats['tickets']['total'] }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 group-hover:border-green-400">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center text-green-600 group-hover:text-green-200">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    {{ $stats['tickets']['open'] }}
                                </span>
                                <span class="flex items-center text-yellow-600 group-hover:text-yellow-200">
                                    <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1"></span>
                                    {{ $stats['tickets']['in_progress'] }}
                                </span>
                                @if($stats['tickets']['overdue'] > 0)
                                <span class="flex items-center text-red-600 group-hover:text-red-200">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                    {{ $stats['tickets']['overdue'] }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 group-hover:text-green-200">
                            {{ __('dashboard.open') }} · {{ __('dashboard.in_progress') }} · {{ __('dashboard.overdue') }}
                        </div>
                    </div>
                </div>
            </a>

            <!-- Assets Card -->
            @if(Auth::user()->hasPermission('view-assets') || Auth::user()->hasPermission('manage-assets'))
            <a href="{{ route('assets.index') }}" class="group relative bg-white overflow-hidden shadow-sm hover:shadow-xl rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 group-hover:text-green-100 transition-colors mb-1">{{ __('dashboard.total_assets') }}</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-white transition-colors">{{ $stats['assets']['total'] }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 group-hover:border-green-400">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center text-green-600 group-hover:text-green-200">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    {{ $stats['assets']['deployed'] }}
                                </span>
                                <span class="flex items-center text-orange-600 group-hover:text-orange-200">
                                    <span class="w-2 h-2 bg-orange-500 rounded-full mr-1"></span>
                                    {{ $stats['assets']['maintenance'] }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 group-hover:text-green-200">
                            {{ __('dashboard.deployed') }} · {{ __('dashboard.maintenance') }}
                        </div>
                    </div>
                </div>
            </a>
            @endif

            <!-- SLA Compliance Card -->
            @if(Auth::user()->hasPermission('manage-sla'))
            <div class="bg-white overflow-hidden shadow-sm hover:shadow-xl rounded-2xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-shrink-0">
                            @if($stats['sla']['compliance'] >= 90)
                                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @elseif($stats['sla']['compliance'] >= 70)
                                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold
                                @if($stats['sla']['compliance'] >= 90) text-green-600
                                @elseif($stats['sla']['compliance'] >= 70) text-yellow-600
                                @else text-red-600
                                @endif">
                                {{ $stats['sla']['compliance'] }}%
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">{{ __('dashboard.sla_compliance') }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="h-2 rounded-full transition-all duration-500
                                @if($stats['sla']['compliance'] >= 90) bg-gradient-to-r from-green-500 to-emerald-600
                                @elseif($stats['sla']['compliance'] >= 70) bg-gradient-to-r from-yellow-500 to-orange-600
                                @else bg-gradient-to-r from-red-500 to-pink-600
                                @endif"
                                style="width: {{ $stats['sla']['compliance'] }}%">
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center text-green-600">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                {{ $stats['sla']['on_track'] }} {{ __('dashboard.on_track') }}
                            </span>
                            @if($stats['sla']['breached'] > 0)
                            <span class="flex items-center text-red-600">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                {{ $stats['sla']['breached'] }} {{ __('dashboard.breached') }}
                            </span>
                            @else
                            <span class="text-gray-400">0 {{ __('dashboard.breached') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Knowledge Base Card -->
            @if(Auth::user()->hasPermission('view-kb'))
            <a href="{{ route('kb.index') }}" class="group relative bg-white overflow-hidden shadow-sm hover:shadow-xl rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 group-hover:text-green-100 transition-colors mb-1">{{ __('dashboard.kb_articles') }}</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-white transition-colors">{{ $stats['kb']['published'] }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 group-hover:border-green-400">
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center text-green-600 group-hover:text-green-200">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                {{ $stats['kb']['published'] }} {{ __('dashboard.published') }}
                            </span>
                            <span class="flex items-center text-gray-500 group-hover:text-green-200">
                                <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                {{ $stats['kb']['recent'] }} {{ __('dashboard.new') }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 group-hover:text-green-200">
                            {{ __('dashboard.total') }} · {{ __('dashboard.this_week') }}
                        </div>
                    </div>
                </div>
            </a>
            @endif

            <!-- Repair Requests Card -->
            @if(Auth::user()->hasAnyPermission(['repair_requests.verify', 'manage-tickets']))
            <a href="{{ route('repair-requests.admin.index') }}" class="group relative bg-white overflow-hidden shadow-sm hover:shadow-xl rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 group-hover:text-blue-100 transition-colors mb-1">Permintaan Perbaikan</p>
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-white transition-colors">{{ $stats['repair_requests']['pending'] }}</p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 group-hover:border-blue-400">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center text-green-600 group-hover:text-blue-200">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    {{ $stats['repair_requests']['approved'] }}
                                </span>
                                @if($stats['repair_requests']['rejected'] > 0)
                                <span class="flex items-center text-red-600 group-hover:text-red-200">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                    {{ $stats['repair_requests']['rejected'] }}
                                </span>
                                @endif
                                <span class="flex items-center text-purple-600 group-hover:text-purple-200">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full mr-1"></span>
                                    {{ $stats['repair_requests']['converted'] }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 text-xs text-gray-500 group-hover:text-blue-200">
                            Menunggu · Disetujui · Dikonversi
                        </div>
                    </div>
                </div>
            </a>
            @endif
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
    </div>
</div>

@push('scripts')
<script>
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
</script>
@endpush
@endsection
