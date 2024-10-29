<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    use ResponseTrait;
    //
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|max:20',
        ]);

        if ($validator->fails()) {
            return $this->returnError('401', $validator->errors());
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



    // ?todo verify account users
    public function verify($id)
    {
        try {
            $user = User::find($id);
            $user->email_verified_at = now();
            $user->save();
            // return $this->returnSuccessMessage("Verify Success", "V000");
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    // ?todo edit return info for user
    public function edit(User $user)
    {
        try {
            return $this->returnData("user", $user);
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    // ?todo update info for user
    public function update(Request $request, User $user)
    {
        try {
            $validator = $this->validate($request, $this->rulesUpdateUsers());
            if ($validator !== true) {
                return $validator;
            }
            $user->update($request->all());
            return $this->returnSuccessMessage("Update Success", "U000");
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }


    // // ?todo read notification for users
    // public function readNotification(Request $request, $id)
    // {
    //     try {
    //         $notification = auth()->user()->notifications()->find($id);
    //         if ($notification) {
    //             $notification->markAsRead();
    //         }
    //         return $this->returnSuccessMessage("Read Success", "R000");
    //     } catch (Exception $e) {
    //         return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
    //     }
    // }


    // ?todo logout in account
    public function logout(Request $request)
    {
        $token = $request->token;
        if (isset($token)) {
            try {
                // ?todo logout
                JWTAuth::setToken($token)->invalidate();
            } catch (TokenInvalidException $e) {
                return $this->returnError("T003", "Some Thing Went Wrongs " . $e->getMessage());
            } catch (TokenExpiredException $e) {
                return $this->returnError("T002", "Some Thing Went Wrongs " . $e->getMessage());
            }
            return $this->returnSuccessMessage('Logged Out Successfully');
        } else {
            return $this->returnError("T001", "Some Thing Went Wrongs .");
        }
    }

}




