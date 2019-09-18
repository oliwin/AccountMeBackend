<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FicheType extends Model
{
    protected $table = 'fiche_types';

    protected $primaryKey = 'id';

    protected $guarded = [];

}
