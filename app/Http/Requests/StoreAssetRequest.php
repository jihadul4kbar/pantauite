<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Asset::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'asset_type' => ['required', 'in:hardware,software,network'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'part_number' => ['nullable', 'string', 'max:100'],
            'specs' => ['nullable', 'array'],
            'status' => ['required', 'in:procurement,inventory,deployed,maintenance,retired,disposed'],
            'condition' => ['required', 'in:new,good,fair,poor,broken'],
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
            'assigned_to_department_id' => ['nullable', 'exists:departments,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'vendor_name' => ['nullable', 'string', 'max:255'],
            'purchase_order_number' => ['nullable', 'string', 'max:100'],
            'purchase_date' => ['nullable', 'date'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'warranty_start' => ['nullable', 'date'],
            'warranty_end' => ['nullable', 'date', 'after_or_equal:warranty_start'],
            'warranty_provider' => ['nullable', 'string', 'max:255'],
            'warranty_notes' => ['nullable', 'string'],
            'depreciation_method' => ['nullable', 'in:straight_line,declining_balance,none'],
            'useful_life_years' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'asset_type.required' => 'Asset type is required.',
            'asset_type.in' => 'Asset type must be one of: hardware, software, network.',
            'name.required' => 'Asset name is required.',
            'status.required' => 'Asset status is required.',
            'condition.required' => 'Asset condition is required.',
            'price.numeric' => 'Price must be a valid number.',
            'warranty_end.after_or_equal' => 'Warranty end date must be after start date.',
            'images.required' => 'Wajib upload minimal 1 gambar',
            'images.min' => 'Minimal 1 gambar',
            'images.max' => 'Maksimal 5 gambar',
            'images.*.image' => 'File harus gambar',
            'images.*.max' => 'Ukuran gambar maksimal 5MB',
        ];
    }
}
