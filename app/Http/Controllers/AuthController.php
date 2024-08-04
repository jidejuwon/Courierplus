<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Helpers\blogHelper;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails())
            return blogHelper::errorResponse($validator->errors()->first());

        $user = User::where('email', $request->email)->first();

        if(!$user)
            return blogHelper::errorResponse('The provided credentials are incorrect.');

        if(!password_verify($request->input('password'), $user->password))
            return blogHelper::errorResponse('The provided credentials are incorrect.');

        $token = $user->createToken('auth-token')->plainTextToken;
        return blogHelper::successResponse(['token' => $token]);
    }


}
