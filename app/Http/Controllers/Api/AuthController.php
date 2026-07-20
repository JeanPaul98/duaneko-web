<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
    }

    // sign up users
    public function register(StorePostRequest $request)
    {

        $user = User::create(
            array_merge(

                $request->validated(),
                [

                  'password' => bcrypt($request->password),

                ]
            )

        );
        return response()->json(
            [
                'message' => "Utilisateur inserer avec succes",
                'user' => $user,

            ],
            201
        );
    }





    // try connect user
    public function login(Request $request)
    {

        $validateData = Validator::make(
            $request->all(),
            [
                'phone_number' => 'required|string',
                'password' => 'required|min:6',
            ]
        );



        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 422);
        }


        if (!$token = auth('api')->attempt($validateData->validated())) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'phone_number ou password incorrect'
                ],
                401
            );
        }
        return $this->respondWithToken($token);
    }


    // create token for users
    protected  function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'connexion réussie',
            'user' => auth('api')->user(),
            'token_type' => 'bearer',
            'access_token' => $token,
            // 'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }



    // refresh token
    public function refresh()
    {
        try {
            $newToken = auth('api')->refresh();
            return $this->respondWithToken($newToken);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['success' => false, 'message' => 'Token has expired and cannot be refreshed'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['success' => false, 'message' => 'Token is invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Token is absent'], 401);
        }
    }

    // current user connected
    public function me()
    {
        return response()->json(['user'=>auth()->user()]);
    }



    // logout user
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


}
