<?php

namespace App\Http\Requests;

use App\Transactions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Exceptions\CustomValidationFail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class DeleteInvoiceRequest extends FormRequest
{
	
	protected function failedValidation(Validator $validator)
{
    throw new CustomValidationFail($validator->errors());
}


    public function authorize(Request $request)
    {

        return true;
    }

    public function messages()
    {
        return [
            'id.required' => 'Required',
            'id.number' => 'Number',
        ];
    }

    public function rules()
    {
        return [
            'id' => 'required|numeric',
        ];
    }
}
