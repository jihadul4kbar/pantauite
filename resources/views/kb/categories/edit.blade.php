@extends('layouts.app')

@section('title', __('kb.edit_category'))

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('kb.categories.index') }}" class="text-sm text-blue-600 hover:text-blue-500 mb-2 inline-block">
            ← Kembali ke Daftar Kategori
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('kb.edit_category') }}</h1>
    </div>

    <!-- Form -->
    <form action="{{ route('kb.categories.update', $category) }}" method="POST" class="bg-white shadow-sm rounded-lg overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                    {{ __('kb.category_name') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('kb.category_description') }}</label>
                <textarea name="description" id="description" rows="3"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
            </div>

            <!-- Parent Category -->
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700">{{ __('kb.parent_category') }}</label>
                <select name="parent_id" id="parent_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('kb.no_parent') }}</option>
                    @foreach($parentCategories as $parent)
                    @if($parent->id !== $category->id)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                        {{ $parent->icon }} {{ $parent->name }}
                    </option>
                    @endif
                    @endforeach
                </select>
            </div>

            <!-- Icon -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700">{{ __('kb.icon_label') }}</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                       placeholder="📁">
                <p class="mt-1 text-xs text-gray-500">{{ __('kb.icon_hint') }}</p>
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('kb.sort_order') }}</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-xs text-gray-500">{{ __('kb.sort_order_hint') }}</p>
            </div>

            <!-- Active Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">{{ __('kb.active_label') }}</span>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('kb.categories.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                {{ __('common.cancel') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                {{ __('kb.update_category_btn') }}
            </button>
        </div>
    </form>
</div>
@endsection
