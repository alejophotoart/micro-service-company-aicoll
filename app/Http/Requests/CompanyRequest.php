<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        // Solo si estamos actualizando una empresa (es decir, si viene en la ruta)
        if ($this->company) {
            // Elimina 'nit' del input para que no se pueda modificar
            $this->request->remove('nit'); // ← importante
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // 'nit' => [
            //     'required',
            //     'regex:/^[0-9\-CF]+$/i',
            //     Rule::unique('companies')->ignore($this->company),
            // ],
            'name' => [
                'required',
                'max:100',
                'string'
            ],
            'address' => [
                'required',
                'max:100',
                'string'
            ],
            'phone' => [
                'required',
                'max:20',
                'string'
            ],
            'active' => 'nullable|boolean',
        ];

        if (!$this->route('company')) {
            $rules['nit'] = [
                'required', 
                'regex:/^[0-9\-CF]+$/i',
                Rule::unique('companies')->ignore($this->company)
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'nit'     => 'NIT',
            'name'    => 'nombre',
            'address' => 'direccion',
            'phone'   => 'telefono',
            'active'  => 'activo',
        ];

    }

    public function messages(): array
    {
        return [
            'max'                => 'El campo :attribute es muy largo',
            'required'           => 'El campo :attribute es requerido',
            'string'             => 'El campo :attribute no es un valor permitido',
            'active.boolean'     => 'El campo :attribute no tiene un valor correcto',
            'nit.unique'         => 'NIT ya existe, debe registrar otro',
            'nit.regex'          => 'Numero de nit no valido',
        ];
    }

    /**
     * En caso que no se especifique el Accept en la peticion, 
     * se debe forzar al Backend de devolver la respuesta en JSON
     */
    // protected function failedValidation(Validator $validator)
    // {
    //     throw new HttpResponseException(response()->json([
    //         'message' => 'Los datos enviados no son válidos.',
    //         'errors' => $validator->errors()
    //     ], 422));
    // }
}
