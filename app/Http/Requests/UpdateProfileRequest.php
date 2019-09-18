<?php

namespace App\Http\Requests;

use App\Rules\InvoiceCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Exceptions\CustomValidationFail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateProfileRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
	
	protected function failedValidation(Validator $validator)
{
    throw new CustomValidationFail( $validator->errors());
}

   public function messages()
    {
        return [
            'name' => 'required|string',
            'secondname' => 'required|string',
            'address' => 'string',
            'lastname' => 'required|string',
            'inn' => 'numeric|max:15',
            'fincode' => 'required|string|max:8'
        ];
    }

    public function rules()
    {

        return [
            'name' => 'required|string',
            'secondname' => 'required|string',
            'address' => 'string',
            'lastname' => 'required|string',
            'inn' => 'string|max:15',
            'fincode' => 'required|string|max:8',
        ];
    }
}
