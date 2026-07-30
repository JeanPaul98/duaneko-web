<?php

namespace App\Http\Controllers\Api\ResetPassword;

use App\Http\Controllers\Controller;
use App\Models\ResetCodePassword;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ResetPasswordController extends Controller
{
    #[OA\Post(
        path: '/password/reset',
        tags: ['Mot de passe'],
        summary: 'Réinitialiser le mot de passe',
        description: "À appeler après avoir vérifié le code via /password/code/check.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['code', 'password', 'password_confirmation'], properties: [
                new OA\Property(property: 'code', type: 'string', example: '482913'),
                new OA\Property(property: 'password', type: 'string', format: 'password', example: 'nouveauMotDePasse'),
                new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'nouveauMotDePasse'),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Mot de passe modifié', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string'),
            ])),
            new OA\Response(response: 422, description: 'Code invalide/expiré ou confirmation ne correspondant pas'),
        ]
    )]
    public function __invoke(Request $request)
    {
        $request->validate(
            [
             'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|confirmed',
           ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at->addHour()->isPast()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update(['password'=>bcrypt($request->password)]); 

        // delete current code 
        $passwordReset->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
