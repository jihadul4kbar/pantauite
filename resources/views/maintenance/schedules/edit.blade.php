@extends('layouts.app')

@section('title', __('common.edit') . ' ' . __('maintenance.schedules.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('maintenance.schedules.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">{{ __('common.back') }} {{ __('maintenance.schedules.title') }}</span>
            </a>
            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="relative px-8 py-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('common.edit') }} {{ __('maintenance.schedules.schedule') }}: {{ $schedule->name }}</h1>
                            <p class="mt-1 text-green-100">{{ __('maintenance.schedules.update_schedule', 'Perbarui jadwal pemeliharaan') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('maintenance.schedules.update', $schedule) }}" method="POST" class="bg-white shadow-sm rounded-2xl overflow-hidden">
            @csrf @method('PUT')
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('common.basic_information', 'Informasi Dasar') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $schedule->name) }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.description') }}</label>
                            <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">{{ old('description', $schedule->description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.asset') }} <span class="text-red-500">*</span></label>
                            <select name="asset_id" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                                @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id', $schedule->asset_id) == $asset->id ? 'selected' : '' }}>{{ $asset->asset_code }} - {{ $asset->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.type') }}</label>
                            <select name="maintenance_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                                <option value="preventive" {{ old('maintenance_type', $schedule->maintenance_type) == 'preventive' ? 'selected' : '' }}>Preventif</option>
                                <option value="corrective" {{ old('maintenance_type', $schedule->maintenance_type) == 'corrective' ? 'selected' : '' }}>Korektif</option>
                                <option value="predictive" {{ old('maintenance_type', $schedule->maintenance_type) == 'predictive' ? 'selected' : '' }}>Prediktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('maintenance.schedules.schedule') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.frequency') }}</label>
                            <select name="frequency_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                                <option value="daily" {{ old('frequency_type', $schedule->frequency_type) == 'daily' ? 'selected' : '' }}>{{ __('enums.maintenance_frequency.daily', 'Harian') }}</option>
                                <option value="weekly" {{ old('frequency_type', $schedule->frequency_type) == 'weekly' ? 'selected' : '' }}>{{ __('enums.maintenance_frequency.weekly', 'Mingguan') }}</option>
                                <option value="monthly" {{ old('frequency_type', $schedule->frequency_type) == 'monthly' ? 'selected' : '' }}>{{ __('enums.maintenance_frequency.monthly', 'Bulanan') }}</option>
                                <option value="yearly" {{ old('frequency_type', $schedule->frequency_type) == 'yearly' ? 'selected' : '' }}>{{ __('enums.maintenance_frequency.annual', 'Tahunan') }}</option>
                                <option value="custom" {{ old('frequency_type', $schedule->frequency_type) == 'custom' ? 'selected' : '' }}>{{ __('maintenance.schedules.custom', 'Kustom') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.frequency') }}</label>
                            <input type="number" name="frequency_value" value="{{ old('frequency_value', $schedule->frequency_value) }}" min="1" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.next_due') }}</label>
                            <input type="date" name="next_due_date" value="{{ old('next_due_date', $schedule->next_due_date->format('Y-m-d')) }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.est_duration', 'Est. Durasi (mnt)') }}</label>
                            <input type="number" name="estimated_duration_minutes" value="{{ old('estimated_duration_minutes', $schedule->estimated_duration_minutes) }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('maintenance.schedules.assignment_cost', 'Penugasan & Biaya') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.assigned_to') }}</label>
                            <select name="assigned_to_user_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                                <option value="">{{ __('maintenance.tasks.unassigned') }}</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to_user_id', $schedule->assigned_to_user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.vendor') }}</label>
                            <select name="vendor_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                                <option value="">{{ __('common.none') }}</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $schedule->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.est_cost', 'Est. Biaya') }}</label>
                            <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $schedule->estimated_cost) }}" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.schedules.approval_threshold', 'Batas Persetujuan') }}</label>
                            <input type="number" name="approval_threshold" value="{{ old('approval_threshold', $schedule->approval_threshold) }}" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 w-5 h-5">
                                <span class="ml-3 text-sm font-medium">{{ __('maintenance.schedules.active') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5 bg-gray-50 border-t flex justify-end space-x-3">
                <a href="{{ route('maintenance.schedules.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700">{{ __('common.update') }} {{ __('maintenance.schedules.schedule') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
