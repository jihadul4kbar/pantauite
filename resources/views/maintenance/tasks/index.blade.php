@extends('layouts.app')

@section('title', __('maintenance.tasks.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="relative mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
            <div class="absolute inset-0 opacity-20"><div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div></div>
            <div class="relative px-8 py-8">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ __('maintenance.tasks.title') }}</h1>
                        <p class="mt-1 text-green-100">{{ __('maintenance.tasks.subtitle') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('common.status') }}</label>
                    <select name="status" class="border-2 border-gray-200 rounded-xl px-3 py-2">
                        <option value="">{{ __('maintenance.tasks.all') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('maintenance.tasks.pending') }}</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>{{ __('maintenance.tasks.scheduled') }}</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>{{ __('maintenance.tasks.in_progress') }}</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('maintenance.tasks.completed') }}</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>{{ __('maintenance.tasks.overdue') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('maintenance.tasks.asset') }}</label>
                    <select name="asset_id" class="border-2 border-gray-200 rounded-xl px-3 py-2">
                        <option value="">{{ __('common.all') }}</option>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>{{ $asset->asset_code }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('maintenance.tasks.create') }}" class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700">+ {{ __('maintenance.tasks.new_task') }}</a>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @if($tasks->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.tasks.task') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.tasks.asset') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.tasks.scheduled_date') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.tasks.assigned_to') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('maintenance.tasks.priority') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('common.status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tasks as $task)
                        <tr class="hover:bg-green-50 transition-all">
                            <td class="px-6 py-4">
                                <a href="{{ route('maintenance.tasks.show', $task) }}" class="text-sm font-bold text-green-600 hover:text-green-800">{{ $task->task_number }}</a>
                                <div class="text-xs text-gray-500">{{ Str::limit($task->title, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $task->asset?->asset_code ?? __('common.na') }}</td>
                            <td class="px-6 py-4 text-sm {{ $task->isOverdue() ? 'text-red-600 font-semibold' : '' }}">{{ $task->scheduled_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                @if($task->assigned_users->count() > 0)
                                    @foreach($task->assigned_users as $user)
                                    <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-medium mr-1 mb-1">{{ Str::limit($user->name, 15) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-400">{{ __('maintenance.tasks.unassigned') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    @if($task->priority == 'critical') bg-red-100 text-red-800
                                    @elseif($task->priority == 'high') bg-orange-100 text-orange-800
                                    @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    @if($task->status == 'completed') bg-green-100 text-green-800
                                    @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($task->status == 'overdue') bg-red-100 text-red-800
                                    @elseif($task->status == 'scheduled') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('maintenance.tasks.show', $task) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition-colors">{{ __('maintenance.tasks.view') }}</a>
                                    @if(in_array($task->status, ['pending', 'scheduled']))
                                    <a href="{{ route('maintenance.tasks.execute', $task) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">{{ __('maintenance.tasks.execute') }}</a>
                                    @endif
                                    @if(Auth::user()->hasRole('it_manager') || Auth::user()->hasRole('super_admin'))
                                    <form action="{{ route('maintenance.tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('maintenance.tasks.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-700 hover:bg-red-100 transition-colors">{{ __('maintenance.tasks.delete') }}</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t bg-gray-50">{{ $tasks->links() }}</div>
            @else
            <div class="p-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <h3 class="text-lg font-bold text-gray-900">{{ __('maintenance.tasks.no_tasks') }}</h3>
                <p class="text-gray-500">{{ __('maintenance.tasks.get_started') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
