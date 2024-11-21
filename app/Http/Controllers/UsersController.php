<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Media;
use App\Mail\VerifyMail;
use App\Traits\MethodTrait;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Traits\Requests\TestAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UsersController extends Controller
{
    use ResponseTrait, MethodTrait, TestAuth, ImageTrait;
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
            if (!$token) {
                return $this->returnError('401', 'information not valid');
            }
            $user = Auth::guard('api')->user()->load('media_one');
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
            $user = User::create($request->all());
            Mail::to($user->email)->send(new VerifyMail($user));
            //? notification
            $this->successNotification($user, 403, "Sir : " . $user->name . " Verifiy Your Account look your email");
            return $this->returnSuccessMessage("Register Success", "R000");
        } catch (Exception $e) {
            return $this->returnError('500', $e->getMessage());
        }
    }


    // ?todo verify account users
    public function verify($id)
    {
        try {
            $user = User::find($id);
            $user->email_verified_at = now(); //? date now
            $user->save();
            // return $this->returnSuccessMessage("Verify Success", "V000");
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    // ?todo edit return info for user
    public function edit()
    {
        try {
            $user = auth()->user();  //? return info for this user
            return $this->returnData("user", $user);
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    // ?todo update info for user
    public function update(Request $request)
    {
        try {
            $validator = $this->validate($request, $this->rulesUpdateUsers());
            if ($validator !== true) {
                return $validator;
            }
            $user = User::findOrFail(auth()->user()->id);
            $user->update($request->all());
            return $this->returnSuccessMessage("Update Success", "U000");
        } catch (Exception $e) {
            return $this->returnError('500', "Server Error . , " . $e->getCode() . " , " . $e->getMessage());
        }
    }

    // ?todo return users image
    public function imagesuser(Request $request, $img)
    {
        try {
            $filename = $img;
            if (file_exists(public_path('images/users/' . $filename))) {
                return $this->returnimageusers($filename, 'images/users/');
            }
            //? img not found
            return $this->returnError('error', 'Image not found');
        } catch (Exception $ex) {
            return $this->returnError('error', $ex->getMessage());
        }
    }

    // ?todo change image of users 
    public function changeimg(Request $request)
    {
        try {
            //? Validate the request if needed
            $request->validate(['media' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',]);
            //? Get the user's media
            $user_media = Media::where('mediaable_type', 'User')->where('mediaable_id', auth()->user()->id)->first();
            //? Save the new image
            $path = $this->saveimage($request->file('media'), 'images/users/', );
            //? Update the media record
            $user_media->update(['media' => $path]);
            return $this->returnSuccessMessage('Image Changed Successfully .');
        } catch (Exception $ex) {
            return $this->returnError('error', $ex->getMessage());
        }
    }


    // ?todo logout in account
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        if (isset($token)) {
            try {
                // ?todo logout
                JWTAuth::setToken(str_replace('Bearer ', '', $token))->invalidate();
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




