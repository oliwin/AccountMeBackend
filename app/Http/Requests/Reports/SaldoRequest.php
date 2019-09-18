<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\CustomValidationFail;

class SaldoRequest extends FormRequest
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
            'from' => 'required|date_format:Y-m-d',
			'to' => 'required|date_format:Y-m-d'
        ];
    }

    public function rules()
    {

        return [
            'from' => 'required|date_format:Y-m-d',
			'to' => 'required|date_format:Y-m-d'
        ];
    }
}
