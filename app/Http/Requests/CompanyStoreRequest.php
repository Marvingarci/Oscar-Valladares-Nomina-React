<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Compañias');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:50', Rule::unique('companies')],
            'phone' => ['required', 'size:8'],
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre es muy extenso.',
            'name.unique' => 'El nombre ya existe.',
            'phone.required' => 'El télefono es obligatorio.',
            'phone.size' => 'El télefono debe tener 8 dígitos.',
        ];
    }
}
