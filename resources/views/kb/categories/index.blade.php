@extends('layouts.app')

@section('title', __('kb.categories'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <a href="{{ route('kb.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-800 hover:text-gray-400 mb-4 transition-colors">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">{{ __('kb.back_to_kb_label') }}</span>
        </a>
        <div class="relative mb-8 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-2xl shadow-xl overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-200 rounded-full blur-3xl transform -translate-x-16 translate-y-16"></div>
            </div>

            <div class="relative px-8 py-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ __('kb.manage_categories') }}</h1>
                                <p class="mt-1 text-purple-100 text-sm">{{ __('kb.manage_categories_subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('kb.categories.create') }}" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>{{ __('kb.new_category') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        @if($categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Kategori</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $categories->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Artikel</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $categories->sum('articles_count') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm hover:shadow-md rounded-xl p-5 transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kategori Aktif</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $categories->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Categories List -->
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
            @if($categories->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($categories as $category)
                <div class="group p-6 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1">
                            <div class="text-4xl mr-4 group-hover:scale-110 transition-transform duration-300">{{ $category->icon ?? '📁' }}</div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    {{ $category->name }}
                                    @if(!$category->is_active)
                                    <span class="ml-2 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full">{{ __('kb.inactive') }}</span>
                                    @endif
                                </h3>
                                @if($category->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $category->articles_count }} {{ Str::plural(__('kb.articles_count'), $category->articles_count) }}
                                    </span>
                                    @if($category->children->count() > 0)
                                    <span>•</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                        </svg>
                                        {{ $category->children->count() }} {{ __('kb.subcategories_count') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <a href="{{ route('kb.categories.edit', $category) }}" class="group/btn inline-flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white text-sm font-semibold rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>{{ __('kb.edit_label') }}</span>
                            </a>
                            @if($category->articles_count == 0)
                            <form action="{{ route('kb.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group/btn inline-flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>{{ __('kb.delete_label') }}</span>
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400 px-3 py-2 bg-gray-100 rounded-lg" title="{{ __('kb.cannot_delete') }}">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ __('kb.cannot_delete') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Subcategories -->
                    @if($category->children->count() > 0)
                    <div class="mt-4 ml-14 space-y-2">
                        @foreach($category->children as $child)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 hover:border-purple-300 hover:shadow-sm transition-all">
                            <div class="flex items-center">
                                <span class="mr-3 text-2xl group-hover:scale-110 transition-transform">{{ $child->icon ?? '📄' }}</span>
                                <div>
                                    <span class="text-sm font-bold text-gray-900">{{ $child->name }}</span>
                                    @if(!$child->is_active)
                                    <span class="ml-2 text-xs text-gray-500 font-medium">({{ __('kb.inactive') }})</span>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $child->articles_count }} {{ __('kb.articles_count') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('kb.categories.edit', $child) }}" class="group/btn inline-flex items-center space-x-2 px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md transform hover:scale-105">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span>{{ __('kb.edit_label') }}</span>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="p-16 text-center">
                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-purple-100 to-indigo-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('kb.no_categories') }}</h3>
                <p class="text-gray-500 mb-6">Mulai dengan membuat kategori pertama Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('kb.categories.create') }}" class="group inline-flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>{{ __('kb.create_category_btn') }}</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
