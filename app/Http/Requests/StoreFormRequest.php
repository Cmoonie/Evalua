<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFormRequest extends FormRequest
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
        return [ // Dit stond eerst allemaal in de store() method!
            // Formulier zelf
            'title'       => ['required', 'string', 'max:32'],
            'subject'     => ['required', 'string', 'max:32'],
            'description' => ['required', 'string'],

            // Competenties
            'competencies'                              => ['required', 'array'],
            'competencies.*.name'                       => ['required', 'string', 'max:32'],
            'competencies.*.domain_description'         => ['required', 'string'],
            'competencies.*.rating_scale'               => ['required', 'string'],
            'competencies.*.complexity'                 => ['required', 'string'],

            // Componenten
            'competencies.*.components'                 => ['required', 'array'],
            'competencies.*.components.*.name'          => ['required', 'string', 'max:32'],
            'competencies.*.components.*.description'   => ['required', 'string'],

            // Beoordelingsniveaus
            'competencies.*.components.*.levels'                        => ['required', 'array'],
            'competencies.*.components.*.levels.*.grade_level_id'       => ['required', Rule::exists('grade_levels', 'id')],
            'competencies.*.components.*.levels.*.description'          => ['required', 'string'],
        ];
    }
}
