<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEnterpriseRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class UsersController extends Controller
{
    public function register(CreateEnterpriseRequest $request)
    {

        try {

            $user = User::create([
                'email' => $request->inn,
                'password' => Hash::make($request->password),
            ]);

            $user_details = [
                'name' => $request->name,
                'address' => $request->address,
                'lastname' => $request->lastname,
                'secondname' => $request->secondname,
                'inn' => $request->inn,
                'fincode' => $request->fincode,
            ];

            $user->details()->create($user_details);

            DB::select('call CopyInvoices(?)', array($user->id));

            $token = JWTAuth::fromUser($user);

            return $this->respondWithToken($token);

        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 400);

        }

    }

    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['errors' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['errors' => 'could_not_create_token'], 500);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken());
            return response()->json([
                'status' => 'success',
                'message' => "User successfully logged out.",
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Failed to logout, please try again.',
            ], 500);
        }
    }

    private function respondWithToken($token)
    {

        $user = JWTAuth::user();
        $user_details = $user->details()->get()->first();
        $user_details["email"] = $user["email"];

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'enterprise' => $user_details,
        ]);
    }
}
