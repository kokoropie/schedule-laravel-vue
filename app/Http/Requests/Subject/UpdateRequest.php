<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'subject_id' => [
                'required',
                \Illuminate\Validation\Rule::unique(\App\Models\Subject::class)->ignore($this->subject->subject_id, 'subject_id')
            ],
            'name' => 'required|string',
            'credits' => 'required|numeric|min:0',
            'teacher_id' => 'required|exists:teachers,teacher_id',
            'color_foreground' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ],
            'color_background' => [
                'required',
                'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'
            ],
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
            'color_foreground.required' => 'Please choose color of subject\'s text',
            'color_foreground.regex' => 'Please choose color of subject\'s text',
            'color_background.required' => 'Please choose color of subject\'s background',
            'color_background.regex' => 'Please choose color of subject\'s background',
            'teacher_id.required' => 'Please select teacher',
            'teacher_id.exists' => 'Teacher doesn\'t exist',
        ];
    }
}
