<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{

    protected $table = 'enterprise';

    protected $fillable = ['name', 'lastname', 'secondname', 'address', 'inn', 'fincode'];
}
