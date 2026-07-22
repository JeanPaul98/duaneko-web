<?php

namespace App\Http\Controllers\Api\ResetPassword;

use App\Http\Controllers\Controller;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CodeCheckController extends Controller
{
    #[OA\Post(
        path: '/password/code/check',
        tags: ['Mot de passe'],
        summary: 'Vérifier le code de réinitialisation',
        description: "À appeler avant /password/reset pour valider le code reçu par email (expire après une heure).",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['code'], properties: [
                new OA\Property(property: 'code', type: 'string', example: '482913'),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Code valide', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'code', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ])),
            new OA\Response(response: 422, description: 'Code invalide ou expiré'),
        ]
    )]
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at->addHour()->isPast()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        return response([
            'code' => $passwordReset->code,
            'message' => trans('passwords.code_is_valid')
        ], 200);
    }
}
