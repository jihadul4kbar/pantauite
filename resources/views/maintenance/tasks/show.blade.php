@php
    // Helper function for translations with fallbacks
    function trans_fallback($key, $fallback) {
        $translated = __($key);
        return $translated === $key ? $fallback : $translated;
    }
@endphp

@extends('layouts.app')

@section('title', trans_fallback('maintenance.tasks.detail_title', 'Detail Pemeliharaan'))

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('maintenance.tasks.index') }}" class="text-sm text-green-600 hover:text-green-700 mb-2 inline-block">{{ trans_fallback('maintenance.tasks.back_to_list', '← Kembali ke Daftar Tugas') }}</a>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $task->task_number }}</h1>
                    <p class="text-gray-600">{{ $task->title }}</p>
                </div>
                <div class="flex space-x-3">
                    @if(in_array($task->status, ['pending', 'scheduled']))
                    <a href="{{ route('maintenance.tasks.execute', $task) }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all">{{ trans_fallback('maintenance.tasks.execute', 'Jalankan') }}</a>
                    @endif
                    @if($task->status === 'in_progress')
                    <a href="{{ route('maintenance.tasks.execute', $task) }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-sm hover:shadow-md transition-all">{{ trans_fallback('maintenance.tasks.continue_task', 'Lanjutkan Tugas') }}</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Task Details -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">{{ trans_fallback('maintenance.tasks.task_details', 'Detail Tugas') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-gray-700">{{ nl2br(e($task->description)) }}</div>
                        @if($task->resolution_notes)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-bold text-green-700 mb-2">{{ trans_fallback('maintenance.tasks.resolution_notes', 'Catatan Penyelesaian') }}</h3>
                            <div class="bg-green-50 p-4 rounded-xl text-sm text-gray-700">{{ nl2br(e($task->resolution_notes)) }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Checklist Results -->
                @if($task->checklistResults && $task->checklistResults->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">{{ trans_fallback('maintenance.tasks.checklist_results', 'Hasil Daftar Periksa') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($task->checklistResults as $result)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $result->checklistItem->item ?? trans_fallback('maintenance.tasks.deleted_item', 'Item yang Dihapus') }}</p>
                                    @if($result->notes) <p class="text-xs text-gray-500 mt-1">{{ $result->notes }}</p> @endif
                                </div>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $result->status === 'pass' ? 'bg-green-100 text-green-800' : ($result->status === 'fail' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($result->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Parts & Materials -->
                @if($task->partsUsed && $task->partsUsed->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">{{ trans_fallback('maintenance.tasks.parts_materials', 'Suku Cadang & Material yang Digunakan') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">{{ trans_fallback('maintenance.inventory.part', 'Suku Cadang') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">{{ trans_fallback('maintenance.inventory.quantity', 'Jumlah') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">{{ trans_fallback('maintenance.inventory.unit_cost', 'Harga Satuan') }}</th>
                                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">{{ trans_fallback('maintenance.tasks.total', 'Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($task->partsUsed as $part)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $part->inventoryPart->part_name ?? trans_fallback('maintenance.tasks.deleted_part', 'Suku Cadang yang Dihapus') }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $part->quantity_used }}</td>
                                        <td class="px-4 py-3 text-sm">Rp {{ number_format($part->unit_cost, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-sm font-medium">Rp {{ number_format($part->quantity_used * $part->unit_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if($task->partsUsed->sum(fn($p) => $p->quantity_used * $p->unit_cost) > 0)
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="3" class="px-4 py-3 text-right font-bold">{{ trans_fallback('maintenance.tasks.total_cost', 'Total Biaya:') }}</td>
                                        <td class="px-4 py-3 text-sm font-bold text-green-600">Rp {{ number_format($task->partsUsed->sum(fn($p) => $p->quantity_used * $p->unit_cost), 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Photos -->
                @if($task->photos && $task->photos->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">{{ trans_fallback('maintenance.tasks.documentation_photos', 'Foto Dokumentasi') }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($task->photos as $photo)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->photo_type }}" class="w-full h-40 object-cover rounded-xl">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded-b-xl">
                                    {{ ucfirst(str_replace('_', ' ', $photo->photo_type)) }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-sm font-semibold opacity-80">{{ trans_fallback('maintenance.tasks.status_priority', 'Status & Prioritas') }}</h3>
                    <div class="mt-4 space-y-3">
                        <div>
                            <label class="text-xs opacity-70">{{ trans_fallback('maintenance.tasks.status', 'Status') }}</label>
                            <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</p>
                        </div>
                        <div>
                            <label class="text-xs opacity-70">{{ trans_fallback('maintenance.tasks.priority', 'Prioritas') }}</label>
                            <p class="font-semibold">{{ ucfirst($task->priority) }}</p>
                        </div>
                        @if($task->schedule)
                        <div>
                            <label class="text-xs opacity-70">{{ trans_fallback('maintenance.tasks.schedule', 'Jadwal') }}</label>
                            <p class="text-sm">{{ $task->schedule->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Assignment Card -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">{{ trans_fallback('maintenance.tasks.assignment', 'Penugasan') }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($task->assignedToUser)
                        <div>
                            <label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.assigned_to', 'Ditugaskan Kepada') }}</label>
                            <p class="mt-1 font-medium">{{ $task->assignedToUser->name }}</p>
                        </div>
                        @endif
                        @if($task->assignedToVendor)
                        <div>
                            <label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.vendor', 'Vendor') }}</label>
                            <p class="mt-1 font-medium">{{ $task->assignedToVendor->name }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.approval_status', 'Status Persetujuan') }}</label>
                            <p class="mt-1">
                                <span class="px-2 py-1 text-xs font-bold rounded-full {{ $task->approval_status === 'approved' ? 'bg-green-100 text-green-800' : ($task->approval_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($task->approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->approval_status)) }}
                                </span>
                            </p>
                        </div>
                        @if($task->estimated_cost) <div><label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.est_cost', 'Est. Biaya') }}</label><p class="text-sm font-bold text-green-600 mt-1">Rp {{ number_format($task->estimated_cost, 0, ',', '.') }}</p></div> @endif
                        @if($task->actual_cost) <div><label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.actual_cost', 'Biaya Aktual') }}</label><p class="text-sm font-bold text-blue-600 mt-1">Rp {{ number_format($task->actual_cost, 0, ',', '.') }}</p></div> @endif
                    </div>
                </div>

                <!-- Evaluation -->
                @if($task->evaluation)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">{{ trans_fallback('maintenance.tasks.evaluations', 'Evaluasi') }}</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($task->evaluation->rating)
                        <div>
                            <label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.rating', 'Penilaian') }}</label>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $task->evaluation->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                        </div>
                        @endif
                        @if($task->evaluation->issues_found)
                        <div>
                            <label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.issues_found', 'Masalah Ditemukan') }}</label>
                            <p class="mt-1 text-sm text-gray-700">{{ $task->evaluation->issues_found }}</p>
                        </div>
                        @endif
                        @if($task->evaluation->recommendations)
                        <div>
                            <label class="text-sm font-semibold text-gray-500">{{ trans_fallback('maintenance.tasks.recommendations', 'Rekomendasi') }}</label>
                            <p class="mt-1 text-sm text-gray-700">{{ $task->evaluation->recommendations }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
