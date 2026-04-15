@extends('layouts.app')

@section('title', __('maintenance.schedules.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="relative mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
            </div>
            <div class="relative px-8 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('maintenance.schedules.title') }}</h1>
                            <p class="mt-1 text-green-100 text-sm">{{ __('maintenance.schedules.subtitle') }}</p>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <form action="{{ route('maintenance.schedules.generate-tasks') }}" method="POST">
                            @csrf
                            <button type="submit" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>{{ __('maintenance.schedules.generate_tasks') }}</span>
                            </button>
                        </form>
                        <a href="{{ route('maintenance.schedules.create') }}" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                            <svg class="w-6 h-6 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ __('maintenance.schedules.add_schedule') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden mb-6 p-4">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('maintenance.schedules.asset') }}</label>
                    <select name="asset_id" class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">{{ __('maintenance.schedules.all_assets') }}</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->asset_code }} - {{ $asset->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('common.status') }}</label>
                    <select name="is_active" class="border-2 border-gray-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">{{ __('common.all') }}</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>{{ __('maintenance.schedules.active') }}</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>{{ __('maintenance.schedules.inactive') }}</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all">{{ __('common.filter') }}</button>
            </form>
        </div>

        <!-- Schedules Table -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
            @if($schedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.schedules.schedule') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.schedules.asset') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.schedules.frequency') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.schedules.next_due') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.schedules.assigned_to') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('common.status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($schedules as $schedule)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $schedule->name }}</div>
                                <div class="text-xs text-gray-500">{{ $schedule->maintenance_type }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $schedule->asset->asset_code }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($schedule->asset->name, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $schedule->frequency_display }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold {{ $schedule->isOverdue() ? 'text-red-600' : ($schedule->isDueSoon() ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $schedule->next_due_date->format('d M Y') }}
                                </span>
                                @if($schedule->isOverdue())
                                <div class="text-xs text-red-500">{{ __('maintenance.schedules.overdue') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $schedule->assignedUser?->name ?? __('common.na') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full {{ $schedule->is_active ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-gray-400 to-gray-500 text-white' }}">
                                    {{ $schedule->is_active ? __('maintenance.schedules.active') : __('maintenance.schedules.inactive') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('maintenance.schedules.edit', $schedule) }}" class="px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700">{{ __('maintenance.schedules.edit') }}</a>
                                    <form action="{{ route('maintenance.schedules.destroy', $schedule) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('maintenance.schedules.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-semibold rounded-lg hover:from-red-600 hover:to-red-700">{{ __('maintenance.schedules.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                {{ $schedules->links() }}
            </div>
            @else
            <div class="p-16 text-center">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('maintenance.schedules.no_schedules') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('maintenance.schedules.get_started') }}</p>
                <a href="{{ route('maintenance.schedules.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('maintenance.schedules.add_schedule') }}</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
