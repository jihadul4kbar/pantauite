@extends('layouts.app')

@section('title', __('kb.title'))

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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('kb.title') }}</h1>
                            <p class="mt-1 text-green-100 text-sm">{{ __('kb.subtitle') }}</p>
                        </div>
                    </div>
                    @can('create', \App\Models\KbArticle::class)
                    <div class="flex space-x-3">
                        <a href="{{ route('kb.create') }}" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ __('kb.new_article') }}</span>
                        </a>
                        <a href="{{ route('kb.categories.index') }}" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>{{ __('kb.categories') }}</span>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-8 bg-white shadow-lg rounded-2xl overflow-hidden">
            <form method="GET" action="{{ route('kb.index') }}" class="p-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('kb.search_placeholder') }}" class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-lg">
                    </div>
                    <select name="category_id" class="md:w-64 border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-4">
                        <option value="">{{ __('kb.all_categories') }}</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->icon }} {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="md:w-auto px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        {{ __('kb.search') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($categories as $category)
            <a href="{{ route('kb.index', ['category_id' => $category->id]) }}" class="group bg-white p-6 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-start space-x-4">
                    <div class="text-4xl group-hover:scale-110 transition-transform duration-300">{{ $category->icon ?? '📄' }}</div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-600 transition-colors mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($category->description, 80) }}</p>
                        <div class="flex items-center text-sm font-semibold text-green-600">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $category->articles_count }} {{ Str::plural(__('kb.article'), $category->articles_count) }}
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Articles List -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">{{ __('kb.articles') }}</h2>
                </div>
            </div>

            @if($articles->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($articles as $article)
                <div class="group p-6 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <a href="{{ route('kb.show', $article) }}" class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $article->article_number }}</span>
                                @if($article->is_featured)
                                <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-sm">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    {{ __('kb.featured') }}
                                </span>
                                @endif
                                @if($article->is_internal)
                                <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold rounded-full shadow-sm">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    {{ __('kb.internal') }}
                                </span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-600 transition-colors mb-2">{{ $article->title }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($article->summary ?? $article->content, 150) }}</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $article->category->name }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $article->author->name }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $article->published_at?->diffForHumans() ?? __('kb.draft') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ $article->views }} {{ __('kb.views') }}
                                </span>
                            </div>
                        </a>
                        <div class="ml-6 flex flex-col items-end space-y-2">
                            @if($helpful = $article->helpful_votes + $article->not_helpful_votes)
                            <div class="mb-2 text-right">
                                @php
                                    $percentage = round(($article->helpful_votes / $helpful) * 100);
                                @endphp
                                <div class="text-2xl font-bold {{ $percentage >= 70 ? 'text-green-600' : ($percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $percentage }}%
                                </div>
                                <div class="text-xs text-gray-500">{{ $helpful }} {{ __('kb.votes') }}</div>
                            </div>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500
                                    @if($percentage >= 70) bg-gradient-to-r from-green-500 to-emerald-600
                                    @elseif($percentage >= 50) bg-gradient-to-r from-yellow-500 to-orange-600
                                    @else bg-gradient-to-r from-red-500 to-red-600
                                    @endif"
                                    style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex space-x-2 mt-2">
                                @can('update', $article)
                                <a href="{{ route('kb.edit', $article) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-sm" title="Ubah Artikel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>{{ __('kb.edit_label') }}</span>
                                </a>
                                @endcan
                                @if(Auth::user()->hasRole('super_admin'))
                                <form action="{{ route('kb.destroy', $article) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, '{{ __('kb.confirm_delete_article', ['title' => addslashes($article->title)]) }}')" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-sm" title="Hapus Artikel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>{{ __('kb.delete_label') }}</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-bold text-gray-900">{{ $articles->firstItem() }}</span> sampai <span class="font-bold text-gray-900">{{ $articles->lastItem() }}</span> dari <span class="font-bold text-gray-900">{{ $articles->total() }}</span> hasil
                    </div>
                    {{ $articles->links() }}
                </div>
            </div>
            @else
            <div class="p-16 text-center">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('kb.no_articles_found_index') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('kb.get_started_article_index') }}</p>
                @can('create', \App\Models\KbArticle::class)
                <a href="{{ route('kb.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>{{ __('kb.create_new_article_btn') }}</span>
                </a>
                @endcan
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
