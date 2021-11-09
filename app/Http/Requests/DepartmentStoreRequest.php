<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Departamentos');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:20', Rule::unique('departments')],
            'dep_code' => ['required', Rule::unique('departments')],
        ];
    }

    public function messages(){
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre debe tener como máximo 20 caracteres.',
            'name.unique' => 'El nombre ya existe.',
            'dep_code.required' => 'El código es obligatorio.',
            'dep_code.unique' => 'El código ya está en uso.',
        ];
    }
}
