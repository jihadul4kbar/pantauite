@extends('layouts.app')

@section('title', __('navigation.assets'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="relative mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-green-200 rounded-full blur-3xl transform -translate-x-16 translate-y-16"></div>
            </div>

            <div class="relative px-8 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">Manajemen Aset</h1>
                            <p class="mt-1 text-green-100 text-sm">Kelola inventaris dan siklus hidup aset TI</p>
                        </div>
                    </div>
                    @if(Auth::user()->hasPermission('manage-assets'))
                    <a href="{{ route('assets.create') }}" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>{{ __('assets.add_asset') }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('assets.total_assets') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $assets->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('assets.deployed') }}</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $assets->where('status', 'deployed')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('assets.inventory') }}</p>
                        <p class="text-2xl font-bold text-gray-600 mt-1">{{ $assets->where('status', 'inventory')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('assets.maintenance') }}</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $assets->where('status', 'maintenance')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-white shadow-sm hover:shadow-lg rounded-2xl transition-shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('assets.filters_search') }}</h2>
                </div>
            </div>
            <form method="GET" action="{{ route('assets.index') }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('common.search') }}
                        </label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="{{ __('assets.search_placeholder') }}" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            {{ __('common.type') }}
                        </label>
                        <select name="type" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="">{{ __('common.all') }}</option>
                            <option value="hardware" {{ ($filters['type'] ?? '') == 'hardware' ? 'selected' : '' }}>Perangkat Keras</option>
                            <option value="software" {{ ($filters['type'] ?? '') == 'software' ? 'selected' : '' }}>Perangkat Lunak</option>
                            <option value="network" {{ ($filters['type'] ?? '') == 'network' ? 'selected' : '' }}>Jaringan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('common.status') }}
                        </label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="">{{ __('common.all') }}</option>
                            <option value="procurement" {{ ($filters['status'] ?? '') == 'procurement' ? 'selected' : '' }}>Pengadaan</option>
                            <option value="inventory" {{ ($filters['status'] ?? '') == 'inventory' ? 'selected' : '' }}>Inventaris</option>
                            <option value="deployed" {{ ($filters['status'] ?? '') == 'deployed' ? 'selected' : '' }}>Diterapkan</option>
                            <option value="maintenance" {{ ($filters['status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                            <option value="retired" {{ ($filters['status'] ?? '') == 'retired' ? 'selected' : '' }}>Pensiun</option>
                            <option value="disposed" {{ ($filters['status'] ?? '') == 'disposed' ? 'selected' : '' }}>Dibuang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            {{ __('common.per_page') }}
                        </label>
                        <select name="per_page" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 {{ __('common.per_page') }}</option>
                            <option value="10" {{ !request('per_page') || request('per_page') == 10 ? 'selected' : '' }}>10 {{ __('common.per_page') }}</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 {{ __('common.per_page') }}</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 {{ __('common.per_page') }}</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        {{ __('common.apply') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Assets Table -->
        <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('assets.asset_code') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('assets.asset_name') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('common.type') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('common.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('assets.assigned_to') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('common.warranty') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($assets as $asset)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200 group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-green-600 group-hover:text-green-700">{{ $asset->asset_code }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $asset->name }}</div>
                                @if($asset->brand || $asset->model)
                                <div class="text-sm text-gray-500">{{ $asset->brand }} {{ $asset->model }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                    @if($asset->asset_type == 'hardware') bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                    @elseif($asset->asset_type == 'software') bg-gradient-to-r from-purple-500 to-purple-600 text-white
                                    @else bg-gradient-to-r from-green-500 to-green-600 text-white
                                    @endif shadow-sm">
                                    {{ ucfirst($asset->asset_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                    @if($asset->status == 'deployed') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                    @elseif($asset->status == 'inventory') bg-gradient-to-r from-gray-500 to-gray-600 text-white
                                    @elseif($asset->status == 'maintenance') bg-gradient-to-r from-yellow-500 to-orange-600 text-white
                                    @elseif($asset->status == 'procurement') bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                    @else bg-gradient-to-r from-red-500 to-red-600 text-white
                                    @endif shadow-sm">
                                    {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($asset->assignedUser)
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                                            {{ substr($asset->assignedUser->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-900 font-medium">{{ $asset->assignedUser->name }}</span>
                                    </div>
                                @elseif($asset->assignedDepartment)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-sm text-gray-700">{{ $asset->assignedDepartment->name }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">{{ __('assets.unassigned') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($asset->warranty_end)
                                    @if($asset->isUnderWarranty())
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-green-600 font-medium">{{ $asset->warranty_end->format('d M Y') }}</span>
                                        </div>
                                        @if($asset->warrantyExpiringInDays(30))
                                            <div class="text-xs text-orange-600 font-semibold mt-1">{{ __('assets.expiring_soon') }}</div>
                                        @endif
                                    @else
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm text-red-600 font-semibold">{{ __('assets.expired') }}</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">{{ __('common.na') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('assets.show', $asset) }}" class="group/btn inline-flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>{{ __('common.view') }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('assets.no_assets') }}</h3>
                                <p class="text-gray-500">{{ __('assets.get_started_assets') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($assets->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700">
                        {{ __('assets.showing_results', ['first' => $assets->firstItem(), 'last' => $assets->lastItem(), 'total' => $assets->total()]) }}
                    </div>
                    {{ $assets->links() }}
                </div>
            </div>
            @elseif($assets->total() > 0)
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200 text-sm text-gray-700">
                {{ __('common.total') }}: <span class="font-bold text-gray-900">{{ $assets->total() }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
