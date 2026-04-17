@extends('layouts.app')

@section('title', __('common.create') . ' ' . __('maintenance.tasks.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('maintenance.tasks.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="font-medium">{{ __('common.back') }} {{ __('maintenance.tasks.title') }}</span>
            </a>
            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="relative px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('common.create') }} {{ __('maintenance.tasks.title') }}</h1>
                            <p class="mt-1 text-green-100">{{ __('maintenance.tasks.create_subtitle') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('maintenance.tasks.store') }}" method="POST" class="bg-white shadow-sm rounded-2xl overflow-hidden">
            @csrf
            <div class="p-6 space-y-6">
                <!-- Basic Info -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        {{ __('common.basic_information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.task_title') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('title') border-red-500 @enderror">
                            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.description') }}</label>
                            <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.asset') }} <span class="text-red-500">*</span></label>
                            <select name="asset_id" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('asset_id') border-red-500 @enderror">
                                <option value="">{{ __('common.select') }} {{ __('maintenance.tasks.asset') }}</option>
                                @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>{{ $asset->asset_code }} - {{ $asset->name }}</option>
                                @endforeach
                            </select>
                            @error('asset_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.schedule_optional') }}</label>
                            <select name="schedule_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">{{ __('maintenance.tasks.no_schedule') }}</option>
                                @foreach($schedules as $schedule)
                                <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Type & Priority -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        {{ __('maintenance.tasks.type_priority') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.maintenance_type') }} <span class="text-red-500">*</span></label>
                            <select name="maintenance_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="preventive" {{ old('maintenance_type') == 'preventive' ? 'selected' : '' }}>{{ __('maintenance.tasks.preventive') }}</option>
                                <option value="corrective" {{ old('maintenance_type') == 'corrective' ? 'selected' : '' }}>{{ __('maintenance.tasks.corrective') }}</option>
                                <option value="predictive" {{ old('maintenance_type') == 'predictive' ? 'selected' : '' }}>{{ __('maintenance.tasks.predictive') }}</option>
                                <option value="emergency" {{ old('maintenance_type') == 'emergency' ? 'selected' : '' }}>{{ __('maintenance.tasks.emergency') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.priority') }} <span class="text-red-500">*</span></label>
                            <select name="priority" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('enums.maintenance_task_priority.low') }}</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>{{ __('enums.maintenance_task_priority.medium') }}</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('enums.maintenance_task_priority.high') }}</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>{{ __('enums.maintenance_task_priority.critical') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Scheduling & Assignment -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        {{ __('maintenance.tasks.scheduling_assignment') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.scheduled_date') }} <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date" value="{{ old('scheduled_date') }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.assigned_to_multiple') }}</label>
                            <select name="assigned_to_user_ids[]" multiple class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500" size="5">
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('assigned_to_user_ids', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role->display_name }})
                                </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">{{ __('maintenance.tasks.select_multiple_hint') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.vendor_external') }}</label>
                            <select name="vendor_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">{{ __('common.none') }}</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.est_cost') }}</label>
                            <input type="number" name="estimated_cost" value="{{ old('estimated_cost') }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-white border-t border-gray-100 flex justify-end space-x-3">
                <a href="{{ route('maintenance.tasks.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-md">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ __('maintenance.tasks.create_task') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
