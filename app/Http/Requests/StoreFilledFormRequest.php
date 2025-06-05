<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFilledFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // De data die uit het formulier komt
            'form_id'      => ['required', Rule::exists('forms', 'id')],
            'student_name' => ['required', 'string', 'max:64'],
            'student_number' => ['required', 'string', 'max:64'],
            'assignment' => ['required', 'string', 'max:100'],
            'business_name' => ['nullable', 'string', 'max:100'],
            'business_location' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            // Componenten
            'components'                   => ['required', 'array'],
            'components.*.component_id'    => ['required', Rule::exists('components', 'id')],
            'components.*.grade_level_id'  => ['required', Rule::exists('grade_levels', 'id')],
            'components.*.comment'         => ['nullable', 'string'],
        ];
    }
}
