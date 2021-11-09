<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaryRuleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Reglas Salariales');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'concept' => 'required', 'max:30', Rule::unique('salary_rules'),
            'code' => 'required', 'max:10',
            'type' => 'required', 'max:30'
        ];
    }


    public function messages(){
        return [
            'concept.required' => 'El concepto es obligatorio.',
            'concept.max' => 'El concepto no debe tener más de 30 caracteres.',
            'concept.unique' => 'El concepto ya existe.',
            'type.required' => 'El tipo es obligatorio.',
            'type.max' => 'El tipo no debe tener más de 30 caracteres.',
            'code.required' => 'El código es obligatorio.',
            'code.max' => 'El código no debe tener más de 10 caracteres.'

        ];
    }
}
