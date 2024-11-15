<?php

namespace App\Rules;

use App\EnterpriseInvoice;
use App\Transactions;
use Illuminate\Contracts\Validation\Rule;

class ExistTransactionsInvoice implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $invoice_code = EnterpriseInvoice::where('code', $value)->first();

        return Transactions::where('AT_code', $invoice_code)->get()->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
