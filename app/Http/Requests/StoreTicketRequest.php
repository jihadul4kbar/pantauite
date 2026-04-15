<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermission('create-tickets');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', Rule::enum(TicketPriority::class)],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'source' => ['nullable', Rule::in(['web', 'phone', 'walk-in', 'email'])],
            'attachments.*' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,log'],
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
            'subject.required' => 'Ticket subject is required.',
            'subject.max' => 'Subject cannot exceed 255 characters.',
            'description.required' => 'Please describe the issue you are experiencing.',
            'priority.required' => 'Please select a priority level.',
            'priority.enum' => 'Invalid priority level.',
            'category_id.exists' => 'Selected category does not exist.',
            'department_id.exists' => 'Selected department does not exist.',
            'attachments.*.max' => 'Each file must not exceed 5MB.',
            'attachments.*.mimes' => 'Invalid file type. Allowed: images, PDF, Word, Excel, text files.',
        ];
    }
}
