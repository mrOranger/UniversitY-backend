<?php

namespace App\Http\Requests\V1\Teachers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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
            'role' => ['required', 'string', 'in:researcher,associate,full'],
            'subject' => ['required', 'string'],
            'user' => [
                'first_name' => ['bail', 'required', 'string'],
                'last_name' => ['bail', 'required', 'string'],
                'email' => ['bail', 'email', 'required'],
                'birth_date' => ['bail', 'required', 'date'],
            ],
        ];
    }
}