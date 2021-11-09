<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateWeightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('Crear Tickets');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'=>'required',
            'status'=>'required',
            'peso' => 'required|numeric|min: 1',
            'observations' => '',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El id es requerido.',
            'status.required' => 'El estado es requerido.',
            'peso.required' => 'El peso es obligatorio.',
            'peso.numeric' => 'El peso debe ser numérico.',
            'peso.min' => 'El peso debe ser mayor a 1.',
        ];
    }
}
