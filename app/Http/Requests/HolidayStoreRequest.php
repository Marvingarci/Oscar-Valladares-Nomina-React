<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_holiday' => 'required',
            'date' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name_holiday.required' => 'El nombre del feriado es obligatorio.',
            'date.required' => 'La fecha es obligatoria.'
        ];
    }
}
