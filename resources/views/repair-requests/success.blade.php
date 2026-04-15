@extends('layouts.auth')

@section('title', 'Permintaan Berhasil Dikirim')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Card -->
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-8">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Success Message -->
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">
                        Permintaan Berhasil Dikirim!
                    </h1>
                    <p class="text-gray-600">
                        Terima kasih telah mengajukan permintaan perbaikan. Tim IT kami akan segera menindaklanjuti.
                    </p>
                </div>

                <!-- Request Details -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Permintaan</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nomor Permintaan:</span>
                            <span class="font-semibold text-blue-600">{{ $repairRequest->request_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama Pemohon:</span>
                            <span class="text-gray-900">{{ $repairRequest->requester_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="text-gray-900">{{ $repairRequest->requester_email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subjek:</span>
                            <span class="text-gray-900">{{ $repairRequest->subject }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Prioritas:</span>
                            <span class="text-gray-900">
                                @if($repairRequest->priority === 'critical')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Critical
                                    </span>
                                @elseif($repairRequest->priority === 'high')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        High
                                    </span>
                                @elseif($repairRequest->priority === 'medium')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Medium
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Low
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Menunggu Verifikasi
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pengajuan:</span>
                            <span class="text-gray-900">{{ $repairRequest->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Important Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Penting!</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li><strong>Simpan nomor permintaan Anda:</strong> <code class="bg-blue-100 px-2 py-0.5 rounded">{{ $repairRequest->request_number }}</code></li>
                                    <li>Anda akan menerima notifikasi melalui email ketika permintaan ditinjau</li>
                                    <li>Hubungi tim IT jika Anda tidak menerima respon dalam 2x24 jam</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a 
                        href="{{ route('repair-requests.create') }}" 
                        class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out"
                    >
                        Buat Permintaan Baru
                    </a>
                    <a 
                        href="{{ url('/') }}" 
                        class="flex-1 text-center bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-lg border border-gray-300 shadow transition duration-150 ease-in-out"
                    >
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
