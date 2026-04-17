@extends('layouts.app')

@section('title', __('maintenance.inventory.stock_in') . ': ' . $inventory->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('maintenance.inventory.show', ['inventory' => $inventory->id]) }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4"><svg class="w-5 h-5 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg><span>{{ __('common.back') }}</span></a>
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-xl px-8 py-6">
                <h1 class="text-2xl font-bold text-white">{{ __('maintenance.inventory.stock_in') }}: {{ $inventory->part_number }}</h1>
                <p class="text-blue-100">{{ $inventory->name }}</p>
                <div class="mt-2 text-sm text-blue-100">{{ __('maintenance.inventory.current_stock') }}: <span class="font-bold text-white">{{ $inventory->quantity_in_stock }} {{ $inventory->unit }}</span></div>
            </div>
        </div>

        <form action="{{ route('maintenance.inventory.process-stock-in', ['inventory' => $inventory->id]) }}" method="POST" class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @csrf
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.inventory.quantity') }} <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" min="0.01" step="0.01" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.inventory.unit_cost') }}</label>
                        <input type="number" name="unit_cost" value="{{ old('unit_cost', $inventory->unit_cost) }}" min="0" step="0.01" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('maintenance.inventory.supplier') }}</label>
                        <input type="text" name="supplier" value="{{ old('supplier') }}" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('common.notes') }}</label>
                        <textarea name="notes" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3" placeholder="{{ __('maintenance.inventory.notes_placeholder') }}">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 bg-gray-50 border-t flex justify-end space-x-3">
                <a href="{{ route('maintenance.inventory.show', ['inventory' => $inventory->id]) }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200">{{ __('common.cancel') }}</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-blue-700">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('maintenance.inventory.add_stock') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
