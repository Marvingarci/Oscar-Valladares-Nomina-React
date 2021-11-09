<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaryStructureStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Estructuras Salariales');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:30', Rule::unique('salary_structures')],
            'description' => 'required', 'max:150',
            'salary_rules' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe de tener más de 30 caracteres.',
            'name.unique' => 'El nombre ya existe.',
            'description.required' => 'La descripción es obligatoria.',
            'description.max' => 'La descripción no debe de tener más de 150 caracteres.',
            'salary_rules.required' => 'Debe ingresar al menos una una regla salarial.',
        ];
    }
}
