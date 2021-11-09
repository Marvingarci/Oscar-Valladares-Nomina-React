<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Empleados');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'department_id' => 'required',
            'position_id' => 'required',
            'company_id' => 'required',
            'full_name' => 'required | max:150',
            'gender' => 'required',
            'date_of_birth' => 'required | date',
            'identy' => ['required', ' max:13',' min:13', Rule::unique('employees')],
            'address' => 'max:200',
            'phone_number' => 'required | size:8',
            'employee_code' => ['required',' max:20', Rule::unique('employees')],
           
        ];
    }
    public function messages()
    {
        return [
            'department_id.required' => 'El departamento es requerido.',
            'position_id.required' => 'El puesto es obligatorio.',
            'company_id.required' => 'La empresa es obligatoria.',
            'full_name.required' => 'El nombre es obligatorio.',
            'full_name.max' => 'Nombre muy extenso.',
            'gender.required' => 'El género es obligatorio.',
            'date_of_birth.required' => 'La fecha de nacimiento es obligatoria.',
            'date_of_birth.date' => 'Formato no válido.',
            'identy.required' => 'La identidad es obligatoria.',
            'identy.max' => 'El campo tiene más de 13 caracteres.',
            'identy.min' => 'EL campo debe tener 13 caracteres.',
            'identy.unique' => 'Esta identidad ya existe.',
            'identy.numeric' => 'Deben ser caracteres numéricos.',
            'address.max' => 'Direción muy extensa.',
            'phone_number.required' => 'El número telefónico es obligatorio.',
            'phone_number.numeric' => 'Deben ser caracteres numéricos',
            'phone_number.size' => 'El número telefónico debe tener 8 dígitos.',
            'employee_code.required' => 'El código es obligatorio.',
            'employee_code.max' => 'El campo esta muy extenso.',
            'employee_code.unique' => 'El campo debe ser único.',
            'employee_code.numeric' => 'Deben ser caracteres numéricos.',

        ];
    }
}
