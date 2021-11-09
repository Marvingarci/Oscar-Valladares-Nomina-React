<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Categorias Vitolas');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:20', Rule::unique('categories')],
            'price_hundred' => 'required', 'numeric', 'min:1', 'max:5',
            'vitolas' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe de tener más de 20 caracteres.',
            'name.unique' => 'El nombre ya existe.',
            'price_hundred.required' => 'El precio es obligatorio.',
            'vitolas.required' => 'Debe ingresar al menos una vitola.',

        ];
    }
}
