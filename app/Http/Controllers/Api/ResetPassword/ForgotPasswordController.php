<?php

namespace App\Http\Controllers\Api\ResetPassword;

use App\Http\Controllers\Controller;
use App\Mail\SendCodeResetPassword;
use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

class ForgotPasswordController extends Controller
{
    #[OA\Post(
        path: '/password/email',
        tags: ['Mot de passe'],
        summary: 'Demander un code de réinitialisation',
        description: "Envoie par email un code à 6 chiffres, valable une heure. Tout code précédent pour cet email est invalidé.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['email'], properties: [
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ama.koffi@example.com'),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Code envoyé', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string'),
            ])),
            new OA\Response(response: 422, description: "Email inconnu ou invalide"),
        ]
    )]
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return response([
            'success'=>true,
            'message' => trans('passwords.sent')], 200);
    }
}
