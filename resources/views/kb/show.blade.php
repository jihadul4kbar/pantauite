@extends('layouts.app')

@section('title', $kb->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('kb.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">{{ __('kb.back_to_kb_label') }}</span>
            </a>

            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                </div>

                <div class="relative px-8 py-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                        <div class="flex items-start space-x-4 mb-4 md:mb-0">
                            <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $kb->article_number }}</h1>
                                    @if($kb->is_featured)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-400 text-white shadow-lg">
                                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        {{ __('kb.featured_article') }}
                                    </span>
                                    @endif
                                    @if($kb->is_internal)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-400 text-white shadow-lg">{{ __('kb.internal_label') }}</span>
                                    @endif
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        @if($kb->status === 'published') bg-green-400 text-white
                                        @elseif($kb->status === 'draft') bg-gray-400 text-white
                                        @else bg-orange-400 text-white
                                        @endif shadow-lg">
                                        {{ ucfirst($kb->status) === 'Published' ? __('kb.status_published') : (ucfirst($kb->status) === 'Draft' ? __('kb.status_draft') : __('kb.status_archived')) }}
                                    </span>
                                    @if($kb->version > 1)
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-400 text-white shadow-lg">v{{ $kb->version }}</span>
                                    @endif
                                </div>
                                <p class="text-green-100 text-lg">{{ $kb->title }}</p>
                                <div class="flex items-center gap-4 mt-2 text-sm text-green-100">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $kb->author?->name ?? __('kb.author_label') }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $kb->category?->name ?? __('kb.uncategorized') }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $kb->published_at?->translatedFormat('d M Y') ?? __('kb.status_draft') }}
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        {{ number_format($kb->views) }} {{ __('kb.views_label') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            @can('update', $kb)
                            <a href="{{ route('kb.edit', $kb) }}" class="group/btn inline-flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>{{ __('kb.edit_label_show') }}</span>
                            </a>
                            @endcan
                            @can('delete', $kb)
                            <form action="{{ route('kb.destroy', $kb) }}" method="POST" onsubmit="return confirm('{{ __('kb.confirm_delete_article', ['title' => addslashes($kb->title)]) }}')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group/btn inline-flex items-center space-x-2 bg-red-600 bg-opacity-80 hover:bg-opacity-100 text-white font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>{{ __('kb.delete_label') }}</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                @if($kb->summary)
                <div class="p-6 border-b border-gray-200 bg-green-50">
                    <h2 class="text-sm font-semibold text-green-900 mb-2">{{ __('kb.summary_show_label') }}</h2>
                    <p class="text-sm text-green-800">{{ $kb->summary }}</p>
                </div>
                @endif

                <div class="p-6">
                    <div class="prose max-w-none kb-content">
                        {!! $kb->content !!}
                    </div>

                    {{-- Attached Files Section --}}
                    @php
                        // Extract images from content that were uploaded via CKEditor
                        preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i', $kb->content, $matches);
                        $hasUploadedImages = false;
                        if (isset($matches[1]) && count($matches[1]) > 0) {
                            foreach ($matches[1] as $imgSrc) {
                                if (strpos($imgSrc, '/storage/articles/') !== false) {
                                    $hasUploadedImages = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    @if($hasUploadedImages)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Attached Images
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($matches[1] as $imgSrc)
                            @if(strpos($imgSrc, '/storage/articles/') !== false)
                            <div class="group relative">
                                <a href="{{ asset(str_replace(url('/'), '', $imgSrc)) }}" target="_blank" class="block overflow-hidden rounded-xl border-2 border-gray-200 hover:border-green-400 transition-colors">
                                    <img src="{{ asset(str_replace(url('/'), '', $imgSrc)) }}" alt="KB Attachment" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                </a>
                                <p class="text-xs text-gray-500 mt-1 truncate">{{ basename(parse_url($imgSrc, PHP_URL_PATH)) }}</p>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                @if($kb->changelog)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700">{{ __('kb.latest_changes_label') }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $kb->changelog }}</p>
                </div>
                @endif

                <!-- Voting Section -->
                <div class="px-6 py-4 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Was this article helpful?</h3>
                    @if($kb && $kb->id)
                    <form action="{{ url('kb/' . $kb->id . '/vote') }}" method="POST">
                        @csrf
                        <div class="flex items-center gap-4">
                            <button type="submit" name="vote_type" value="helpful"
                                    class="px-4 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"/>
                                </svg>
                                Helpful ({{ $kb->helpful_votes }})
                            </button>
                            <button type="submit" name="vote_type" value="not_helpful"
                                    class="px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"/>
                                </svg>
                                Not Helpful ({{ $kb->not_helpful_votes }})
                            </button>
                        </div>
                    </form>
                    @endif

                    @php
                        $total = $kb->helpful_votes + $kb->not_helpful_votes;
                        $percentage = $total > 0 ? round(($kb->helpful_votes / $total) * 100) : 0;
                    @endphp
                    @if($total > 0)
                    <div class="mt-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <span>{{ $percentage }}% found this helpful</span>
                            <span>({{ $total }} votes)</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('kb.related_articles_label') }}</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($relatedArticles as $related)
                    <a href="{{ route('kb.show', $related) }}" class="block p-4 hover:bg-gray-50 transition">
                        <h3 class="text-sm font-medium text-green-600">{{ $related->title }}</h3>
                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($related->summary ?? $related->content, 100) }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Article Info -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('kb.article_info_label') }}</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('kb.category_info_label') }}</label>
                            <a href="{{ route('kb.index', ['category_id' => $kb->category_id]) }}" class="mt-1 text-sm text-green-600 hover:text-green-500">
                                {{ $kb->category?->icon ?? '📄' }} {{ $kb->category?->name ?? __('kb.uncategorized') }}
                            </a>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('kb.author_label') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $kb->author?->name ?? __('kb.author_label') }}</p>
                        </div>
                        @if($kb->reviewed_by)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('kb.reviewed_by_label') }}</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $kb->reviewer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $kb->reviewed_at?->format('M d, Y') }}</p>
                        </div>
                        @endif
                        @if($kb->tags && count($kb->tags) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">{{ __('kb.tags_label') }}</label>
                            <div class="mt-1 flex flex-wrap gap-2">
                                @foreach($kb->tags as $tag)
                                <a href="{{ route('kb.index', ['tag' => $tag]) }}" class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                                    {{ $tag }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">{{ __('kb.categories_sidebar_label') }}</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach(\App\Models\KbCategory::active()->withCount('articles')->get() as $cat)
                    <a href="{{ route('kb.index', ['category_id' => $cat->id]) }}" class="block px-6 py-3 hover:bg-gray-50 transition flex items-center justify-between">
                        <span class="text-sm text-gray-700">{{ $cat->icon }} {{ $cat->name }}</span>
                        <span class="text-xs text-gray-500">{{ $cat->articles_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
