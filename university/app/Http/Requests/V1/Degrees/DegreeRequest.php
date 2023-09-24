<?php

namespace App\Http\Requests\V1\Degrees;

use Illuminate\Foundation\Http\FormRequest;

class DegreeRequest extends FormRequest
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
            'name' => ['bail', 'required','string','max:255', 'unique:degrees,name'],
            'code' => ['bail', 'required', 'string', 'max:255'],
            'course_type' => ['required', 'string', 'in:bachelor,master,phd']
        ];
    }

    public function messages () : array
    {
        return [
            'in' => 'The :attribute field must be bachelor, master or phd.'
        ];
    }
}
