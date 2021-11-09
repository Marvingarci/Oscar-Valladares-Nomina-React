<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Actualizar Tickets');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|numeric|min: 1',
            'user_id' => 'required',
            'rolero_id' => 'required|numeric|min: 1',
            'bonchero_id' => 'required|numeric|min: 1',
            'amount_of_cigars' => 'required|numeric|min: 1',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Seleccione un producto.',
            'product_id.min' => 'Debe seleccionar un producto.',
            'user_id.required' => 'El Usuario es obligatorio.',
            'rolero_id.required' => 'Seleccione un rolero.',
            'rolero_id.min' => 'Debe seleccionar un rolero.',

            'bonchero_id.required' => 'Seleccione un bonchero.',
            'bonchero_id.min' => 'Debe seleccionar un bonchero.',

            'amount_of_cigars.required' => 'Seleccione una cantidad.',
            'amount_of_cigars.min' => 'Debe seleccionar una cantidad.',
        ];
    }
}
