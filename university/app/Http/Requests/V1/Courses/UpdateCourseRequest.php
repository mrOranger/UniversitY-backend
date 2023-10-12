<?php

namespace App\Http\Requests\V1\Courses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'bail'],
            'sector' => ['required', 'string', 'bail'],
            'starting_date' => ['required', 'date', 'bail'],
            'ending_date' => ['required', 'date', 'bail', 'after_or_equal:starting_date'],
            'cfu' => ['required', 'numeric', 'bail'],
            'professor' => [
                'role' => ['required', 'string', 'in:researcher,associate,full'],
                'subject' => ['required', 'string'],
                'user' => [
                    'first_name' => ['bail', 'required', 'string'],
                    'last_name' => ['bail', 'required', 'string'],
                    'email' => ['bail', 'email', 'required'],
                    'birth_date' => ['bail', 'required', 'date'],
                ],
            ],
        ];
    }
}
