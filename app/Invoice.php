<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = ['AC_code', 'AC_name', 'AC_fname', 'AC_type', 'AC_group'];

}
