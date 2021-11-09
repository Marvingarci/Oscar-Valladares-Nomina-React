<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollByPositionStoreRequest extends FormRequest
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
            'position_id' => 'required',
            'salary_structure_id' => 'required',
            'salary_rules' => 'required',
            'start_date' => 'required',
            'final_date' => 'required|date|after_or_equal:start_date',           
        ];
    }

    public function messages()
    {
        return [
            'position_id.required' => 'Debe seleccionar el puesto.',
            'salary_structure_id.required' => 'La estructura salarial es obligatoria.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'final_date.required' => 'La fecha final es obligatoria.',
            'final_date.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha incial.',
        ];
    }
}
