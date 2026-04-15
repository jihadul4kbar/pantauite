@extends('layouts.app')

@section('title', 'Detail Permintaan Perbaikan')

@section('content')
<div class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back Button -->
        <div class="mb-4">
            <a href="{{ route('repair-requests.admin.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                ← Kembali ke Daftar Permintaan
            </a>
        </div>

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Detail Permintaan Perbaikan
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Nomor: <span class="font-semibold text-blue-600">{{ $repairRequest->request_number }}</span>
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Request Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Pemohon -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                        Informasi Pemohon
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Lengkap</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->requester_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->requester_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Telepon</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->requester_phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Departemen</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->requester_department ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Permasalahan -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                        Detail Permasalahan
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Subjek</label>
                            <p class="mt-1 text-sm text-gray-900 font-medium">{{ $repairRequest->subject }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                            <div class="mt-1 text-sm text-gray-900 bg-gray-50 p-4 rounded">
                                {{ $repairRequest->description }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Prioritas</label>
                                <div class="mt-1">
                                    @if($repairRequest->priority === 'critical')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                            Critical
                                        </span>
                                    @elseif($repairRequest->priority === 'high')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">
                                            High
                                        </span>
                                    @elseif($repairRequest->priority === 'medium')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Medium
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                            Low
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Kategori</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $repairRequest->category->name ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Perangkat -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                        Informasi Perangkat
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Perangkat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->asset_name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nomor Seri</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->asset_serial ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Lokasi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->location ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Status & Verifikasi -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">
                        Status & Verifikasi
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                @if($repairRequest->status === 'draft')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Draft
                                    </span>
                                @elseif($repairRequest->status === 'submitted')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($repairRequest->status === 'approved')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        Disetujui
                                    </span>
                                @elseif($repairRequest->status === 'rejected')
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                        Dikonversi ke Tiket
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($repairRequest->verifier)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Diverifikasi oleh</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $repairRequest->verifier->name }}</p>
                            </div>
                        @endif

                        @if($repairRequest->verified_at)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal Verifikasi</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $repairRequest->verified_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        @endif

                        @if($repairRequest->rejection_reason)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Alasan Penolakan</label>
                                <div class="mt-1 text-sm text-gray-900 bg-red-50 p-4 rounded border border-red-200">
                                    {{ $repairRequest->rejection_reason }}
                                </div>
                            </div>
                        @endif

                        @if($repairRequest->ticket)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tiket Terkait</label>
                                <p class="mt-1">
                                    <a href="{{ route('tickets.show', $repairRequest->ticket->id) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                        {{ $repairRequest->ticket->ticket_number }} - {{ $repairRequest->ticket->subject }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6 sticky top-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Aksi
                    </h2>

                    <!-- Status Info Card -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-xs text-blue-700">
                            <strong class="font-semibold">Status Saat Ini:</strong>
                            @if($repairRequest->status === 'submitted')
                                Menunggu verifikasi IT Manager
                            @elseif($repairRequest->status === 'approved')
                                Disetujui - Siap dikonversi ke tiket
                            @elseif($repairRequest->status === 'rejected')
                                Permintaan ditolak
                            @elseif($repairRequest->status === 'converted')
                                Sudah dikonversi menjadi tiket
                            @else
                                Draft
                            @endif
                        </p>
                    </div>

                    @if($repairRequest->isSubmitted())
                        <!-- Approve Button -->
                        <form action="{{ route('repair-requests.admin.approve', $repairRequest->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out"
                            >
                                ✓ Setujui
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <button 
                            onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out"
                        >
                            ✕ Tolak
                        </button>
                    @elseif($repairRequest->isApproved())
                        <!-- Convert to Ticket Button -->
                        <form action="{{ route('repair-requests.admin.convert', $repairRequest->id) }}" method="POST" class="mb-3">
                            @csrf
                            <button 
                                type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out"
                            >
                                🎫 Konversi ke Tiket
                            </button>
                        </form>
                    @elseif($repairRequest->isRejected())
                        <div class="p-3 bg-gray-50 border border-gray-200 rounded text-center">
                            <p class="text-sm text-gray-600">Permintaan ini telah ditolak</p>
                        </div>
                    @elseif($repairRequest->isConverted())
                        <div class="p-3 bg-green-50 border border-green-200 rounded text-center">
                            <p class="text-sm text-green-700">Sudah dikonversi menjadi tiket</p>
                            @if($repairRequest->ticket)
                                <a href="{{ route('tickets.show', $repairRequest->ticket->id) }}" class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-900 font-medium">
                                    Lihat Tiket →
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mt-3">Tolak Permintaan</h3>
            <p class="text-sm text-gray-500 text-center mt-1">Berikan alasan penolakan</p>
            
            <form action="{{ route('repair-requests.admin.reject', $repairRequest->id) }}" method="POST" class="mt-4">
                @csrf
                <textarea 
                    name="rejection_reason" 
                    rows="4"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Alasan penolakan..."
                ></textarea>
                @error('rejection_reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <div class="flex gap-3 mt-4">
                    <button 
                        type="button" 
                        onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                    >
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
