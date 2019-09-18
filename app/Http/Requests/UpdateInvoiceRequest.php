<?php

namespace App\Http\Requests;

use App\Rules\InvoiceCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Exceptions\CustomValidationFail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateInvoiceRequest extends FormRequest
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
            'name.required' => 'The name is required.',
            'name.string' => 'The name should be a string',
            'code.required' => 'The name is required.',
            'code.string' => 'The name should be a string',
            'code.InvoiceCode' => 'InvoiceCode error',
			'remark.string' => 'string',
			'group' => 'nullable|string',
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
			'remark' => 'nullable|string',
			'type' => 'nullable|string',
			'group' => 'nullable|string',
            'code' => ['required', 'string', new InvoiceCode],
        ];
    }
}
