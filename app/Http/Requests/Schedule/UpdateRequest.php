<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Config;

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
            'name' => 'required',
            'numOfClassPeriodsPerDay' => 'required|numeric|min:1',
            'timeOfEachClassPeriod' => 'required|array',
            'timeOfEachClassPeriod.*' => 'required|array:start,end',
            'timeOfEachClassPeriod.*.start' => 'required|distinct|date_format:H:i',
            'timeOfEachClassPeriod.*.end' => 'required|distinct|date_format:H:i|after:timeOfEachClassPeriod.*.start'
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
            'name.required' => 'Please input name',
            'numOfClassPeriodsPerDay.required' => 'Please input num of class periods per day',
            'numOfClassPeriodsPerDay.numeric' => 'Please input num of class periods per day',
            'numOfClassPeriodsPerDay.min' => 'Num of class periods atleast is :min',
            'timeOfEachClassPeriod.required' => 'Please input time of period',
            'timeOfEachClassPeriod.array' => 'Please input time of period',
            'timeOfEachClassPeriod.*.required' => 'Please input time of period :index',
            'timeOfEachClassPeriod.*.array:start,end' => 'Please input time of period :index',
            'timeOfEachClassPeriod.*.start.required' => 'Please input time start of period :index',
            'timeOfEachClassPeriod.*.start.date_format' => 'Please input time start of period :index',
            'timeOfEachClassPeriod.*.end.required' => 'Please input time end of period :index',
            'timeOfEachClassPeriod.*.end.date_format' => 'Please input time end of period :index',
            'timeOfEachClassPeriod.*.end.after' => 'Time end must be after starting of period :index',
        ];
    }
}
