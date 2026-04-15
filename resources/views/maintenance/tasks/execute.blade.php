@extends('layouts.app')

@section('title', __('maintenance.tasks.execute') . ': ' . $task->task_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('maintenance.tasks.show', $task) }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>{{ __('common.back') }} {{ __('maintenance.tasks.title') }}</span>
            </a>
            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20"><div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div></div>
                <div class="relative px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $task->task_number }} - {{ __('maintenance.tasks.execute_maintenance', 'Jalankan Pemeliharaan') }}</h1>
                            <p class="text-green-100">{{ $task->title }}</p>
                        </div>
                        <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl text-gray-800 font-semibold">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('maintenance.tasks.save-execution', $task) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Checklist -->
                    @if($checklistItems->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                {{ __('maintenance.tasks.checklist', 'Daftar Periksa Pemeliharaan') }}
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @foreach($checklistItems as $index => $item)
                            <div class="p-4 border border-gray-200 rounded-xl {{ $item->is_required ? 'bg-red-50 border-red-200' : 'bg-white' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $index + 1 }}. {{ $item->item_name }}</h3>
                                        @if($item->description) <p class="text-sm text-gray-600 mt-1">{{ $item->description }}</p> @endif
                                        @if($item->requires_photo) <span class="inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded mt-1">{{ __('maintenance.tasks.photo_required', 'Foto Diperlukan') }}</span> @endif
                                    </div>
                                    @if($item->is_required) <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">{{ __('maintenance.tasks.required', 'Wajib') }}</span> @endif
                                </div>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="checklist[{{ $item->item_name }}][status]" value="pass" class="text-green-600 focus:ring-green-500" required>
                                        <span class="text-sm font-medium text-green-700">{{ __('maintenance.tasks.pass', 'Lolos') }}</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="checklist[{{ $item->item_name }}][status]" value="fail" class="text-red-600 focus:ring-red-500">
                                        <span class="text-sm font-medium text-red-700">{{ __('maintenance.tasks.fail', 'Gagal') }}</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="checklist[{{ $item->item_name }}][status]" value="na" class="text-gray-600 focus:ring-gray-500">
                                        <span class="text-sm font-medium text-gray-700">{{ __('common.na') }}</span>
                                    </label>
                                </div>
                                <input type="hidden" name="checklist[{{ $item->item_name }}][description]" value="{{ $item->description }}">
                                <input type="text" name="checklist[{{ $item->item_name }}][notes]" placeholder="{{ __('maintenance.tasks.notes_optional', 'Catatan (opsional)') }}" class="mt-3 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Parts & Materials -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                {{ __('maintenance.tasks.parts_materials', 'Suku Cadang & Material yang Digunakan') }}
                            </h2>
                        </div>
                        <div class="p-6" id="partsContainer">
                            <div class="space-y-4 parts-list">
                                <div class="grid grid-cols-12 gap-3 items-end border-b border-gray-200 pb-2">
                                    <div class="col-span-4"><label class="text-xs font-semibold text-gray-500">{{ __('maintenance.inventory.part', 'Nama Suku Cadang') }}</label><input type="text" name="requirements[0][part_name]" placeholder="{{ __('maintenance.tasks.part_name', 'Nama suku cadang') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                                    <div class="col-span-2"><label class="text-xs font-semibold text-gray-500">{{ __('maintenance.inventory.quantity', 'Jml') }}</label><input type="number" name="requirements[0][quantity]" value="1" min="0" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm qty-input"></div>
                                    <div class="col-span-2"><label class="text-xs font-semibold text-gray-500">{{ __('maintenance.inventory.unit_cost', 'Harga Satuan') }}</label><input type="number" name="requirements[0][unit_cost]" placeholder="0" min="0" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm cost-input"></div>
                                    <div class="col-span-2"><label class="text-xs font-semibold text-gray-500">{{ __('maintenance.tasks.total', 'Total') }}</label><input type="text" readonly class="w-full border border-gray-100 rounded-lg px-3 py-2 text-sm bg-gray-50 total-input" value="0"></div>
                                    <div class="col-span-2"><button type="button" onclick="addPartRow()" class="w-full px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700">+ {{ __('common.add', 'Tambah') }}</button></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resolution Notes -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <h2 class="text-lg font-bold text-gray-900">{{ __('maintenance.tasks.resolution_notes', 'Catatan Penyelesaian') }}</h2>
                        </div>
                        <div class="p-6">
                            <textarea name="resolution_notes" rows="4" placeholder="{{ __('maintenance.tasks.resolution_placeholder', 'Jelaskan pekerjaan yang dilakukan, masalah yang ditemukan, dan penyelesaiannya...') }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                        </div>
                    </div>

                    <!-- Complete Button -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('maintenance.tasks.show', $task) }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200">{{ __('common.cancel') }}</a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-700 hover:to-emerald-700 shadow-lg">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('maintenance.tasks.complete_task', 'Selesaikan Tugas') }}
                        </button>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Task Info -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 space-y-4">
                            <div><label class="text-sm font-semibold text-gray-500">{{ __('maintenance.tasks.asset') }}</label><p class="text-sm text-gray-900 mt-1">{{ $task->asset->asset_code }} - {{ $task->asset->name }}</p></div>
                            <div><label class="text-sm font-semibold text-gray-500">{{ __('maintenance.tasks.scheduled_date') }}</label><p class="text-sm text-gray-900 mt-1">{{ $task->scheduled_date->format('d M Y') }}</p></div>
                            <div><label class="text-sm font-semibold text-gray-500">{{ __('maintenance.tasks.assigned_to') }}</label><p class="text-sm text-gray-900 mt-1">{{ $task->assignedUser?->name ?? '-' }}</p></div>
                            @if($task->estimated_cost) <div><label class="text-sm font-semibold text-gray-500">{{ __('maintenance.tasks.est_cost', 'Est. Biaya') }}</label><p class="text-sm font-bold text-green-600 mt-1">Rp {{ number_format($task->estimated_cost, 0, ',', '.') }}</p></div> @endif
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                            <h2 class="text-lg font-bold text-gray-900">{{ __('maintenance.tasks.photo_documentation', 'Dokumentasi Foto') }}</h2>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('maintenance.tasks.upload-photo', $task) }}" method="POST" enctype="multipart/form-data" id="photoForm">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.tasks.photo_type', 'Tipe Foto') }}</label>
                                        <select name="photo_type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                            <option value="before">{{ __('maintenance.tasks.photo_before', 'Sebelum') }}</option>
                                            <option value="during">{{ __('maintenance.tasks.photo_during', 'Saat Proses') }}</option>
                                            <option value="after">{{ __('maintenance.tasks.photo_after', 'Setelah') }}</option>
                                            <option value="evidence">{{ __('maintenance.tasks.photo_evidence', 'Bukti') }}</option>
                                        </select>
                                    </div>
                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition-colors cursor-pointer" onclick="document.getElementById('photoInput').click()">
                                        <svg class="w-10 h-10 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="text-sm text-gray-600">{{ __('maintenance.tasks.click_upload', 'Klik untuk unggah foto') }}</p>
                                        <p class="text-xs text-gray-400">{{ __('maintenance.tasks.max_file', 'Maks 5MB per file') }}</p>
                                    </div>
                                    <input type="file" id="photoInput" name="photos[]" multiple accept="image/*" class="hidden">
                                    <div id="photoPreview" class="grid grid-cols-2 gap-2"></div>
                                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700">{{ __('common.upload') }} {{ __('maintenance.tasks.photos', 'Foto') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let partIndex = 1;
function addPartRow() {
    const container = document.getElementById('partsContainer').querySelector('.parts-list');
    const newRow = `
        <div class="grid grid-cols-12 gap-3 items-end border-b border-gray-200 pb-2 pt-2">
            <div class="col-span-4"><input type="text" name="requirements[${partIndex}][part_name]" placeholder="{{ __('maintenance.tasks.part_name', 'Nama suku cadang') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
            <div class="col-span-2"><input type="number" name="requirements[${partIndex}][quantity]" value="1" min="0" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm qty-input"></div>
            <div class="col-span-2"><input type="number" name="requirements[${partIndex}][unit_cost]" placeholder="0" min="0" step="0.01" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm cost-input"></div>
            <div class="col-span-2"><input type="text" readonly class="w-full border border-gray-100 rounded-lg px-3 py-2 text-sm bg-gray-50 total-input" value="0"></div>
            <div class="col-span-2"><button type="button" onclick="this.closest('.grid').remove()" class="w-full px-3 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600">✕ Remove</button></div>
        </div>`;
    container.insertAdjacentHTML('beforeend', newRow);
    partIndex++;
    bindCalcEvents();
}
function calcTotal(row) {
    const qty = parseFloat(row.querySelector('.qty-input')?.value || 0);
    const cost = parseFloat(row.querySelector('.cost-input')?.value || 0);
    row.querySelector('.total-input').value = (qty * cost).toFixed(2);
}
function bindCalcEvents() {
    document.querySelectorAll('.parts-list .grid').forEach(row => {
        row.querySelector('.qty-input')?.addEventListener('input', () => calcTotal(row));
        row.querySelector('.cost-input')?.addEventListener('input', () => calcTotal(row));
    });
}
bindCalcEvents();

// Photo preview
document.getElementById('photoInput').addEventListener('change', function(e) {
    const preview = document.getElementById('photoPreview');
    preview.innerHTML = '';
    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML += `<div class="relative"><img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg"><div class="absolute inset-0 bg-black bg-opacity-20 rounded-lg"></div></div>`;
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
