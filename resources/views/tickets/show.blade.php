@extends('layouts.app')

@section('title', __('tickets.ticket_number') . ' ' . $ticket->ticket_number)

@push('styles')
<style>
    .status-badge {
        transition: all 0.3s ease;
    }
    
    .priority-badge {
        transition: all 0.3s ease;
    }
    
    .rating-btn {
        transition: all 0.2s ease;
    }
    
    .rating-btn:hover {
        transform: scale(1.1);
    }
    
    .rating-btn:active {
        transform: scale(0.95);
    }
    
    /* Timeline animations */
    .timeline-item {
        transition: all 0.3s ease;
    }
    
    .timeline-item:hover {
        transform: translateX(4px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('tickets.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">{{ __('tickets.back_to_tickets') }}</span>
            </a>

            <!-- Header Card -->
            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                </div>
                <div class="relative px-8 py-6">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                    <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $ticket->ticket_number }}</h1>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full status-badge
                                        @if($ticket->status === 'open') bg-blue-400 text-white
                                        @elseif($ticket->status === 'in_progress') bg-yellow-400 text-white
                                        @elseif($ticket->status === 'resolved') bg-green-400 text-white
                                        @elseif($ticket->status === 'closed') bg-gray-400 text-white
                                        @else bg-red-400 text-white
                                        @endif shadow-lg">
                                        {{ __('enums.ticket_status.' . $ticket->status) ?: ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full priority-badge
                                        @if($ticket->priority === 'critical') bg-red-600 text-white
                                        @elseif($ticket->priority === 'high') bg-orange-500 text-white
                                        @elseif($ticket->priority === 'medium') bg-yellow-500 text-white
                                        @else bg-green-500 text-white
                                        @endif shadow-lg">
                                        {{ __('enums.ticket_priority.' . $ticket->priority) ?: ucfirst($ticket->priority) }}
                                    </span>
                                </div>
                                <p class="text-green-100 text-lg">{{ $ticket->subject }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @can('update', $ticket)
                            <a href="{{ route('tickets.edit', $ticket) }}" class="group/btn inline-flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>{{ __('common.edit') }}</span>
                            </a>
                            @endcan
                            @can('delete', $ticket)
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group/btn inline-flex items-center space-x-2 bg-red-600 bg-opacity-80 hover:bg-opacity-100 text-white font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>{{ __('common.delete') }}</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Layout: 3 Columns -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- LEFT: Timeline Progress (2 columns on desktop) -->
            <div class="lg:col-span-2 hidden lg:block">
                <div class="sticky top-6">
                    <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                        <div class="px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ __('tickets.timeline') }}
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="relative">
                                <!-- Timeline Line -->
                                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                                
                                <!-- Timeline Items -->
                                <div class="space-y-6 relative">
                                    <!-- Created -->
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0 z-10">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs font-bold text-gray-900">{{ __('enums.ticket_status.open') }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->created_at->format('M d, H:i') }}</p>
                                            <p class="text-xs text-gray-400">{{ $ticket->user->name }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- First Response -->
                                    @if($ticket->first_response_at)
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 z-10">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs font-bold text-gray-900">{{ __('tickets.first_response') }}</p>
                                            <p class="text-xs text-gray-500">{{ $ticket->first_response_at->format('M d, H:i') }}</p>
                                            <p class="text-xs text-green-600 font-medium">{{ $ticket->created_at->diffForHumans($ticket->first_response_at, true) }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- In Progress -->
                                    @if($ticket->status === 'in_progress' || in_array($ticket->status, ['resolved', 'closed']))
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center flex-shrink-0 z-10">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs font-bold text-gray-900">{{ __('enums.ticket_status.in_progress') }}</p>
                                            @if($ticket->assignee)
                                            <p class="text-xs text-gray-500">{{ $ticket->assignee->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Resolved -->
                                    @if($ticket->status === 'resolved' || $ticket->status === 'closed')
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0 z-10">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs font-bold text-gray-900">{{ __('enums.ticket_status.resolved') }}</p>
                                            @if($ticket->resolved_at)
                                            <p class="text-xs text-gray-500">{{ $ticket->resolved_at->format('M d, H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Closed -->
                                    @if($ticket->status === 'closed')
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 rounded-full bg-gray-500 flex items-center justify-center flex-shrink-0 z-10">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-xs font-bold text-gray-900">{{ __('enums.ticket_status.closed') }}</p>
                                            @if($ticket->closed_at)
                                            <p class="text-xs text-gray-500">{{ $ticket->closed_at->format('M d, H:i') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CENTER: Main Content (7 columns on desktop) -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Section 1: Ticket Information -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ __('tickets.ticket_details') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="text-sm font-bold text-gray-700 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                {{ __('tickets.description') }}
                            </h3>
                            <div class="prose max-w-none text-gray-700 leading-relaxed bg-gray-50 rounded-xl p-4 border border-gray-200">
                                {{ nl2br(e($ticket->description)) }}
                            </div>
                        </div>

                        <!-- Initial Attachments -->
                        @if($ticket->attachments->where('comment_id', null)->count() > 0)
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                {{ __('tickets.attachments') }} ({{ $ticket->attachments->where('comment_id', null)->count() }})
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($ticket->attachments->where('comment_id', null) as $attachment)
                                    @php
                                        $filePath = $attachment->file_path;
                                        $fileName = $attachment->original_filename ?? basename($filePath);
                                        $fileSize = $attachment->file_size;
                                        $isImage = in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="group relative block overflow-hidden rounded-xl border-2 border-gray-200 hover:border-green-400 transition-colors">
                                            <img src="{{ asset('storage/' . $filePath) }}" alt="{{ $fileName }}" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="flex items-center space-x-3 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all border border-green-200 group">
                                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate">{{ $fileName }}</p>
                                                @if($fileSize)
                                                <p class="text-xs text-gray-500">{{ number_format($fileSize / 1024, 1) }} KB</p>
                                                @endif
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Metadata Tags -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $ticket->category->name ?? __('common.na') }}
                                </span>
                                @if($ticket->department)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $ticket->department->name }}
                                </span>
                                @endif
                                @if($ticket->asset)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                    </svg>
                                    {{ $ticket->asset->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Comments & Updates -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                {{ __('tickets.comments_updates') }}
                            </h2>
                            <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold rounded-full">
                                {{ $ticket->comments->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($ticket->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($ticket->comments as $comment)
                            @can('view', $ticket)
                            @if(!$comment->is_internal || auth()->user()->hasPermission('manage-tickets'))
                            <div class="group border-l-4 {{ $comment->is_internal ? 'border-yellow-400 bg-gradient-to-r from-yellow-50 to-orange-50' : 'border-green-400 bg-gradient-to-r from-green-50 to-emerald-50' }} p-5 rounded-xl hover:shadow-md transition-all">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900">{{ $comment->user->name }}</span>
                                            <div class="flex items-center text-sm text-gray-500 mt-0.5">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $comment->created_at->diffForHumans() }}
                    </div>
                </div>

                <!-- Repair Request Photos -->
                @if($ticket->repairRequest && $ticket->repairRequest->photos->count() > 0)
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            📸 Foto Permintaan Perbaikan ({{ $ticket->repairRequest->photos->count() }})
                        </h2>
                        <p class="text-xs text-gray-600 mt-1">
                            Dari: {{ $ticket->repairRequest->request_number }} - {{ $ticket->repairRequest->requester_name }}
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($ticket->repairRequest->photos as $index => $photo)
                                <div class="relative group aspect-square cursor-pointer" onclick="openRepairPhotoModal({{ $index }})">
                                    <img src="{{ $photo->url }}" 
                                         alt="Photo {{ $loop->iteration }}" 
                                         class="w-full h-full object-cover rounded-xl border-2 border-gray-200 group-hover:border-blue-500 transition-all shadow-md group-hover:shadow-lg"/>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-md">
                                        {{ $loop->iteration }} / {{ $ticket->repairRequest->photos->count() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Repair Request Photo Modal -->
                <div id="repairPhotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div class="fixed inset-0 bg-gray-900 bg-opacity-90 transition-opacity" aria-hidden="true" onclick="closeRepairPhotoModal()"></div>

                        <!-- Centering trick -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <!-- Modal panel -->
                        <div class="inline-block align-bottom bg-transparent rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                            <div class="relative">
                                <!-- Close button -->
                                <button type="button" class="absolute top-4 right-4 text-white hover:text-gray-300 focus:outline-none z-10" onclick="closeRepairPhotoModal()">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                
                                <!-- Image container -->
                                <div class="flex items-center justify-center p-4">
                                    <img id="repairModalImage" src="" alt="Full size photo" class="max-h-[80vh] max-w-full object-contain rounded-lg shadow-2xl">
                                </div>
                                
                                <!-- Navigation buttons (if more than 1 photo) -->
                                @if($ticket->repairRequest->photos->count() > 1)
                                <button type="button" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 focus:outline-none bg-black bg-opacity-30 rounded-full p-2 hover:bg-opacity-50 transition-all" onclick="prevRepairPhoto()">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button type="button" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 focus:outline-none bg-black bg-opacity-30 rounded-full p-2 hover:bg-opacity-50 transition-all" onclick="nextRepairPhoto()">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                                @endif
                            </div>
                            <div class="bg-black bg-opacity-60 px-4 py-3 sm:px-6 flex justify-between items-center">
                                <p class="text-sm text-white" id="repairPhotoCaption">Photo</p>
                                <div class="text-sm text-gray-300">
                                    <span id="currentPhotoIndex">1</span> / {{ $ticket->repairRequest->photos->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        @if($comment->is_internal)
                                        <span class="px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs font-bold rounded-full shadow-sm">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 11-18 0 4 4 0 0118 0z"></path>
                                            </svg>
                                            {{ __('tickets.internal') }}
                                        </span>
                                        @endif
                                        @if($comment->is_solution)
                                        <span class="px-3 py-1 bg-gradient-to-r from-green-400 to-emerald-600 text-white text-xs font-bold rounded-full shadow-sm">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('tickets.solution') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-gray-700 leading-relaxed ml-13">
                                    {{ nl2br(e($comment->comment)) }}
                                </div>

                                {{-- Comment Attachments --}}
                                @php
                                    $commentAttachments = $ticket->attachments->where('comment_id', $comment->id);
                                @endphp
                                @if($commentAttachments->count() > 0)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-xs font-semibold text-gray-500 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        {{ __('tickets.attachments') }} ({{ $commentAttachments->count() }})
                                    </p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach($commentAttachments as $attachment)
                                            @php
                                                $filePath = $attachment->file_path;
                                                $fileName = $attachment->original_filename ?? basename($filePath);
                                                $fileSize = $attachment->file_size;
                                                $isImage = in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            @endphp
                                            @if($isImage)
                                                <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="group relative block overflow-hidden rounded-xl border-2 border-gray-200 hover:border-green-400 transition-colors">
                                                    <img src="{{ asset('storage/' . $filePath) }}" alt="{{ $fileName }}" class="w-full h-24 object-cover group-hover:scale-105 transition-transform duration-300">
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="flex items-center space-x-2 p-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg hover:from-green-100 hover:to-emerald-100 transition-all border border-green-200">
                                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-900 truncate">{{ $fileName }}</p>
                                                        @if($fileSize)
                                                        <p class="text-xs text-gray-500">{{ number_format($fileSize / 1024, 1) }} KB</p>
                                                        @endif
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            @endcan
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">{{ __('tickets.no_comments') }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ __('tickets.first_comment') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Section 3: Solution & Evidence (Highlighted) -->
                @if($ticket->resolution_notes || $ticket->comments->where('is_solution', true)->count() > 0)
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-100 to-emerald-100 border-b border-green-200">
                        <h2 class="text-lg font-bold text-green-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('tickets.solution_evidence') }}
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($ticket->resolution_notes)
                        <div>
                            <h3 class="text-sm font-bold text-green-800 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ __('tickets.resolution_notes') }}
                            </h3>
                            <div class="bg-white rounded-xl p-4 border border-green-200 text-gray-700">
                                {{ nl2br(e($ticket->resolution_notes)) }}
                            </div>
                        </div>
                        @endif

                        @php
                            $solutionComments = $ticket->comments->where('is_solution', true);
                            $solutionAttachments = $ticket->attachments->filter(function($attachment) use ($solutionComments) {
                                return $attachment->comment_id && $solutionComments->contains('id', $attachment->comment_id);
                            });
                        @endphp

                        @if($solutionAttachments->count() > 0)
                        <div>
                            <h3 class="text-sm font-bold text-green-800 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('tickets.evidence_photos') }}
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($solutionAttachments as $attachment)
                                    @php
                                        $filePath = $attachment->file_path;
                                        $fileName = $attachment->original_filename ?? basename($filePath);
                                        $isImage = in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset('storage/' . $filePath) }}" target="_blank" class="group relative block overflow-hidden rounded-xl border-2 border-green-300 hover:border-green-500 transition-colors">
                                            <img src="{{ asset('storage/' . $filePath) }}" alt="{{ $fileName }}" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Resolution Metrics -->
                        @if($ticket->resolved_at)
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-green-200">
                            <div>
                                <p class="text-xs font-semibold text-green-700 mb-1">{{ __('tickets.resolved_at') }}</p>
                                <p class="text-sm font-bold text-gray-900">{{ $ticket->resolved_at->format('M d, Y H:i') }}</p>
                            </div>
                            @if($ticket->assignee)
                            <div>
                                <p class="text-xs font-semibold text-green-700 mb-1">{{ __('tickets.resolved_by') }}</p>
                                <p class="text-sm font-bold text-gray-900">{{ $ticket->assignee->name }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Add Comment Form -->
                @can('comment', $ticket)
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('tickets.add_comment') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('tickets.comments.add', $ticket) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label for="comment" class="block text-sm font-semibold text-gray-700 mb-2">
                                        {{ __('tickets.comment') }} <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="comment"
                                              id="comment"
                                              rows="5"
                                              required
                                              class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 resize-none"
                                              placeholder="{{ __('tickets.comment_placeholder') }}"></textarea>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    @can('addInternalNote', $ticket)
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded border-2 border-gray-300 text-yellow-600 focus:ring-yellow-500 w-5 h-5">
                                        <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-yellow-600 transition-colors">
                                            <svg class="w-4 h-4 inline mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            {{ __('tickets.add_internal_note') }}
                                        </span>
                                    </label>
                                    @endcan
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" name="is_solution" value="1" class="rounded border-2 border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                                        <span class="ml-2 text-sm font-medium text-gray-700 group-hover:text-green-600 transition-colors">
                                            <svg class="w-4 h-4 inline mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('tickets.mark_as_solution') }}
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label for="attachments" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="w-4 h-4 inline mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        {{ __('tickets.attach_files') }}
                                    </label>
                                    
                                    <!-- Camera Capture Button -->
                                    <div class="mb-3">
                                        <button type="button" onclick="document.getElementById('camera-input').click()" class="w-full sm:w-auto px-4 py-2.5 bg-gradient-to-r from-blue-500 to-cyan-600 text-white text-sm font-semibold rounded-xl hover:from-blue-600 hover:to-cyan-700 transition-all shadow-md flex items-center justify-center space-x-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>Ambil Foto dengan Kamera</span>
                                        </button>
                                        <input type="file" id="camera-input" accept="image/*" capture="environment" class="hidden" onchange="handleCameraCapture(event)">
                                    </div>
                                    
                                    <!-- Preview Container -->
                                    <div id="preview-container" class="grid grid-cols-3 gap-3 mb-3 hidden"></div>
                                    
                                    <!-- Regular File Input -->
                                    <input type="file" name="attachments[]" multiple id="attachments" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.txt,.log" class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-green-50 file:to-emerald-50 file:text-green-700 hover:file:from-green-100 hover:file:to-emerald-100 file:cursor-pointer file:transition-colors">
                                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('common.max_files') }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    {{ __('tickets.post_comment') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endcan

                <!-- Customer Satisfaction (if resolved/closed) -->
                @if(in_array($ticket->status, ['resolved', 'closed']) && !$ticket->satisfaction_rating)
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-100 to-pink-100 border-b border-purple-200">
                        <h2 class="text-lg font-bold text-purple-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('tickets.rate_service') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-purple-800 mb-4">{{ __('tickets.how_was_your_experience') }}</p>
                        <form action="{{ route('tickets.rate', $ticket) }}" method="POST">
                            @csrf
                            <div class="flex items-center justify-center space-x-3 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="selectRating({{ $i }})" class="rating-btn w-12 h-12 rounded-full border-2 border-purple-300 hover:border-purple-500 hover:bg-purple-100 transition-all text-2xl focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @if($i == 1) 😞
                                    @elseif($i == 2) 😐
                                    @elseif($i == 3) 🙂
                                    @elseif($i == 4) 😊
                                    @else 🤩
                                    @endif
                                </button>
                                @endfor
                            </div>
                            <input type="hidden" name="satisfaction_rating" id="rating-input">
                            <div>
                                <label for="satisfaction_feedback" class="block text-sm font-semibold text-purple-800 mb-2">
                                    {{ __('tickets.feedback_optional') }}
                                </label>
                                <textarea name="satisfaction_feedback"
                                          id="satisfaction_feedback"
                                          rows="3"
                                          class="w-full border-2 border-purple-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors px-4 py-3 resize-none"
                                          placeholder="{{ __('tickets.feedback_placeholder') }}"></textarea>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all shadow-md">
                                    {{ __('tickets.submit_feedback') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <!-- RIGHT: Info Panel (3 columns on desktop) -->
            <div class="lg:col-span-3">
                
                <!-- Combined Info Card (Sticky) -->
                <div class="bg-white shadow-sm rounded-2xl overflow-hidden sticky top-6">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('tickets.ticket_info') }}
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-5">
                        
                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('common.status') }}</label>
                            <div class="mb-2.5">
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full inline-block status-badge
                                    @if($ticket->status === 'open') bg-blue-400 text-white
                                    @elseif($ticket->status === 'in_progress') bg-yellow-400 text-white
                                    @elseif($ticket->status === 'resolved') bg-green-400 text-white
                                    @elseif($ticket->status === 'closed') bg-gray-400 text-white
                                    @else bg-red-400 text-white
                                    @endif shadow-sm">
                                    {{ __('enums.ticket_status.' . $ticket->status) ?: ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            @can('changeStatus', $ticket)
                            <form action="{{ route('tickets.status.change', $ticket) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="w-full text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3.5 py-2.5 transition-colors">
                                    <option value="">{{ __('tickets.update_status') }}</option>
                                    @if($ticket->isOpen() || $ticket->isReopened())
                                    <option value="in_progress">{{ __('enums.ticket_status.in_progress') }}</option>
                                    <option value="closed">{{ __('enums.ticket_status.closed') }}</option>
                                    @elseif($ticket->isInProgress())
                                    <option value="resolved">{{ __('enums.ticket_status.resolved') }}</option>
                                    <option value="open">{{ __('enums.ticket_status.open') }}</option>
                                    @elseif($ticket->isResolved())
                                    <option value="closed">{{ __('enums.ticket_status.closed') }}</option>
                                    <option value="reopened">{{ __('enums.ticket_status.reopened') }}</option>
                                    @elseif($ticket->isClosed())
                                    <option value="reopened">{{ __('enums.ticket_status.reopened') }}</option>
                                    @endif
                                </select>
                            </form>
                            @endcan
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('common.priority') }}</label>
                            <div>
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full inline-block priority-badge
                                    @if($ticket->priority === 'critical') bg-red-600 text-white
                                    @elseif($ticket->priority === 'high') bg-orange-600 text-white
                                    @elseif($ticket->priority === 'medium') bg-yellow-600 text-white
                                    @else bg-green-600 text-white
                                    @endif shadow-sm">
                                    {{ __('enums.ticket_priority.' . $ticket->priority) ?: ucfirst($ticket->priority) }}
                                </span>
                            </div>
                        </div>

                        <!-- Assignee -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('common.assigned_to') }}</label>
                            @if($ticket->assignee)
                            <div class="flex items-center space-x-3 bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 rounded-xl border border-green-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ substr($ticket->assignee->name, 0, 1) }}
                                </div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->assignee->name }}</p>
                            </div>
                            @else
                            <p class="text-sm text-gray-500 bg-gray-50 px-4 py-2.5 rounded-xl italic border border-gray-200">{{ __('tickets.unassigned') }}</p>
                            @endif
                            @can('assign', $ticket)
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" class="mt-2.5">
                                @csrf
                                <select name="assignee_id" onchange="this.form.submit()" class="w-full text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3.5 py-2.5 transition-colors">
                                    <option value="">{{ __('tickets.assign_to') }}</option>
                                    @foreach(\App\Models\User::whereRole('it_staff', 'it_manager')->active()->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                            @endcan
                        </div>

                        <!-- Reported By (Department or User) -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('tickets.reported_by') }}</label>
                            @if($ticket->department)
                            {{-- Display Department as Reporter --}}
                            <div class="flex items-center space-x-3 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 rounded-xl border border-blue-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $ticket->department->name }}</p>
                                    @if($ticket->requester_name)
                                    <p class="text-xs text-gray-600">{{ $ticket->requester_name }}</p>
                                    @endif
                                </div>
                            </div>
                            @elseif($ticket->requester_department)
                            {{-- Display Requester Department from Repair Request --}}
                            <div class="flex items-center space-x-3 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 rounded-xl border border-blue-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $ticket->requester_department }}</p>
                                    @if($ticket->requester_name)
                                    <p class="text-xs text-gray-600">{{ $ticket->requester_name }}</p>
                                    @endif
                                </div>
                            </div>
                            @else
                            {{-- Fallback to User --}}
                            <div class="flex items-center space-x-3 bg-gradient-to-r from-green-50 to-emerald-50 px-4 py-3 rounded-xl border border-green-200">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                    {{ substr($ticket->user->name, 0, 1) }}
                                </div>
                                <p class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Created At -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('tickets.created_at') }}</label>
                            <p class="text-sm font-medium text-gray-900 bg-gray-50 px-4 py-2.5 rounded-xl flex items-center border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $ticket->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>

                        <!-- Metrics -->
                        <div class="pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">{{ __('tickets.comments') }}</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $ticket->comments->count() }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">{{ __('tickets.attachments') }}</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $ticket->attachments->count() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SLA Timer Section -->
                        @if($ticket->slaPolicy)
                        <div class="pt-5 border-t border-gray-200">
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-5 border border-orange-200">
                                <h3 class="text-sm font-bold text-gray-900 flex items-center mb-4">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ __('tickets.sla_information') }}
                                </h3>
                                
                                <div class="space-y-3.5">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">{{ __('tickets.policy') }}</p>
                                        <p class="text-sm font-bold text-gray-900 bg-white px-3.5 py-2.5 rounded-lg border border-orange-200">{{ $ticket->slaPolicy->name }}</p>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2.5">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">{{ __('tickets.response_time') }}</p>
                                            <p class="text-sm font-bold text-gray-900 bg-white px-3 py-2 rounded-md text-center">{{ $ticket->slaPolicy->response_time_minutes }} min</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">{{ __('tickets.resolution_time') }}</p>
                                            <p class="text-sm font-bold text-gray-900 bg-white px-3 py-2 rounded-md text-center">{{ $ticket->slaPolicy->resolution_time_minutes }} min</p>
                                        </div>
                                    </div>

                                    @if($ticket->sla_deadline)
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wide">{{ __('tickets.sla_deadline') }}</p>
                                        <p class="text-sm font-bold text-gray-900 bg-white px-3.5 py-2.5 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $ticket->sla_deadline->format('M d, H:i') }}
                                        </p>
                                    </div>
                                    @endif

                                    <!-- SLA Status -->
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('tickets.sla_status') }}</p>
                                        @if($ticket->sla_breached)
                                        <span class="w-full px-4 py-2.5 text-xs font-bold rounded-lg inline-block bg-gradient-to-r from-red-500 to-red-600 text-white text-center shadow-sm">
                                            <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('tickets.breached') }}
                                        </span>
                                        @elseif($ticket->sla_deadline && $ticket->sla_deadline->isPast() && !in_array($ticket->status, ['closed', 'resolved']))
                                        <span class="w-full px-4 py-2.5 text-xs font-bold rounded-lg inline-block bg-gradient-to-r from-orange-500 to-orange-600 text-white text-center shadow-sm">
                                            <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            {{ __('tickets.overdue') }}
                                        </span>
                                        @else
                                        <span class="w-full px-4 py-2.5 text-xs font-bold rounded-lg inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white text-center shadow-sm">
                                            <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ __('tickets.on_track') }}
                                        </span>
                                        @endif
                                    </div>

                                    <!-- SLA Pause/Resume -->
                                    @can('update', $ticket)
                                    @if(!in_array($ticket->status, ['closed', 'resolved']) && !$ticket->sla_breached)
                                    <form action="{{ route('tickets.sla.pause', $ticket) }}" method="POST" class="pt-2.5">
                                        @csrf
                                        @if($ticket->paused_at)
                                        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-bold rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-md flex items-center justify-center space-x-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ __('tickets.resume') }}</span>
                                        </button>
                                        <p class="text-xs text-gray-500 text-center mt-2">{{ __('tickets.paused_since', ['time' => $ticket->paused_at->diffForHumans()]) }}</p>
                                        @else
                                        <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all shadow-md flex items-center justify-center space-x-2">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>{{ __('tickets.pause_sla_timer') }}</span>
                                        </button>
                                        <p class="text-xs text-gray-500 text-center mt-2">{{ __('tickets.pause_sla_hint') }}</p>
                                        @endif
                                    </form>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Related KB Article Section -->
                        <div class="pt-5 border-t border-gray-200">
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-5 border border-purple-200">
                                <h3 class="text-sm font-bold text-gray-900 flex items-center mb-4">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    {{ __('tickets.knowledge_base') }}
                                </h3>
                                
                                @if($ticket->relatedKbArticle)
                                <!-- Linked Article -->
                                <div>
                                    <div class="flex items-start justify-between mb-2.5">
                                        <a href="{{ route('kb.show', $ticket->relatedKbArticle) }}" target="_blank" class="text-sm font-bold text-purple-700 hover:text-purple-900 transition-colors">
                                            {{ $ticket->relatedKbArticle->title }}
                                        </a>
                                        @can('update', $ticket)
                                        <form action="{{ route('tickets.link-kb', $ticket->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" name="kb_article_id" value="" class="text-gray-400 hover:text-red-500 transition-colors" title="{{ __('tickets.remove_kb_link') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                    <p class="text-xs text-gray-600 mb-3 line-clamp-2">{{ Str::limit($ticket->relatedKbArticle->summary ?? $ticket->relatedKbArticle->content, 100) }}</p>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-2.5 py-1 bg-purple-200 text-purple-800 text-xs font-bold rounded-full">
                                            {{ $ticket->relatedKbArticle->category->name ?? __('common.na') }}
                                        </span>
                                        <span class="text-xs text-gray-500 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            {{ number_format($ticket->relatedKbArticle->views) }} {{ __('tickets.views') }}
                                        </span>
                                    </div>
                                </div>
                                @else
                                <!-- Link Form -->
                                @can('update', $ticket)
                                <form action="{{ route('tickets.link-kb', $ticket->id) }}" method="POST">
                                    @csrf
                                    <label class="block text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">{{ __('tickets.link_kb_article') }}</label>
                                    <select name="kb_article_id" class="w-full border-2 border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors px-3.5 py-2.5 text-sm mb-3">
                                        <option value="">{{ __('tickets.select_kb_article') }}</option>
                                        @foreach(\App\Models\KbArticle::where('status', 'published')->orderBy('title')->get() as $article)
                                        <option value="{{ $article->id }}">{{ Str::limit($article->title, 50) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-xs font-bold rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all shadow-md flex items-center justify-center space-x-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        <span>{{ __('tickets.link') }}</span>
                                    </button>
                                </form>
                                @else
                                <p class="text-sm text-gray-500 text-center py-3">{{ __('tickets.no_kb_linked') }}</p>
                                @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Rating function
function selectRating(rating) {
    document.getElementById('rating-input').value = rating;
    document.querySelectorAll('.rating-btn').forEach((btn, index) => {
        if (index < rating) {
            btn.classList.add('bg-purple-200', 'border-purple-500');
            btn.classList.remove('border-purple-300');
        } else {
            btn.classList.remove('bg-purple-200', 'border-purple-500');
            btn.classList.add('border-purple-300');
        }
    });
}

// Camera capture handler
function handleCameraCapture(event) {
    const file = event.target.files[0];
    if (!file || !file.type.startsWith('image/')) {
        return;
    }

    const previewContainer = document.getElementById('preview-container');
    previewContainer.classList.remove('hidden');

    const reader = new FileReader();
    reader.onload = function(e) {
        const div = document.createElement('div');
        div.className = 'relative group';
        div.innerHTML = `
            <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl border-2 border-blue-400" alt="Preview">
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        `;
        previewContainer.appendChild(div);
    };
    reader.readAsDataURL(file);

    // Reset camera input
    event.target.value = '';
}

// Preview for regular file uploads
document.getElementById('attachments')?.addEventListener('change', function(e) {
    const previewContainer = document.getElementById('preview-container');
    const files = Array.from(e.target.files);
    
    if (files.length > 0) {
        previewContainer.classList.remove('hidden');
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-xl border-2 border-green-400" alt="Preview">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                            <span class="text-white text-xs font-medium">${file.name}</span>
                        </div>
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            } else {
                // Non-image file preview
                const div = document.createElement('div');
                div.className = 'flex items-center space-x-2 p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200';
                div.innerHTML = `
                    <svg class="w-8 h-8 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-900 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                    </div>
                `;
                previewContainer.appendChild(div);
            }
        });
    }
});
</script>
@endpush

@endsection
