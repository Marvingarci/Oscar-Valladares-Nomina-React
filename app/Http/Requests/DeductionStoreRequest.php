<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeductionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Ver Reglas Salariales');//duda
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => ['required'],
            'name'=>'required',
            'monto' => 'required|numeric|not_in:0',
            'cuota' => 'required|numeric|not_in:0',
            'pend' => 'required|numeric|not_in:0',
            'status'=>'required'
        ];
    }
    public function messages()
    {
        return [
            'employee_id.required' => 'El id del empleado es requerido.',
            'name.required'=>'Este nombre de la deduccion es requerida.',
            'monto.required' => 'El monto es requerido.',
            'monto.numeric'=>'el monto debe ser numerico',
            'cuota.required' => 'La cuota es requerida.',
            'cuota.numeric'=>'La cuota debe ser numerico',
            'status.required' => 'Lel estatus es requerido.',
        ];
    }
}
