<?php

namespace App\Http\Controllers;

use App\EnterpriseInvoice;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\DeleteInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Invoice;
use App\Transactions;
use Response;
use App\UserDetails;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{

    public function update(UpdateProfileRequest $request)
    {
		
        $user = UserDetails::where('user_id', JWTAuth::user()->id)->update([
            'name' => $request->name,
            'address' => $request->address,
			'lastname' => $request->lastname,
			'secondname' => $request->secondname,
			'inn' => $request->inn,
			'fincode' => $request->fincode
        ]);

        return response()->json($user);
    }
}
