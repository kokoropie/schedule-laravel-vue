<?php

namespace App\Http\Requests\ScheduleDetail;

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
        $numOfClassPeriodsPerDay = request()->route()->parameter("schedule")->numOfClassPeriodsPerDay;
        return [
            'subject_id' => 'required|exists:subjects,subject_id',
            'start' => 'required|numeric|min:1|max:' . $numOfClassPeriodsPerDay,
            'end' => 'required|numeric|gte:start|max:' . $numOfClassPeriodsPerDay,
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'type' => 'required|in:OFFLINE,ONLINE',
            'dateOfWeek' => 'required|array',
            'dateOfWeek.*' => 'numeric|in:1,2,3,4,5,6,7',
            'is_makeUp_class' => 'boolean'
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
            'subject_id.required' => 'Please choose subject',
            'subject_id.exists' => 'Subject doesn\'t exist',
            'start.required' => 'Please input starting class periods',
            'start.numeric' => 'Please input starting class periods',
            'start.min' => 'Atleast, starting class periods must be at 1',
            'start.max' => 'Class periods just have to be started at :max',
            'end.required' => 'Please input ending class periods',
            'end.numeric' => 'Please input ending class periods',
            'end.gte' => 'Class periods must be ended after or equal the starting',
            'end.max' => 'Atleast, ending class periods must be at :max',
            'from.required' => 'Please input schedule\'s date from',
            'from.date' => 'Please input schedule\'s date from',
            'to.required' => 'Please input schedule\'s date to',
            'to.date' => 'Please input schedule\'s date to',
            'to.after_or_equal' => 'Atleast, the schedule needs to have 1 day to study',
            'type.required' => 'Please choose type to study',
            'type.in' => 'Please choose type to study',
            'dateOfWeek.required' => 'Please choose date of week',
            'dateOfWeek.array' => 'Please choose date of week',
            'dateOfWeek.*.numeric' => 'Date doen\'t exist',
            'dateOfWeek.*.in' => 'Date doen\'t exist',
            'is_makeUp_class.boolean' => 'Is make-up class much be a checkbox value.',
        ];
    }
}
