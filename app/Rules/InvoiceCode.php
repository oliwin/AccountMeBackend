<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class InvoiceCode implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $reg = "/^\d+(?:\.\d+)*$/";

        return preg_match($reg, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The invoice code should be in format <number>.<number>.<n>';
    }
}
