<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Response;

class CreateEnterpriseRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {

        return response()->json(['errors' => $validator->errors()], 400);

    }

    public function authorize()
    {

        return true;

    }

    public function messages()
    {
        return [
            'password' => 'required|string',
            'name' => 'required|string',
            'secondname' => 'required|string',
            'address' => 'string',
            'lastname' => 'required|string',
            'inn' => 'numeric|max:10',
        ];
    }

    public function rules()
    {

        return [
            'password' => 'required|string',
            'name' => 'required|string',
            'secondname' => 'required|string|nullable',
            'address' => 'string|nullable',
            'lastname' => 'required|string',
            'inn' => 'numeric|max:10|unique:users|min:10',

        ];
    }
}
