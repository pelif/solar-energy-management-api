<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|uuid',
            'uf' => 'required|string|size:2',
            'installation_type' => 'required|string',
            'equipment' => 'required|array',
            'equipment.*.type' => 'required|string',
            'equipment.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'O ID do cliente é obrigatório',
            'uf.required' => 'A UF é obrigatória',
            'installation_type.required' => 'O tipo de instalação é obrigatório',
            'equipment.required' => 'Os equipamentos são obrigatórios',
            'equipment.*.type.required' => 'O tipo de equipamento é obrigatório',
            'equipment.*.quantity.required' => 'A quantidade de equipamento é obrigatória',
            'equipment.*.quantity.integer' => 'A quantidade de equipamento deve ser um número inteiro',
            'equipment.*.quantity.min' => 'A quantidade de equipamento deve ser maior que 0',
        ];
    }

}
