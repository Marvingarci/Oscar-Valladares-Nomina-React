<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateDefectsRequest extends FormRequest
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
            'amount_of_cigars'=> 'required',
            'supervisor_id' => 'required',
            'trancados' => 'required | numeric',
            'botados' => 'required | numeric',
            'pelados' => 'required | numeric',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El id es requerido.',
            'status.required' => 'El estado es requerido.',
            'supervisor_id.required' => 'trancados es obligatorio.',
            'trancados.required' => 'trancados es obligatorio.',
            'amount_of_cigars.required' => 'cantidad es obligatorio.',
            'tracados.numeric' => 'trancados debe ser numérico.',
            'botados.required' => 'botados es necesaria.',
            'botados.numeric' => 'botados debe ser numérico.',
            'pelados.required' => 'pelados es obligatorio.',
            'pelados.numeric' => 'pelados debe ser numérico.',
        ];
    }
}
