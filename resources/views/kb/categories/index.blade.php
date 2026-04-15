@extends('layouts.app')

@section('title', __('kb.categories'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('kb.index') }}" class="text-sm text-blue-600 hover:text-blue-500 mb-2 inline-block">
                ← {{ __('kb.back_to_kb_label') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('kb.manage_categories') }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ __('kb.manage_categories_subtitle') }}</p>
        </div>
        <a href="{{ route('kb.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('kb.new_category') }}
        </a>
    </div>

    <!-- Categories List -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        @if($categories->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($categories as $category)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-start flex-1">
                        <div class="text-3xl mr-4">{{ $category->icon ?? '📁' }}</div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $category->name }}
                                @if(!$category->is_active)
                                <span class="ml-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">{{ __('kb.inactive') }}</span>
                                @endif
                            </h3>
                            @if($category->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                <span>{{ $category->articles_count }} {{ Str::plural(__('kb.articles_count'), $category->articles_count) }}</span>
                                @if($category->children->count() > 0)
                                <span>•</span>
                                <span>{{ $category->children->count() }} {{ __('kb.subcategories_count') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <a href="{{ route('kb.categories.edit', $category) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 text-sm">
                            {{ __('kb.edit_label') }}
                        </a>
                        @if($category->articles_count == 0)
                        <form action="{{ route('kb.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 text-sm">
                                {{ __('kb.delete_label') }}
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400" title="{{ __('kb.cannot_delete') }}">{{ __('kb.cannot_delete') }}</span>
                        @endif
                    </div>
                </div>

                <!-- Subcategories -->
                @if($category->children->count() > 0)
                <div class="mt-4 ml-12 space-y-2">
                    @foreach($category->children as $child)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div class="flex items-center">
                            <span class="mr-2">{{ $child->icon ?? '📄' }}</span>
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $child->name }}</span>
                                @if(!$child->is_active)
                                <span class="ml-2 text-xs text-gray-500">({{ __('kb.inactive') }})</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">{{ $child->articles_count }} {{ __('kb.articles_count') }}</span>
                            <a href="{{ route('kb.categories.edit', $child) }}" class="text-xs text-blue-600 hover:text-blue-500">{{ __('kb.edit_label') }}</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('kb.no_categories') }}</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat kategori pertama Anda.</p>
            <div class="mt-6">
                <a href="{{ route('kb.categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Category
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
