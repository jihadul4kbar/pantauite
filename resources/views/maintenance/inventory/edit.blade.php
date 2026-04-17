@extends('layouts.app')

@section('title', __('common.edit') . ': ' . $inventory->part_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('maintenance.inventory.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4"><svg class="w-5 h-5 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg><span>{{ __('common.back') }} {{ __('maintenance.inventory.title') }}</span></a>
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-xl px-8 py-6"><h1 class="text-2xl font-bold text-white">{{ __('common.edit') }}: {{ $inventory->part_number }}</h1><p class="text-green-100">{{ $inventory->name }}</p></div>
        </div>

        <form action="{{ route('maintenance.inventory.update', ['inventory' => $inventory->id]) }}" method="POST" class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @csrf @method('PUT')
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="part_number" value="{{ old('part_number', $inventory->part_number) }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 @error('part_number') border-red-500 @enderror">
                        @error('part_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $inventory->name) }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.description') }}</label>
                        <textarea name="description" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">{{ old('description', $inventory->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <input type="text" name="category" value="{{ old('category', $inventory->category) }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer', $inventory->manufacturer) }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <input type="number" name="quantity_in_stock" value="{{ old('quantity_in_stock', $inventory->quantity_in_stock) }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <input type="number" name="reorder_point" value="{{ old('reorder_point', $inventory->reorder_point) }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <input type="number" name="unit_cost" value="{{ old('unit_cost', $inventory->unit_cost) }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <select name="unit" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                            <option value="pcs" {{ old('unit', $inventory->unit) == 'pcs' ? 'selected' : '' }}>PCS</option>
                            <option value="box" {{ old('unit', $inventory->unit) == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="set" {{ old('unit', $inventory->unit) == 'set' ? 'selected' : '' }}>Set</option>
                            <option value="liter" {{ old('unit', $inventory->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="kg" {{ old('unit', $inventory->unit) == 'kg' ? 'selected' : '' }}>KG</option>
                            <option value="meter" {{ old('unit', $inventory->unit) == 'meter' ? 'selected' : '' }}>Meter</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('') }}</label>
                        <select name="vendor_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                            <option value="">{{ __('common.none') }}</option>
                            @foreach($vendors as $vendor) <option value="{{ $vendor->id }}" {{ old('vendor_id', $inventory->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.location') }}</label>
                        <input type="text" name="location" value="{{ old('location', $inventory->location) }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $inventory->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 w-5 h-5">
                            <span class="ml-3 text-sm font-medium">{{ __('common.active') }}</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 bg-gray-50 border-t flex justify-end space-x-3">
                <a href="{{ route('maintenance.inventory.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">{{ __('') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

