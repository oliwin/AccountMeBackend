<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnterpriseInvoice extends Model
{
    protected $table = 'enterprise_invoces';

    protected $primaryKey = 'AC_id';

    protected $fillable = ['AC_code', 'AC_name', 'AC_fname', 'AC_type', 'AC_group', 'enterprise_id', 'parent_id'];

    protected $dates = ['created_at', 'updated_at'];

    protected $hidden = ['updated_at'];

}
