<?php

namespace App\Http\Requests;

use App\Rules\InvoiceCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Exceptions\CustomValidationFail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CreateInvoiceRequest extends FormRequest
{

    public function authorize(Request $request)
    {
        return true;

    }
	
		protected function failedValidation(Validator $validator)
{
    throw new CustomValidationFail($validator->errors());
}

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'name.string' => 'The name should be a string',
			'group' => 'nullable|string',
			'code.required' => 'Code',
			  'code.string' => 'Code',
			  'type.required' => 'Type',
			  'remark.string' => 'string',
			  	'id' => 'numeric'
        ];
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
			'id' => 'numeric',
			'group' => 'nullable|string',
			'type' => 'nullable|string',
			'remark' => 'nullable|string',
            'code' => ['required', 'string', new InvoiceCode],
        ];
    }
}
