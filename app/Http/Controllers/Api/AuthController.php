<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    private const ERROR_MESSAGE_VALIDATION_ERROR = 'Validation error';
    private const ERROR_MESSAGE_BAD_CREDENTIALS = 'Unable to log you in due to bad credentials.';
    private const SUCCESS_MESSAGE_AFTER_LOGIN = 'Sucessfully logged in.';

    public function login(LoginRequest $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => self::ERROR_MESSAGE_VALIDATION_ERROR,
                        'errors' => $validateUser->errors()
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => self::ERROR_MESSAGE_BAD_CREDENTIALS,
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $user = User::where('email', $request->email)->first();

            return response()->json(
                [
                    'status' => true,
                    'message' => self::SUCCESS_MESSAGE_AFTER_LOGIN,
                    'token' => $user->createToken("api-token")->plainTextToken
                ],
                Response::HTTP_OK
            );

        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $throwable->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

}
