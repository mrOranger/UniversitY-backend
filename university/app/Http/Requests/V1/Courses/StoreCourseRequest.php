<?php

namespace App\Http\Requests\V1\Courses;

use App\Http\Requests\V1\Teachers\StoreTeacherRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
            'starting_date' => ['required', 'date', 'date_format:Y-m-d', 'bail'],
            'ending_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:starting_date', 'bail'],
            'cfu' => ['required', 'numeric', 'bail'],
            'professor' => (new StoreTeacherRequest())->rules()
        ];
    }
}
