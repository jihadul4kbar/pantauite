@extends('layouts.app')

@section('title', __('reports.ticket_reports'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('reports.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">{{ __('common.back') }} {{ __('reports.title') }}</span>
            </a>

            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                </div>

                <div class="relative px-8 py-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ __('reports.ticket_reports') }}</h1>
                                <p class="mt-1 text-green-100 text-sm">Analitik dan statistik tiket komprehensif</p>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-lg px-4 py-2 text-white text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Generated in {{ $generationTime ?? 0 }}ms
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="mb-6 bg-white shadow-sm hover:shadow-lg rounded-2xl transition-shadow overflow-hidden">
            <form action="{{ route('reports.tickets') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="report_type" value="{{ $filters['report_type'] ?? 'summary' }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('reports.date_range') }} - Dari</label>
                        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('reports.date_range') }} - Sampai</label>
                        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.status') }}</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="">{{ __('common.all') }} {{ __('common.status') }}</option>
                            <option value="open" {{ ($filters['status'] ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ ($filters['status'] ?? '') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ ($filters['status'] ?? '') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.priority') }}</label>
                        <select name="priority" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="">{{ __('common.all') }} {{ __('common.priority') }}</option>
                            <option value="critical" {{ ($filters['priority'] ?? '') === 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="high" {{ ($filters['priority'] ?? '') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ ($filters['priority'] ?? '') === 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        {{ __('reports.generate') }} {{ __('reports.report_type') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Content -->
        @if(isset($reportData['summary']) || isset($reportData))
        <!-- Summary Statistics -->
        @if(isset($reportData['summary']))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $reportData['summary']['total'] ?? $reportData['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @if(isset($reportData['summary']['sla_compliance']))
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kepatuhan SLA</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $reportData['summary']['sla_compliance'] ?? 0 }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif
            @if(isset($reportData['summary']['avg_resolution_time']))
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rata-rata Resolusi</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $reportData['summary']['avg_resolution_time'] ?? '-' }}h</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            @endif
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Berdasarkan {{ __('common.status') }}</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">{{ count($reportData['summary']['by_status'] ?? []) }} jenis</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Detailed Data Table -->
        @if(isset($reportData['tickets']))
        <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900">Detail Tiket</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tiket</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('common.status') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">{{ __('common.priority') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Penerima</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">SLA</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($reportData['tickets'] as $ticket)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $ticket->ticket_number }}</div>
                                <div class="text-sm text-gray-500 truncate">{{ Str::limit($ticket->subject, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                    @if($ticket->status === 'open') bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                    @elseif($ticket->status === 'in_progress') bg-gradient-to-r from-yellow-500 to-orange-600 text-white
                                    @elseif($ticket->status === 'resolved') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                    @elseif($ticket->status === 'closed') bg-gradient-to-r from-gray-500 to-gray-600 text-white
                                    @else bg-gradient-to-r from-red-500 to-red-600 text-white
                                    @endif shadow-sm">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                    @if($ticket->priority === 'critical') bg-gradient-to-r from-red-500 to-pink-600 text-white
                                    @elseif($ticket->priority === 'high') bg-gradient-to-r from-orange-500 to-orange-600 text-white
                                    @elseif($ticket->priority === 'medium') bg-gradient-to-r from-yellow-500 to-yellow-600 text-white
                                    @else bg-gradient-to-r from-green-500 to-green-600 text-white
                                    @endif shadow-sm">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $ticket->assignee->name ?? 'Belum Ditugaskan' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->sla_breached)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-red-500 to-red-600 text-white shadow-sm">Dilanggar</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-sm">Tepat Waktu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- SLA Compliance Detail -->
        @if(isset($reportData['by_priority']))
        <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900">Kepatuhan SLA berdasarkan Prioritas</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($reportData['by_priority'] as $priority => $data)
                    <div class="bg-gradient-to-r from-gray-50 to-white rounded-xl p-5 border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-2">{{ ucfirst($priority) }}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total</span>
                                <span class="font-bold">{{ $data['total'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Dilanggar</span>
                                <span class="font-bold text-red-600">{{ $data['breached'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Patuh</span>
                                <span class="font-bold text-green-600">{{ $data['compliant'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="h-2 rounded-full bg-gradient-to-r from-green-500 to-emerald-600" style="width: {{ $data['compliance_rate'] }}%"></div>
                            </div>
                            <p class="text-xs text-center text-gray-500 mt-1">{{ $data['compliance_rate'] }}% patuh</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
