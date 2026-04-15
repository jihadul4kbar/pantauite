<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('asset'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'part_number' => ['nullable', 'string', 'max:100'],
            'specs' => ['nullable', 'array'],
            'status' => ['sometimes', 'in:procurement,inventory,deployed,maintenance,retired,disposed'],
            'condition' => ['sometimes', 'in:new,good,fair,poor,broken'],
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
            'delete_old_images' => ['sometimes', 'boolean'],
        ];

        // Only validate images if files are actually uploaded
        if ($this->hasFile('images')) {
            $rules['images'] = ['required', 'array', 'max:5'];
            $rules['images.*'] = ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'];
        }

        return $rules;
    }
}
