<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_failed'), $validator->errors());
        }
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $accessToken = $user->createToken('authToken')->accessToken;
        return $this->sendResponse(
            [
                "user" => $user,
                'access_token' => $accessToken,
            ],
            __('auth.register_success')
        );
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,])) {
            $success['token'] =  Auth::user()->createToken('MyApp')->accessToken;
            $success['user'] =  Auth::user();
            return $this->sendResponse($success, __('auth.login_success'));
        } else {
            return $this->sendError(__('auth.failed'), ['error' => __('auth.failed')]);
        }
    }
    public function logout(Request $request)
    {
    }
}
