<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subject_id' => 'required|unique:subjects,subject_id',
            'name' => 'required|string',
            'credits' => 'required|numeric|min:1',
            'teacher_id' => 'required|exists:teachers,teacher_id'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'subject_id.required' => 'Please input subject\'s ID',
            'subject_id.unique' => 'Subject\'s ID existed', 
            'name.required' => 'Please input subject\'s name',
            'name.string' => 'Subject\'s name isn\'t in right format',
            'credits.required' => 'Please input subject\'s credits',
            'credits.numeric' => 'Subject\'s credits must be numeric',
            'credits.min' => 'Subject\'s credits must be greater than or equal to  :min',
            'teacher_id.required' => 'Please select teacher',
            'teacher_id.exists' => 'Teacher doesn\'t exist',
        ];
    }
}
