<?php

namespace App\Http\Requests;

use App\Models\KbArticle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKbArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route parameter is 'kb', not 'article'
        $article = $this->route('kb');
        
        if (!$article) {
            return false;
        }
        
        return auth()->user()->can('update', $article);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string'],
            'summary' => ['nullable', 'string'],
            'category_id' => ['sometimes', 'exists:kb_categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_internal' => ['sometimes', 'boolean'],
            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived'])],
            'changelog' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ];
    }
}
