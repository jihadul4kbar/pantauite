<?php

namespace App\Http\Requests;

use App\Services\CaptchaService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRepairRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public form, no authentication required
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'requester_name' => ['required', 'string', 'max:255'],
            'requester_email' => ['required', 'email', 'max:255'],
            'requester_phone' => ['nullable', 'string', 'max:50'],
            'requester_department' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:critical,high,medium,low'],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'asset_name' => ['nullable', 'string', 'max:255'],
            'asset_serial' => ['nullable', 'string', 'max:255'],
            'captcha' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'requester_name.required' => 'Nama wajib diisi.',
            'requester_email.required' => 'Email wajib diisi.',
            'requester_email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek permasalahan wajib diisi.',
            'description.required' => 'Deskripsi permasalahan wajib diisi.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas yang dipilih tidak valid.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'captcha.required' => 'CAPTCHA wajib diisi.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'requester_name' => 'nama pengaju',
            'requester_email' => 'email pengaju',
            'requester_phone' => 'nomor telepon',
            'requester_department' => 'departemen',
            'subject' => 'subjek permasalahan',
            'description' => 'deskripsi permasalahan',
            'priority' => 'prioritas',
            'category_id' => 'kategori',
            'location' => 'lokasi',
            'asset_name' => 'nama perangkat',
            'asset_serial' => 'nomor seri',
            'captcha' => 'CAPTCHA',
        ];
    }

    /**
     * Handle failed validation with custom CAPTCHA check.
     */
    protected function failedValidation(Validator $validator)
    {
        // If CAPTCHA validation failed, regenerate it
        if ($validator->errors()->has('captcha')) {
            // CAPTCHA error will be handled by the controller
        }
        
        throw new HttpResponseException(redirect()->back()
            ->withErrors($validator)
            ->withInput());
    }
}
