@extends('layouts.app')

@section('title', 'Detail Permintaan Perbaikan')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Detail Permintaan Perbaikan
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Informasi lengkap tentang permintaan perbaikan #{{ $repairRequest->request_number }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a 
                        href="{{ route('repair-requests.admin.index') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @if($repairRequest->status === 'draft')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    <svg class="mr-1.5 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Draft
                </span>
            @elseif($repairRequest->status === 'submitted')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <svg class="mr-1.5 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Submitted
                </span>
            @elseif($repairRequest->status === 'approved')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="mr-1.5 h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Approved
                </span>
            @elseif($repairRequest->status === 'rejected')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <svg class="mr-1.5 h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Rejected
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    <svg class="mr-1.5 h-5 w-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Converted to Ticket
                </span>
            @endif
        </div>

        <!-- Request Details -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden mb-8 border border-gray-100">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemohon</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                <p class="text-gray-900">{{ $repairRequest->requester_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-gray-900">{{ $repairRequest->requester_email }}</p>
                            </div>
                            @if($repairRequest->requester_phone)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Telepon</p>
                                    <p class="text-gray-900">{{ $repairRequest->requester_phone }}</p>
                                </div>
                            @endif
                            @if($repairRequest->requester_department)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Departemen</p>
                                    <p class="text-gray-900">{{ $repairRequest->requester_department }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Permintaan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nomor Permintaan</p>
                                <p class="text-gray-900 font-mono">#{{ $repairRequest->request_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                                <p class="text-gray-900">{{ $repairRequest->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Prioritas</p>
                                <div class="mt-1">
                                    @if($repairRequest->priority === 'critical')
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 inline-flex items-center">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                            🔴 Critical - Sangat Mendesak
                                        </span>
                                    @elseif($repairRequest->priority === 'high')
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 inline-flex items-center">
                                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-1.5"></span>
                                            🟠 High - Tinggi
                                        </span>
                                    @elseif($repairRequest->priority === 'medium')
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 inline-flex items-center">
                                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1.5"></span>
                                            🟡 Medium - Sedang
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 inline-flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                            🟢 Low - Rendah
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($repairRequest->category)
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Kategori</p>
                                    <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">
                                        {{ $repairRequest->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 mb-8 pb-8 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Permasalahan</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Subjek Permasalahan</p>
                                <p class="text-gray-900 font-medium">{{ $repairRequest->subject }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Deskripsi Lengkap</p>
                                <div class="mt-1 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $repairRequest->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($repairRequest->asset_name || $repairRequest->asset_serial || $repairRequest->location)
                    <div class="grid grid-cols-1 gap-6 mb-8 pb-8 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Perangkat</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($repairRequest->asset_name)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Nama Perangkat</p>
                                        <p class="text-gray-900">{{ $repairRequest->asset_name }}</p>
                                    </div>
                                @endif
                                
                                @if($repairRequest->asset_serial)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Nomor Seri</p>
                                        <p class="text-gray-900 font-mono">{{ $repairRequest->asset_serial }}</p>
                                    </div>
                                @endif
                                
                                @if($repairRequest->location)
                                    <div class="md:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Lokasi Perangkat</p>
                                        <p class="text-gray-900">{{ $repairRequest->location }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($repairRequest->photos && count($repairRequest->photos) > 0)
                    <div class="grid grid-cols-1 gap-6 mb-8 pb-8 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Lampiran</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($repairRequest->photos as $index => $photo)
                                    <div class="relative group">
                                        <div 
                                            class="cursor-pointer photo-thumbnail"
                                            data-index="{{ $index }}"
                                            data-url="{{ $photo->url }}"
                                        >
                                            <img 
                                                src="{{ $photo->url }}" 
                                                alt="Foto Perangkat" 
                                                class="w-full h-32 object-cover rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow"
                                            >
                                        </div>
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg pointer-events-none">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($repairRequest->status === 'submitted')
                        <form action="{{ route('repair-requests.admin.approve', $repairRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('POST')
                            <button 
                                type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui
                            </button>
                        </form>
                        
                        <form action="{{ route('repair-requests.admin.reject', $repairRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('POST')
                            <button 
                                type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak
                            </button>
                        </form>
                        
                        <form action="{{ route('repair-requests.admin.convert', $repairRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('POST')
                            <button 
                                type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Konversi ke Tiket
                            </button>
                        </form>
                    @elseif($repairRequest->status === 'approved')
                        <form action="{{ route('repair-requests.admin.convert', $repairRequest->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('POST')
                            <button 
                                type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Konversi ke Tiket
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 flex items-center justify-center p-4">
        <div class="relative max-w-6xl w-full">
            <div class="flex justify-between items-center mb-4">
                <button 
                    type="button"
                    onclick="closeImageModal()"
                    class="text-white hover:text-gray-300 focus:outline-none"
                >
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="text-white text-lg" id="imageCounter">1 / {{ count($repairRequest->photos) }}</div>
                <div class="w-8"></div> <!-- Spacer to balance the controls -->
            </div>
            
            <div class="flex items-center justify-center relative">
                <button 
                    type="button"
                    id="prevButton"
                    class="absolute left-0 md:-left-12 text-white hover:text-gray-300 focus:outline-none z-10 p-2"
                >
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <div class="relative flex items-center justify-center w-full">
                    <img 
                        id="modalImage" 
                        src="" 
                        alt="Full size image" 
                        class="max-h-[70vh] max-w-full object-contain mx-auto"
                    >
                </div>
                
                <button 
                    type="button"
                    id="nextButton"
                    class="absolute right-0 md:-right-12 text-white hover:text-gray-300 focus:outline-none z-10 p-2"
                >
                    <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Collect all image URLs from the thumbnails
    const thumbnails = document.querySelectorAll('.photo-thumbnail');
    const imageList = Array.from(thumbnails).map(thumb => thumb.dataset.url);
    
    let currentIndex = 0;
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const imageCounter = document.getElementById('imageCounter');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');

    // Function to update modal content
    function updateModalContent(index) {
        if (index >= 0 && index < imageList.length) {
            currentIndex = index;
            modalImage.src = imageList[currentIndex];
            imageCounter.textContent = `${currentIndex + 1} / ${imageList.length}`;
        }
    }

    // Add click event to each thumbnail
    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => {
            openImageModal(index);
        });
    });

    // Open Modal
    window.openImageModal = function(index) {
        if (!modal || !imageList.length) return;
        
        updateModalContent(index);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    };

    // Close Modal
    window.closeImageModal = function() {
        if (!modal) return;
        
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
        modalImage.src = ''; // Clear src to stop loading
    };

    // Navigation
    function nextImage() {
        let newIndex = currentIndex + 1;
        if (newIndex >= imageList.length) newIndex = 0; // Loop back to start
        updateModalContent(newIndex);
    }

    function prevImage() {
        let newIndex = currentIndex - 1;
        if (newIndex < 0) newIndex = imageList.length - 1; // Loop to end
        updateModalContent(newIndex);
    }

    // Event Listeners for buttons
    if (nextButton) {
        nextButton.addEventListener('click', (e) => {
            e.stopPropagation();
            nextImage();
        });
    }
    
    if (prevButton) {
        prevButton.addEventListener('click', (e) => {
            e.stopPropagation();
            prevImage();
        });
    }

    // Close on background click
    if (modal) {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeImageModal();
            }
        });
    }

    // Keyboard navigation
    window.addEventListener('keydown', (event) => {
        if (modal && !modal.classList.contains('hidden')) {
            if (event.key === 'ArrowLeft') {
                prevImage();
            } else if (event.key === 'ArrowRight') {
                nextImage();
            } else if (event.key === 'Escape') {
                closeImageModal();
            }
        }
    });
});
</script>
@endsection