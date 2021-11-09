<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayUpdateRequest extends FormRequest
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
            'id.required' => 'El id de feriado es obligatorio.',
            'name_holiday.required' => 'El nombre del feriado es obligatorio.',
            'date.required' => 'La fecha ya existe.'
        ];
    }
}
