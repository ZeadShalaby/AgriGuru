<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        if ($validator != true) {
            return $validator;
        }
        try {
            $credentials = $request->only('email', 'password');
            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return $this->returnError('401', 'information not valid');
            }
            $user = Auth::guard('api')->user();
            $user->token = $token;
            return $this->returnData("users", $user);

        } catch (Exception $e) {
            return $this->returnError('500', $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        $validator = $this->validate($request, ["email" => "required|email|unique:users,email", "password" => "required|string|min:8|max:20", "name" => "required|string|max:255"]);
        if ($validator !== true) {
            return $validator;
        }
        try {
            User::create($request->all());
            return $this->returnSuccessMessage("register successfully");
        } catch (Exception $e) {
            return $this->returnError('500', $e->getMessage());
        }
    }
}




