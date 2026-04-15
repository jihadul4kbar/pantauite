@extends('layouts.app')

@section('title', __('reports.title'))

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
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ __('reports.title') }}</h1>
                        <p class="mt-1 text-green-100 text-sm">{{ __('reports.subtitle') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($availableReports as $categoryKey => $category)
            <div class="bg-white shadow-sm hover:shadow-xl rounded-2xl overflow-hidden transition-all duration-300 transform hover:-translate-y-1">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center space-x-3">
                        <div class="text-3xl">{{ $category['icon'] }}</div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">{{ $category['name'] }}</h2>
                            <p class="text-xs text-gray-500">{{ $category['description'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($category['reports'] as $reportKey => $reportName)
                        @if($categoryKey === 'tickets')
                        <form action="{{ route('reports.tickets') }}" method="POST">
                            @csrf
                            <input type="hidden" name="report_type" value="{{ str_replace(' ', '_', strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $reportKey))) }}">
                            <button type="submit" class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all text-left group">
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">{{ $reportName }}</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                        @elseif($categoryKey === 'assets')
                        <form action="{{ route('reports.assets') }}" method="POST">
                            @csrf
                            <input type="hidden" name="report_type" value="{{ str_replace(' ', '_', strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $reportKey))) }}">
                            <button type="submit" class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all text-left group">
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">{{ $reportName }}</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                        @elseif($categoryKey === 'kb')
                        <form action="{{ route('reports.kb') }}" method="POST">
                            @csrf
                            <input type="hidden" name="report_type" value="{{ str_replace(' ', '_', strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $reportKey))) }}">
                            <button type="submit" class="w-full flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all text-left group">
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">{{ $reportName }}</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Recent Reports -->
        @if($recentReports->count() > 0)
        <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('reports.recent_reports') }}</h2>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentReports as $report)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ str_replace('_', ' ', ucfirst($report->report_type)) }}</p>
                            <p class="text-xs text-gray-500">{{ __('reports.generated_by') }} {{ $report->generatedBy->name ?? __('reports.unknown') }} • {{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold rounded-full">{{ ucfirst($report->format) }}</span>
                        @if($report->generation_time_ms)
                        <span class="text-xs text-gray-500">{{ $report->generation_time_ms }}ms</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
