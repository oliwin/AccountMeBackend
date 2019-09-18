<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use App\Exceptions\CustomValidationFail;

class RemainsRequest extends FormRequest
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
            'date' => 'required|date_format:Y-m-d'
        ];
    }

    public function rules()
    {

        return [
            'date' => 'required|date_format:Y-m-d'
        ];
    }
}
