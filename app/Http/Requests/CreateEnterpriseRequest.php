<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateEnterpriseRequest extends FormRequest
{
	
		protected function failedValidation(Validator $validator)
{
    throw new CustomValidationFail($validator->errors());
}

    public function authorize()
    {

        return true;

    }

    public function messages()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'name' => 'required|string',
            'secondname' => 'required|string',
            'address' => 'string',
            'lastname' => 'required|string',
            'inn' => 'numeric|max:15',
            'fincode' => 'required|string|max:8',
        ];
    }

    public function rules()
    {

        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'name' => 'required|string',
            'secondname' => 'required|string',
            'address' => 'string',
            'lastname' => 'required|string',
            'inn' => 'string|max:15',
            'fincode' => 'required|string|max:8',
        ];
    }
}
