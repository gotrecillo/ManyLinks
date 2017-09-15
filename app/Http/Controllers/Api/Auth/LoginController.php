<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $valid = Auth::validate($request->only(['email', 'password']));

        return $valid
            ? response()->json([
                "data" => [
                    "token" => User::tokenByEmail($request->get('email'))
                ]
            ])
            : response()->json([
                "error" => true,
                "message" => "Invalid credentials"
            ], 401);
    }
}
