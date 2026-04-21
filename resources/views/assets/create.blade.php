@extends('layouts.app')

@section('title', __('assets.add_new'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('assets.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">{{ __('assets.back_to_assets') }}</span>
            </a>

            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                </div>

                <div class="relative px-8 py-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('assets.add_new') }}</h1>
                            <p class="mt-1 text-green-100">{{ __('assets.create_subtitle') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.basic_info') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.asset_type') }} <span class="text-red-500 ml-1">*</span></label>
                                <select name="asset_type" required class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('asset_type') border-red-500 @enderror">
                                    <option value="">{{ __('assets.select_type') }}</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type->value }}" {{ old('asset_type') == $type->value ? 'selected' : '' }}>
                                        {{ ucfirst($type->value) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('asset_type') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.status') }} <span class="text-red-500 ml-1">*</span></label>
                                <select name="status" required class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('status') border-red-500 @enderror">
                                    <option value="inventory" {{ old('status') == 'inventory' ? 'selected' : '' }}>{{ __('enums.asset_status.inventory') }}</option>
                                    <option value="procurement" {{ old('status') == 'procurement' ? 'selected' : '' }}>{{ __('enums.asset_status.procurement') }}</option>
                                    <option value="deployed" {{ old('status') == 'deployed' ? 'selected' : '' }}>{{ __('enums.asset_status.deployed') }}</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>{{ __('enums.asset_status.maintenance') }}</option>
                                    <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>{{ __('enums.asset_status.retired') }}</option>
                                    <option value="disposed" {{ old('status') == 'disposed' ? 'selected' : '' }}>{{ __('enums.asset_status.disposed') }}</option>
                                </select>
                                @error('status') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.asset_name') }} <span class="text-red-500 ml-1">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('name') border-red-500 @enderror">
                                @error('name') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.brand') }}</label>
                                <input type="text" name="brand" value="{{ old('brand') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.model') }}</label>
                                <input type="text" name="model" value="{{ old('model') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.serial_number') }}</label>
                                <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.select_condition') }} <span class="text-red-500 ml-1">*</span></label>
                                <select name="condition" required class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('condition') border-red-500 @enderror">
                                    <option value="">{{ __('assets.select_condition') }}</option>
                                    @foreach($conditions as $cond)
                                    <option value="{{ $cond }}" {{ old('condition') == $cond ? 'selected' : '' }}>
                                        {{ ucfirst($cond) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('condition') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.location') }}</label>
                                <input type="text" name="location" value="{{ old('location') }}" placeholder="{{ __('assets.location_placeholder') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Information -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.purchase_info') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.select_vendor') }}</label>
                                <select name="vendor_id" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                                    <option value="">{{ __('assets.select_vendor') }}</option>
                                    @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.date') }}</label>
                                <input type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.price_idr') }}</label>
                                <input type="number" name="price" value="{{ old('price') }}" step="0.01"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.po_number') }}</label>
                                <input type="text" name="purchase_order_number" value="{{ old('purchase_order_number') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warranty Information -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.warranty_info') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.warranty_start') }}</label>
                                <input type="date" name="warranty_start" value="{{ old('warranty_start') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.warranty_end') }}</label>
                                <input type="date" name="warranty_end" value="{{ old('warranty_end') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.warranty_provider') }}</label>
                                <input type="text" name="warranty_provider" value="{{ old('warranty_provider') }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignment -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.assignment') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.assigned_to_user') }}</label>
                                <select name="assigned_to_user_id" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                                    <option value="">-- {{ __('assets.unassigned') }} --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to_user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('assets.assign_to_user') }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.assigned_to_department') }}</label>
                                <select name="assigned_to_department_id" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                                    <option value="">-- {{ __('common.none') }} --</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('assigned_to_department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('assets.assign_to_department') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Depreciation -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.depreciation') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.depreciation_method') }}</label>
                                <select name="depreciation_method" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                                    @foreach($depreciationMethods as $method)
                                    <option value="{{ $method }}" {{ old('depreciation_method', 'straight_line') == $method ? 'selected' : '' }}>
                                        {{ str_replace('_', ' ', ucfirst($method)) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('assets.useful_life') }}</label>
                                <input type="number" name="useful_life_years" value="{{ old('useful_life_years') }}" min="1"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.additional_notes') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <textarea name="notes" rows="4" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 resize-none">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Asset Images -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('assets.asset_images') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">{{ __('assets.upload_images') }}</label>
                                <p class="text-xs text-gray-500 mb-4 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('assets.min_images_required') }}
                                </p>
                                
                                <!-- Sequential Camera Slots -->
                                <div id="camera-slots" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <!-- Slots will be generated by JavaScript -->
                                </div>
                                
                                <!-- Hidden file input -->
                                <input type="file" id="camera-input" name="images[]" accept="image/jpeg,image/png,image/jpg,image/gif" capture="user" class="hidden">
                                
                                @error('images') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                                @error('images.0') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <!-- Progress Indicator -->
                            <div id="upload-progress" class="hidden">
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border-2 border-green-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-green-800">Progress Upload</span>
                                        <span id="progress-text" class="text-sm font-bold text-green-600">0/5</span>
                                    </div>
                                    <div class="w-full bg-green-100 rounded-full h-3 overflow-hidden">
                                        <div id="progress-bar" class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p id="progress-message" class="text-xs text-green-600 mt-2 text-center">Klik slot kosong untuk mengambil foto</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 bg-white shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <p class="text-sm text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('common.required_fields') }}
                    </p>
                    <div class="flex space-x-3">
                        <a href="{{ route('assets.index') }}" class="group px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors shadow-sm">
                            <svg class="w-4 h-4 inline mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ __('common.cancel') }}
                        </a>
                        <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                            <svg class="w-5 h-5 inline mr-1 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('assets.create_asset') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Sequential Camera Capture System
    const TOTAL_SLOTS = 5;
    const capturedImages = new Array(TOTAL_SLOTS).fill(null);
    const cameraInput = document.getElementById('camera-input');
    const slotsContainer = document.getElementById('camera-slots');
    const progressContainer = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const progressMessage = document.getElementById('progress-message');
    let currentSlotIndex = 0;

    // Initialize slots
    function initializeSlots() {
        slotsContainer.innerHTML = '';
        for (let i = 0; i < TOTAL_SLOTS; i++) {
            const slot = createSlot(i);
            slotsContainer.appendChild(slot);
        }
    }

    // Create individual slot
    function createSlot(index) {
        const div = document.createElement('div');
        div.className = 'relative aspect-square bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-green-500 hover:from-green-50 hover:to-emerald-50 transition-all duration-300 group';
        div.dataset.index = index;
        
        div.innerHTML = `
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <div class="slot-icon">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span class="slot-text mt-2 text-xs font-medium text-gray-500 group-hover:text-green-700 transition-colors">Foto ${index + 1}</span>
                <span class="slot-subtext text-xs text-gray-400 mt-1">Klik untuk ambil</span>
            </div>
            <img class="preview-image hidden w-full h-full object-cover rounded-xl" alt="Preview">
            <div class="remove-button hidden absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 transition-colors shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div class="retake-button hidden absolute bottom-2 left-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full p-1.5 transition-colors shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.058A5.568 5.568 0 0110.165 4m0 0a5.55 5.55 0 014.536 2.336M4 16v-5h.058a5.568 5.568 0 00-5.332 4.665m0 0a5.55 5.55 0 004.536-2.336"></path>
                </svg>
            </div>
        `;
        
        div.addEventListener('click', (e) => handleSlotClick(e, index));
        return div;
    }

    // Handle slot click
    function handleSlotClick(e, index) {
        if (capturedImages[index]) {
            return; // Don't allow clicking filled slots directly
        }
        currentSlotIndex = index;
        cameraInput.click();
    }

    // Handle file input change
    cameraInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            capturedImages[currentSlotIndex] = {
                file: file,
                dataUrl: event.target.result
            };
            updateSlot(currentSlotIndex);
            updateProgress();
            cameraInput.value = ''; // Reset for next capture
            
            // Auto-focus next empty slot
            const nextIndex = capturedImages.findIndex((img, idx) => idx > currentSlotIndex && !img);
            if (nextIndex !== -1) {
                currentSlotIndex = nextIndex;
            } else {
                const firstEmpty = capturedImages.findIndex(img => !img);
                if (firstEmpty !== -1) {
                    currentSlotIndex = firstEmpty;
                }
            }
            
            // Check if all slots are filled
            if (capturedImages.every(img => img !== null)) {
                progressMessage.textContent = '✅ Semua foto sudah diupload! Form siap disubmit.';
                progressMessage.classList.add('font-bold');
            }
        };
        reader.readAsDataURL(file);
    });

    // Update individual slot
    function updateSlot(index) {
        const slots = slotsContainer.querySelectorAll('[data-index]');
        const slot = slots[index];
        const img = slot.querySelector('.preview-image');
        const icon = slot.querySelector('.slot-icon');
        const text = slot.querySelector('.slot-text');
        const subtext = slot.querySelector('.slot-subtext');
        const removeBtn = slot.querySelector('.remove-button');
        const retakeBtn = slot.querySelector('.retake-button');
        
        if (capturedImages[index]) {
            img.src = capturedImages[index].dataUrl;
            img.classList.remove('hidden');
            icon.classList.add('hidden');
            text.classList.add('hidden');
            subtext.classList.add('hidden');
            removeBtn.classList.remove('hidden');
            retakeBtn.classList.remove('hidden');
            slot.classList.remove('border-dashed', 'border-gray-300');
            slot.classList.add('border-solid', 'border-green-400');
            
            // Remove button click
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                capturedImages[index] = null;
                updateSlot(index);
                updateProgress();
            });
            
            // Retake button click
            retakeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                currentSlotIndex = index;
                cameraInput.click();
            });
        }
    }

    // Update progress bar
    function updateProgress() {
        const filledCount = capturedImages.filter(img => img !== null).length;
        const percentage = (filledCount / TOTAL_SLOTS) * 100;
        
        progressContainer.classList.remove('hidden');
        progressBar.style.width = percentage + '%';
        progressText.textContent = filledCount + '/' + TOTAL_SLOTS;
        
        if (filledCount === 0) {
            progressMessage.textContent = 'Klik slot kosong untuk mengambil foto';
        } else if (filledCount < TOTAL_SLOTS) {
            progressMessage.textContent = 'Lanjut! ' + (TOTAL_SLOTS - filledCount) + ' foto lagi';
        }
    }

    // Create hidden file inputs for form submission
    function prepareForSubmission() {
        // Remove old inputs
        document.querySelectorAll('input[name="images[]"]').forEach(el => el.remove());
        
        // Create new inputs with captured images
        capturedImages.forEach((img, index) => {
            if (img) {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'images[]';
                input.style.display = 'none';
                
                // Create a File object from the data URL
                fetch(img.dataUrl)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], img.file.name, { type: img.file.type });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                    });
                
                document.querySelector('form').appendChild(input);
            }
        });
    }

    // Initialize on page load
    initializeSlots();
    
    // Prepare files before form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const filledCount = capturedImages.filter(img => img !== null).length;
        
        if (filledCount < TOTAL_SLOTS) {
            e.preventDefault();
            alert('⚠️ Wajib upload tepat 5 gambar! Anda baru mengupload ' + filledCount + ' gambar.');
            return false;
        }
        
        prepareForSubmission();
    });
</script>
@endpush
@endsection
