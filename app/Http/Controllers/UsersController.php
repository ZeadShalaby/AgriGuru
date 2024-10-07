<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\RequestLogin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    use ResponseTrait;
    //
    public function login(Request $request)
    {
        $validator = $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|max:20',
        ]);
        if ($validator !== true) {
            return $validator;
        }
        try {
            $credentials = $request->only('email', 'password');
            $token = Auth::guard('api')->attempt($credentials);
            $user = Auth::guard('api')->user();
            $user->token = $token;
            return $this->returnData('user', $user);
        } catch (Exception $e) {
            return $this->returnError('401', 'Unauthenticated user');
        }
    }
}




