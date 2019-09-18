<?php

namespace App\Http\Controllers;

use App\TransactionsType;
use Response;
use Illuminate\Http\Request;
use App\Http\Resources\TransactionTypeResource;

class TransactionTypeController extends Controller
{

    public function index()
    {
		
        return TransactionTypeResource::collection(TransactionsType::get());
    }

}
