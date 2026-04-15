@extends('layouts.app')

@section('title', __('reports.kb_reports'))

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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ __('reports.kb_reports') }}</h1>
                                <p class="mt-1 text-green-100 text-sm">Analitik artikel, umpan balik, dan statistik penggunaan</p>
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

        <!-- Summary Statistics -->
        @if(is_array($reportData) && isset($reportData['total']))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Artikel</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $reportData['total'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('common.published') }}</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $reportData['published'] ?? 0 }}</p>
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
                        <p class="text-sm font-medium text-gray-600">Total Tampilan</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($reportData['total_views'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Suara Membantu</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ number_format($reportData['total_helpful_votes'] ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filter Form -->
        <div class="mb-6 bg-white shadow-sm hover:shadow-lg rounded-2xl transition-shadow overflow-hidden">
            <form action="{{ route('reports.kb') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="report_type" value="{{ $filters['report_type'] ?? 'summary' }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.status') }}</label>
                        <select name="status" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="">{{ __('common.all') }} {{ __('common.status') }}</option>
                            <option value="draft" {{ ($filters['status'] ?? '') === 'draft' ? 'selected' : '' }}>{{ __('common.draft') }}</option>
                            <option value="published" {{ ($filters['status'] ?? '') === 'published' ? 'selected' : '' }}>{{ __('common.published') }}</option>
                            <option value="archived" {{ ($filters['status'] ?? '') === 'archived' ? 'selected' : '' }}>{{ __('common.archived') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.category') }}</label>
                        <select name="category_id" class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3">
                            <option value="">{{ __('common.all') }} {{ __('common.category') }}</option>
                            @foreach($filterOptions['categories'] ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            {{ __('reports.generate') }} {{ __('reports.report_type') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Most Viewed Articles -->
        @if(is_array($reportData) && isset($reportData['most_viewed']))
        <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900">Artikel Paling Dilihat</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($reportData['most_viewed'] as $index => $article)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">{{ $index + 1 }}</div>
                        <div>
                            <a href="{{ route('kb.show', $article) }}" class="text-sm font-semibold text-gray-900 hover:text-green-600">{{ $article->title }}</a>
                            <p class="text-xs text-gray-500">{{ $article->category->name ?? 'Tanpa Kategori' }} • Oleh {{ $article->author->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-bold text-blue-600">{{ number_format($article->views) }} tampilan</span>
                        @php
                            $totalVotes = $article->helpful_votes + $article->not_helpful_votes;
                            $helpfulPercent = $totalVotes > 0 ? round(($article->helpful_votes / $totalVotes) * 100) : 0;
                        @endphp
                        <span class="text-xs text-gray-500">{{ $helpfulPercent }}% membantu</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Articles with Negative Feedback -->
        @if(is_array($reportData) && isset($reportData['negative_feedback']))
        <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow mt-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-lg font-bold text-gray-900">Artikel Perlu Perbaikan (Umpan Balik Rendah)</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($reportData['negative_feedback'] as $article)
                @php
                    $totalVotes = $article->helpful_votes + $article->not_helpful_votes;
                    $helpfulPercent = $totalVotes > 0 ? round(($article->helpful_votes / $totalVotes) * 100) : 0;
                @endphp
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <a href="{{ route('kb.show', $article) }}" class="text-sm font-semibold text-gray-900 hover:text-green-600">{{ $article->title }}</a>
                            <p class="text-xs text-gray-500">{{ $article->category->name ?? 'Tanpa Kategori' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-bold text-red-600">{{ $helpfulPercent }}% membantu</span>
                        <span class="text-xs text-gray-500">{{ $totalVotes }} suara</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
