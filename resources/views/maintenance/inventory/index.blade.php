@extends('layouts.app')

@section('title', __('maintenance.inventory.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="relative mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
            <div class="absolute inset-0 opacity-20"><div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div></div>
            <div class="relative px-8 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('maintenance.inventory.title') }}</h1>
                            <p class="mt-1 text-green-100">{{ __('maintenance.inventory.subtitle') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('maintenance.inventory.create') }}" class="group flex items-center space-x-2 bg-white bg-opacity-20 backdrop-blur-lg hover:bg-opacity-30 text-gray-800 font-semibold px-5 py-3 rounded-xl transition-all shadow-lg">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>{{ __('maintenance.inventory.add_part') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @if($parts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Suku Cadang</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Harga Satuan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">{{ __('common.status') }}</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($parts as $part)
                        <tr class="hover:bg-green-50 transition-all">
                            <td class="px-6 py-4">
                                <a href="{{ route('maintenance.inventory.show', ['inventory' => $part->id]) }}" class="text-sm font-bold text-green-600 hover:text-green-800">{{ $part->part_number }}</a>
                                <div class="text-xs text-gray-500">{{ Str::limit($part->name, 30) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $part->category ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold {{ $part->needsReorder() ? 'text-red-600' : 'text-green-600' }}">{{ $part->quantity_in_stock }} {{ $part->unit }}</span>
                                @if($part->reorder_point > 0) <div class="text-xs text-gray-500">{{ __('maintenance.inventory.reorder') }} {{ $part->reorder_point }}</div> @endif
                            </td>
                            <td class="px-6 py-4 text-sm">Rp {{ number_format($part->unit_cost ?? 0, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($part->quantity_in_stock <= 0)
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-bold rounded">Stok Habis</span>
                                @elseif($part->needsReorder())
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded">Stok Rendah</span>
                                @else
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded">Tersedia</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('maintenance.inventory.stock-in', ['inventory' => $part->id]) }}" class="px-3 py-1.5 bg-blue-500 text-white text-xs font-semibold rounded-lg hover:bg-blue-600">{{ __('maintenance.inventory.stock_in') }}</a>
                                    <a href="{{ route('maintenance.inventory.edit', ['inventory' => $part->id]) }}" class="px-3 py-1.5 bg-green-500 text-white text-xs font-semibold rounded-lg hover:bg-green-600">{{ __('maintenance.inventory.edit') }}</a>
                                    @if(Auth::user()->hasRole('super_admin'))
                                    <form action="{{ route('maintenance.inventory.destroy', ['inventory' => $part->id]) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('maintenance.inventory.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 text-white text-xs font-semibold rounded-lg hover:bg-red-600 transition-all shadow-sm">{{ __('maintenance.inventory.delete') }}</button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t bg-gray-50">{{ $parts->links() }}</div>
            @else
            <div class="p-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <h3 class="text-lg font-bold text-gray-900">{{ __('maintenance.inventory.no_parts') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('maintenance.inventory.get_started') }}</p>
                <a href="{{ route('maintenance.inventory.create') }}" class="inline-flex items-center space-x-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">+ {{ __('maintenance.inventory.add_part') }}</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
