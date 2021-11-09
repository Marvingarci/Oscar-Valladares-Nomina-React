<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Productos');//duda
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required',Rule::unique('products')],
            'product_code'=>'required',
            'vitola_id' => 'required|numeric|not_in:0',
            'category_id' => 'required|numeric|not_in:0',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El nombre es requerido.',
            'name.unique'=>'Este producto ya esta registrada.',
            'product_code.required' => 'El código del prodcuto es requerido.',
            'vitola_id.required' => 'La vitola es requerida.',
            'category_id.required' => 'La categoría es requerida.',
        ];
    }
}
