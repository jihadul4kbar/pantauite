<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');
        
        // Check if user can update this specific ticket
        return auth()->user()->can('update', $ticket);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();
        $isStaff = $user->hasPermission('manage-tickets') || $user->hasRole('it_manager');

        // End users can only update limited fields
        if (!$isStaff) {
            return [
                'subject' => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'string'],
            ];
        }

        // IT staff/manager can update all fields
        return [
            'subject' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'priority' => ['sometimes', Rule::enum(TicketPriority::class)],
            'category_id' => ['nullable', 'exists:ticket_categories,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'status' => ['sometimes', Rule::enum(TicketStatus::class)],
            'resolution_notes' => ['nullable', 'string'],
            'satisfaction_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'satisfaction_feedback' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'assignee_id.exists' => 'Selected assignee does not exist.',
            'status.enum' => 'Invalid status.',
            'satisfaction_rating.min' => 'Rating must be at least 1.',
            'satisfaction_rating.max' => 'Rating cannot exceed 5.',
        ];
    }
}
