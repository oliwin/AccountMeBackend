<?php

namespace App\Http\Requests\Transactions;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{

    public function authorize()
    {

        return true;

    }

    public function messages()
    {
        return [
            'date' => 'required|date_format:Y-m-d',
            'remark' => 'string',
            'transactions' => 'required|array',
            'transactions.remark' => 'required|string',
			'transactions.amount' => 'required|numeric',
			'transactions.type' => 'required|numeric',
            'transactions.invoice.AC_id' => 'required|numeric',
        ];
    }

    public function rules()
    {

        return [
            'date' => 'required|date_format:Y-m-d',
            'remark' => 'string',
            'project_code' => 'string',
            'transactions' => 'required|array',
            'transactions.amount' => 'required|numeric',
			'transactions.type' => 'required|numeric',
            'transactions.remark' => 'required|string',
            'transactions.invoice.AC_id' => 'required|numeric',
        ];
    }
}
