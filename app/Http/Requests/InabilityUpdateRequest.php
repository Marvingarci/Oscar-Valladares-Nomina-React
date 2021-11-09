<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InabilityUpdateRequest extends FormRequest
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
            'employee_id' => 'required',
            'caption' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'El empleado es obligatorio.',
            'caption.required' => 'La descripcion es obligatoria.',
            'start_date.required' => 'la fecha inicial es obligatoria.',
            'end_date.required' => 'La fecha final es obligatoria.'
        ];
    }
}
