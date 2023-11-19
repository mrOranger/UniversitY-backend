<?php

namespace App\Http\Requests\V1\Common;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    /**
     * Stops the validation process on first failure.
     * 
     * @var bool $stopsOnFirstFailure
     */
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
                'birth_date' => ['bail', 'required', 'date', 'date_format:Y-m-d',],
            ],
        ];
    }
}
