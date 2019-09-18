<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TransactionsType;

class Transactions extends Model
{
	
	public $timestamps = false;
	
    protected $table = 'account_transactions';

    protected $primaryKey = 'AT_id';

    protected $guarded = [];
	
	public function type()
    {
        return $this->hasOne(TransactionsType::class, 'id', 'AT_type');
    }
}
