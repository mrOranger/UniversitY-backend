<?php

namespace App\Http\Requests\V1\Students;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'user' => [
                'first_name' => ['bail', 'required', 'string'],
                'last_name' => ['bail', 'required', 'string'],
                'email' => ['bail', 'email', 'required'],
                'birth_date' => ['bail', 'required', 'date'],
            ],
            'bachelor_final_mark' => ['bail', 'numeric', 'min:66', 'max:110', 'nullable'],
            'master_final_mark' => ['bail', 'numeric', 'min:66', 'max:110', 'nullable'],
            'phd_final_mark' => ['bail', 'numeric', 'min:66', 'max:110', 'nullable'],
            'outside_prescribed_time' => ['bail', 'required', 'boolean'],
            'degree' => [
                'name' => ['bail', 'required', 'string', 'max:255', 'unique:degrees,name'],
                'code' => ['bail', 'required', 'string', 'max:255'],
                'course_type' => ['required', 'string', 'in:bachelor,master,phd'],
            ],
        ];
    }
}
