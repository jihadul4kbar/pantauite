@extends('layouts.app')

@section('title', 'Tambah Suku Cadang Baru')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('maintenance.inventory.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4"><svg class="w-5 h-5 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg><span>Kembali ke Daftar Suku Cadang</span></a>
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-xl px-8 py-6"><h1 class="text-2xl font-bold text-white">Tambah Suku Cadang Baru</h1></div>
        </div>

        <form action="{{ route('maintenance.inventory.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @csrf
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Suku Cadang <span class="text-red-500">*</span></label>
                        <input type="text" name="part_number" value="{{ old('part_number') }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.description') }}</label>
                        <textarea name="description" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                        <input type="text" name="category" value="{{ old('category') }}" placeholder="mis., Kelistrikan, Mekanik" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Produsen</label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Awal</label>
                        <input type="number" name="quantity_in_stock" value="{{ old('quantity_in_stock', 0) }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Titik Pesan Ulang</label>
                        <input type="number" name="reorder_point" value="{{ old('reorder_point', 5) }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                        <input type="number" name="unit_cost" value="{{ old('unit_cost') }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan</label>
                        <select name="unit" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                            <option value="pcs">PCS</option><option value="box">Box</option><option value="set">Set</option><option value="liter">Liter</option><option value="kg">KG</option><option value="meter">Meter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vendor/Pemasok</label>
                        <select name="vendor_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                            <option value="">{{ __('common.none') }}</option>
                            @foreach($vendors as $vendor) <option value="{{ $vendor->id }}">{{ $vendor->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.location') }}</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="mis., Rak A3, Laci 2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 bg-gray-50 border-t flex justify-end space-x-3">
                <a href="{{ route('maintenance.inventory.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">Tambah Suku Cadang</button>
            </div>
        </form>
    </div>
</div>
@endsection
