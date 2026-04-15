@extends('layouts.app')

@section('title', __('kb.create_title'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb & Header -->
        <div class="mb-6">
            <a href="{{ route('kb.index') }}" class="group inline-flex items-center space-x-2 text-sm text-gray-600 hover:text-green-600 mb-4 transition-colors">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">{{ __('kb.back_to_kb') }}</span>
            </a>

            <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl transform translate-x-32 -translate-y-32"></div>
                </div>

                <div class="relative px-8 py-8">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-white bg-opacity-20 backdrop-blur-lg rounded-xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ __('kb.create_title') }}</h1>
                            <p class="mt-1 text-green-100">{{ __('kb.create_subtitle') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('kb.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <!-- Article Content -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('kb.article_content') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ __('kb.title_label') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('title') border-red-500 @enderror"
                                       placeholder="{{ __('kb.title_placeholder') }}">
                                @error('title') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('kb.summary') }}</label>
                                <textarea name="summary" id="summary" rows="2"
                                          class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3"
                                          placeholder="{{ __('kb.summary_placeholder') }}">{{ old('summary') }}</textarea>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('kb.summary_hint') }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ __('kb.content') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea name="content" id="content" rows="15"
                                          class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('content') border-red-500 @enderror"
                                          placeholder="{{ __('kb.content_placeholder') }}">{{ old('content') }}</textarea>
                                @error('content') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category & Status -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('kb.category_status') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ __('kb.category') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select name="category_id" id="category_id" required
                                        class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('category_id') border-red-500 @enderror">
                                    <option value="">{{ __('kb.select_category') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->icon }} {{ $category->name }}
                                        @if($category->parent)
                                        ({{ $category->parent->name }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ __('kb.status') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('status') border-red-500 @enderror">
                                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>{{ __('enums.kb_status.draft') }}</option>
                                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>{{ __('enums.kb_status.published') }}</option>
                                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>{{ __('enums.kb_status.archived') }}</option>
                                </select>
                                @error('status') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags & Options -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('kb.tags_options') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('kb.tags') }}</label>
                                <input type="text" name="tags_input" id="tags_input"
                                       value="{{ old('tags') ? implode(', ', old('tags')) : '' }}"
                                       class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3"
                                       placeholder="{{ __('kb.tags_placeholder') }}">
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('kb.tags_hint') }}
                                </p>
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 cursor-pointer hover:from-green-100 hover:to-emerald-100 transition-colors">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                           class="rounded border-2 border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ __('kb.featured_article') }}</span>
                                </label>
                                <label class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 cursor-pointer hover:from-green-100 hover:to-emerald-100 transition-colors">
                                    <input type="checkbox" name="is_internal" value="1" {{ old('is_internal') ? 'checked' : '' }}
                                           class="rounded border-2 border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ __('kb.internal_only') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Changelog -->
                <div class="bg-white shadow-sm hover:shadow-lg rounded-2xl overflow-hidden transition-shadow">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ __('kb.version_changelog') }}</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            {{ __('kb.what_changed') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea name="changelog" rows="2" required
                                  class="w-full border-2 border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors px-4 py-3 @error('changelog') border-red-500 @enderror"
                                  placeholder="{{ __('kb.changelog_placeholder') }}">{{ old('changelog') }}</textarea>
                        @error('changelog') <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 bg-white shadow-sm rounded-2xl overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
                    <p class="text-sm text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('common.required_fields') }}
                    </p>
                    <div class="flex space-x-3">
                        <a href="{{ route('kb.index') }}" class="group px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-semibold transition-colors shadow-sm">
                            <svg class="w-4 h-4 inline mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ __('common.cancel') }}
                        </a>
                        <button type="submit" class="group px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                            <svg class="w-5 h-5 inline mr-1 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('kb.create_article') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    let kbEditorInstance = null;

    // Custom upload adapter for CKEditor
    class SimpleUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file.then(file => {
                return new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('kb.upload-image') }}', true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                    xhr.onload = () => {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.url) {
                                resolve({ default: response.url });
                            } else {
                                reject(response.error?.message || 'Upload failed');
                            }
                        } else {
                            reject('Upload failed');
                        }
                    };

                    xhr.onerror = () => reject('Upload failed');
                    xhr.send(data);
                });
            });
        }

        abort() {
            // Abort upload if needed
        }
    }

    // Register upload adapter
    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new SimpleUploadAdapter(loader);
        };
    }

    ClassicEditor
        .create(document.querySelector('#content'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', 'imageUpload', 'mediaEmbed', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            },
            language: 'id'
        })
        .then(editor => {
            kbEditorInstance = editor;
        })
        .catch(error => {
            console.error('CKEditor initialization error:', error);
        });

    // Validate CKEditor content before form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        if (kbEditorInstance) {
            const editorData = kbEditorInstance.getData();
            // Update the textarea with editor content
            document.getElementById('content').value = editorData;

            // Validate content is not empty
            const plainText = editorData.replace(/<[^>]*>/g, '').trim();
            if (!plainText) {
                e.preventDefault();
                alert('{{ __('kb.content_placeholder') }} harus diisi.');
                return false;
            }
        }
    });
</script>
@endpush
@endsection
