<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can("Actualizar Nómina");
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
            'salary_structure_id' => 'required',
            'salary_rules' => 'required',
            'start_date' => 'required',
            'final_date' => 'required|date|after_or_equal:start_date',
            'ordinary_salary' => 'numeric',
            'total_deduc' => 'numeric',
            'total_inc' => 'numeric',
            'total_to_pay' => 'numeric',
            'status' => 'required',
            'days_worked' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'El empleado es obligatorio.',
            'salary_structure_id.required' => 'La estructura salarial es obligatoria.',
            'salary_rules.required' => 'Asegurese de tener reglas salariales dentro de la estructura salarial.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'final_date.required' => 'La fecha final es obligatoria.',
            'final_date.after_or_equal' => 'La fecha final debe ser mayor o igual a la fecha incial',
            'ordinary_salary.numeric' => 'El salario ordinario debe ser un número.',
            'total_deduc.numeric' => 'El total de deducciones debe ser un número.',
            'total_inc.numeric' => 'El total de ingresos debe ser un número.',
            'total_to_pay.numeric' => 'El total a pagar debe ser un número.',
            'status.required' => 'El estado es obligatorio.',
            'days_worked.required' => 'Los días trabajados son obligatorios.',
            'days_worked.numeric' => 'Los días trabajados deben ser un número.',
            'days_worked.min' => 'Los días trabajados deben ser números positivos.',

            
        ];
    }
}
