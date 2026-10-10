<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
    }

    #[OA\Post(
        path: '/auth/register',
        tags: ['Authentification'],
        summary: 'Créer un compte citoyen',
        description: "Crée un compte avec le rôle Citoyen par défaut. Le numéro de téléphone doit être unique.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'phone_number', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Ama'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Koffi'),
                    new OA\Property(property: 'email', type: 'string', nullable: true, example: 'ama.koffi@example.com'),
                    new OA\Property(property: 'phone_number', type: 'string', example: '90000000'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Compte créé',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Utilisateur inserer avec succes'),
                    new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                ])
            ),
            new OA\Response(response: 422, description: 'Validation échouée (ex. téléphone déjà utilisé)'),
        ]
    )]
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





    #[OA\Post(
        path: '/auth/login',
        tags: ['Authentification'],
        summary: 'Se connecter (téléphone + mot de passe)',
        description: "Retourne un jeton JWT à utiliser dans l'en-tête Authorization: Bearer {token}. Le compte doit avoir le statut 'validated' pour les rôles professionnels ; les citoyens sont validés par défaut.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['phone_number', 'password'],
                properties: [
                    new OA\Property(property: 'phone_number', type: 'string', example: '90000000'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Connexion réussie',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(property: 'message', type: 'string', example: 'connexion réussie'),
                    new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                    new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                    new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'),
                ])
            ),
            new OA\Response(response: 401, description: 'Téléphone ou mot de passe incorrect'),
            new OA\Response(response: 422, description: 'Validation échouée'),
        ]
    )]
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


        $credentials = $validateData->validated();
        $token = auth('api')->attempt($credentials);

        // Comptes créés avant le sélecteur de pays : numéro enregistré sans indicatif.
        if (!$token && ($localNumber = $this->stripCountryCode($credentials['phone_number']))) {
            $token = auth('api')->attempt(['phone_number' => $localNumber] + $credentials);
        }

        if (!$token) {

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


    /**
     * Retire l'indicatif d'un pays configuré (ex. +22890000000 -> 90000000),
     * ou null si le numéro ne commence par aucun indicatif connu.
     */
    private function stripCountryCode(string $phoneNumber): ?string
    {
        $phoneCode = Country::whereNotNull('phone_code')->pluck('phone_code')
            ->sortByDesc(fn ($code) => strlen($code))
            ->first(fn ($code) => str_starts_with($phoneNumber, $code));

        return $phoneCode ? substr($phoneNumber, strlen($phoneCode)) : null;
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



    #[OA\Post(
        path: '/auth/refresh',
        tags: ['Authentification'],
        summary: 'Rafraîchir le jeton JWT',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Nouveau jeton émis', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'access_token', type: 'string'),
            ])),
            new OA\Response(response: 401, description: 'Jeton expiré, invalide ou absent'),
        ]
    )]
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

    #[OA\Get(
        path: '/auth/me',
        tags: ['Authentification'],
        summary: 'Profil de l\'utilisateur connecté',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Utilisateur courant', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
            ])),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    // current user connected
    public function me()
    {
        return response()->json(['user'=>auth()->user()]);
    }



    #[OA\Put(
        path: '/auth/me',
        tags: ['Authentification'],
        summary: 'Modifier son profil',
        description: "Met à jour les informations de l'utilisateur connecté. Seuls les champs envoyés sont modifiés.",
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'first_name', type: 'string', example: 'Ama'),
                new OA\Property(property: 'last_name', type: 'string', example: 'Koffi'),
                new OA\Property(property: 'email', type: 'string', nullable: true, example: 'ama.koffi@example.com'),
                new OA\Property(property: 'phone_number', type: 'string', example: '90000000'),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Profil mis à jour', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
            ])),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 422, description: 'Validation échouée (ex. téléphone ou email déjà utilisé)'),
        ]
    )]
    public function updateMe(Request $request)
    {
        $user = auth('api')->user();

        $data = $request->validate([
            'first_name' => 'sometimes|required|string|min:2|max:30',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'nullable', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['sometimes', 'required', 'string', 'regex:/^\+\d{8,15}$/', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user->fresh(),
        ]);
    }

    #[OA\Put(
        path: '/auth/me/password',
        tags: ['Authentification'],
        summary: 'Changer son mot de passe',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['current_password', 'password', 'password_confirmation'], properties: [
                new OA\Property(property: 'current_password', type: 'string', format: 'password'),
                new OA\Property(property: 'password', type: 'string', format: 'password'),
                new OA\Property(property: 'password_confirmation', type: 'string', format: 'password'),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Mot de passe modifié'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 422, description: 'Mot de passe actuel incorrect ou confirmation invalide'),
        ]
    )]
    public function updatePassword(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|max:20|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect',
                'errors' => ['current_password' => ['Le mot de passe actuel est incorrect']],
            ], 422);
        }

        // Le cast 'hashed' du modèle User se charge du hachage.
        $user->update(['password' => $request->password]);

        return response()->json(['message' => 'Mot de passe modifié avec succès']);
    }

    #[OA\Post(
        path: '/auth/logout',
        tags: ['Authentification'],
        summary: 'Se déconnecter',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Déconnecté', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out'),
            ])),
        ]
    )]
    // logout user
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


}
