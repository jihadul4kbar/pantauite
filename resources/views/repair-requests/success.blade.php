@extends('layouts.auth')

@section('title', 'Permintaan Berhasil Dikirim')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Card -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="p-8">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <!-- Success Message -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-3">
                        Permintaan Berhasil Dikirim!
                    </h1>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Terima kasih telah mengajukan permintaan perbaikan. Tim IT kami akan segera menindaklanjuti.
                    </p>
                </div>

                <!-- Request Details Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 mb-8 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                        Detail Permintaan
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Nomor Permintaan:</span>
                            <span class="font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg text-center sm:text-right">
                                {{ $repairRequest->request_number }}
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Nama Pemohon:</span>
                            <span class="text-gray-900 text-center sm:text-right">{{ $repairRequest->requester_name }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Email:</span>
                            <span class="text-gray-900 text-center sm:text-right break-all max-w-xs">{{ $repairRequest->requester_email }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Subjek:</span>
                            <span class="text-gray-900 text-center sm:text-right max-w-xs truncate" title="{{ $repairRequest->subject }}">{{ $repairRequest->subject }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Prioritas:</span>
                            <span class="text-center sm:text-right">
                                @if($repairRequest->priority === 'critical')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 inline-flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span>
                                        Critical
                                    </span>
                                @elseif($repairRequest->priority === 'high')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800 inline-flex items-center">
                                        <span class="w-2 h-2 bg-orange-500 rounded-full mr-1.5"></span>
                                        High
                                    </span>
                                @elseif($repairRequest->priority === 'medium')
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 inline-flex items-center">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-1.5"></span>
                                        Medium
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 inline-flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                        Low
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Status:</span>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 inline-flex items-center justify-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-1.5 animate-pulse"></span>
                                Menunggu Verifikasi
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                            <span class="text-gray-600 mb-1 sm:mb-0">Tanggal Pengajuan:</span>
                            <span class="text-gray-900 text-center sm:text-right">{{ $repairRequest->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Important Info -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-blue-800">Penting!</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li><strong>Simpan nomor permintaan Anda:</strong> <span class="font-mono bg-blue-100 px-2 py-0.5 rounded">{{ $repairRequest->request_number }}</span> untuk melacak status permintaan</li>
                                    <li>Anda akan menerima notifikasi melalui email ketika permintaan ditinjau</li>
                                    <li>Tim IT akan meninjau permintaan Anda dan menghubungi jika diperlukan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a 
                        href="{{ route('repair-requests.create') }}" 
                        class="flex-1 text-center bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-4 rounded-lg shadow transition duration-150 ease-in-out flex items-center justify-center"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Permintaan Baru
                    </a>
                    <a 
                        href="{{ url('/') }}" 
                        class="flex-1 text-center bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-4 rounded-lg border border-gray-300 shadow transition duration-150 ease-in-out flex items-center justify-center"
                    >
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Additional Info Section -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Permintaan perbaikan Anda sedang diproses oleh tim kami.</p>
            <p class="mt-1">Harap bersabar sementara kami meninjau permintaan Anda.</p>
        </div>
    </div>
</div>
@endsection