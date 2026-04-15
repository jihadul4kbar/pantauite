@extends('layouts.app')

@section('title', $asset->asset_code . ' - ' . $asset->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('assets.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Back to Assets</span>
            </a>

            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                </div>

                <div class="relative px-8 py-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                        <div class="flex items-start space-x-4 mb-4 md:mb-0">
                            <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center flex-shrink-0">
                                @if($asset->asset_type == 'hardware')
                                <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                @elseif($asset->asset_type == 'software')
                                <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                </svg>
                                @else
                                <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                </svg>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <h1 class="text-2xl md:text-3xl font-bold text-white">{{ $asset->asset_code }}</h1>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        @if($asset->status == 'deployed') bg-green-400 text-white
                                        @elseif($asset->status == 'inventory') bg-gray-400 text-white
                                        @elseif($asset->status == 'maintenance') bg-yellow-400 text-white
                                        @elseif($asset->status == 'procurement') bg-blue-400 text-white
                                        @elseif($asset->status == 'retired') bg-orange-400 text-white
                                        @else bg-red-400 text-white
                                        @endif shadow-lg">
                                        {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                    </span>
                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                        @if($asset->asset_type == 'hardware') bg-blue-400 text-white
                                        @elseif($asset->asset_type == 'software') bg-purple-400 text-white
                                        @else bg-emerald-400 text-white
                                        @endif shadow-lg">
                                        {{ ucfirst($asset->asset_type) }}
                                    </span>
                                    @if($asset->isUnderWarranty())
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-400 text-white shadow-lg">
                                        {{ $asset->warrantyDaysRemaining() }} days left
                                    </span>
                                    @endif
                                </div>
                                <p class="text-green-100 text-lg">{{ $asset->name }} - {{ $asset->brand }} {{ $asset->model }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            @can('update', $asset)
                            <a href="{{ route('assets.edit', $asset) }}" class="group/btn inline-flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit</span>
                            </a>
                            @endcan
                            @can('delete', $asset)
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete &quot;{{ addslashes($asset->name) }}&quot; ({{ $asset->asset_code }})? This action cannot be undone.')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="group/btn inline-flex items-center space-x-2 bg-red-600 bg-opacity-80 hover:bg-opacity-100 text-white font-semibold px-5 py-3 rounded-xl transition-all duration-300 shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Asset Images Gallery (Prominent) -->
    @if($asset->images && count($asset->images) > 0)
    <div class="bg-white shadow-sm rounded-lg mb-6 overflow-hidden">
        <!-- Hero Image -->
        <div class="relative h-96 bg-gray-100">
            <img src="{{ asset('storage/' . $asset->images[0]) }}"
                 class="w-full h-full object-cover"
                 alt="Asset hero image">
            @if(count($asset->images) > 1)
            <div class="absolute bottom-4 right-4 bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm">
                {{ count($asset->images) }} photos
            </div>
            @endif
        </div>

        <!-- Thumbnail Grid -->
        @if(count($asset->images) > 1)
        <div class="p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($asset->images as $index => $image)
                <div class="relative group cursor-pointer overflow-hidden rounded-lg border-2 border-transparent hover:border-blue-500 transition-all"
                     onclick="openLightbox({{ $index }})">
                    <img src="{{ asset('storage/' . $image) }}"
                         class="w-full h-24 object-cover"
                         alt="Asset image {{ $index + 1 }}">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="hidden fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center" onclick="closeLightbox()">
        <button class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors" onclick="closeLightbox()">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <button class="absolute left-6 text-white hover:text-gray-300 transition-colors" onclick="event.stopPropagation(); navigateLightbox(-1)">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <div class="max-w-5xl max-h-[90vh] px-16">
            <img id="lightbox-img" src="" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl" alt="Full size image">
            <div class="text-center text-white mt-4 text-sm">
                <span id="lightbox-counter">1</span> / {{ count($asset->images) }}
            </div>
        </div>
        <button class="absolute right-6 text-white hover:text-gray-300 transition-colors" onclick="event.stopPropagation(); navigateLightbox(1)">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Basic Information -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Asset Code</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->asset_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Asset Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($asset->asset_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Brand / Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->brand }} {{ $asset->model }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Serial Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->serial_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Condition</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($asset->condition) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->location ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Purchase Information -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Purchase Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Vendor</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->vendor?->name ?? $asset->vendor_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->purchase_date?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($asset->price ?? 0, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">PO Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->purchase_order_number ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Assignment -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Assignment</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned To User</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->assignedUser?->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned To Department</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->assignedDepartment?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->assigned_at?->format('d M Y H:i') ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Lifecycle Logs -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Lifecycle History</h3>
                <div class="flow-root">
                    <ul class="-mb-8">
                        @forelse($asset->lifecycleLogs as $log)
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $log->to_status ?? 'Created')) }} <span class="font-medium text-gray-900">{{ $log->reason }}</span></p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-sm text-gray-500 text-center py-4">No lifecycle logs</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Related Tickets / Ticket History -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Ticket History
                    @if($asset->tickets->count() > 0)
                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $asset->tickets->count() }} ticket{{ $asset->tickets->count() > 1 ? 's' : '' }}</span>
                    @endif
                </h3>
                
                @if($asset->tickets->count() > 0)
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($asset->tickets as $ticket)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <!-- Status-based icon color -->
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                            @if($ticket->status == 'open') bg-blue-500
                                            @elseif($ticket->status == 'in_progress') bg-yellow-500
                                            @elseif($ticket->status == 'resolved') bg-green-500
                                            @elseif($ticket->status == 'closed') bg-gray-500
                                            @else bg-purple-500
                                            @endif">
                                            @if($ticket->status == 'open')
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                            @elseif($ticket->status == 'in_progress')
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @elseif($ticket->status == 'resolved' || $ticket->status == 'closed')
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @else
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-900">
                                                <a href="{{ route('tickets.show', $ticket) }}" class="font-medium text-blue-600 hover:text-blue-700">
                                                    {{ $ticket->ticket_number }}
                                                </a>
                                                <span class="text-gray-500"> - {{ Str::limit($ticket->subject, 60) }}</span>
                                            </p>
                                            <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500">
                                                <!-- Status badge -->
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($ticket->status == 'open') bg-blue-100 text-blue-800
                                                    @elseif($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800
                                                    @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                                                    @elseif($ticket->status == 'closed') bg-gray-100 text-gray-800
                                                    @else bg-purple-100 text-purple-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                                </span>
                                                <!-- Priority badge -->
                                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($ticket->priority == 'critical') bg-red-100 text-red-800
                                                    @elseif($ticket->priority == 'high') bg-orange-100 text-orange-800
                                                    @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </div>
                                            @if($ticket->assignee)
                                            <p class="mt-1 text-xs text-gray-500">
                                                Assigned to: <span class="font-medium text-gray-700">{{ $ticket->assignee->name }}</span>
                                            </p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $ticket->created_at }}">{{ $ticket->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tickets yet</h3>
                    <p class="mt-1 text-sm text-gray-500">No tickets have been created for this asset.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Warranty Card -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Warranty</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($asset->isUnderWarranty())
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Under Warranty</span>
                            @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->warranty_start?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">End Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->warranty_end?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Provider</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->warranty_provider ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Days Remaining</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->warrantyDaysRemaining() }} days</dd>
                    </div>
                </dl>
            </div>

            <!-- Depreciation Card -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Depreciation</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Method</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ str_replace('_', ' ', ucfirst($asset->depreciation_method ?? 'N/A')) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Purchase Price</dt>
                        <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($asset->price ?? 0, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Value</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-bold">Rp {{ number_format($currentDepreciatedValue ?? 0, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Useful Life</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->useful_life_years ?? '-' }} years</dd>
                    </div>
                </dl>
            </div>

            <!-- Timestamps Card -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $asset->updated_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const images = @json($asset->images ?? []);
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        img.src = '{{ asset("storage") }}/' + images[index];
        if (counter) counter.textContent = index + 1;
        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function navigateLightbox(direction) {
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = images.length - 1;
        if (currentIndex >= images.length) currentIndex = 0;

        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        img.src = '{{ asset("storage") }}/' + images[currentIndex];
        if (counter) counter.textContent = currentIndex + 1;
    }

    // Close lightbox with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });
</script>
@endpush
@endsection
