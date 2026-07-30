<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReportStoreRequest;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use OpenApi\Attributes as OA;



class ReportController extends Controller
{
#[OA\Post(
    path: '/auth/reports/{user_id}',
    tags: ['Signalements'],
    summary: 'Créer un signalement (utilisateur connecté)',
    description: "Le citoyen doit être authentifié (Bearer). Le paramètre {user_id} dans l'URL n'est pas utilisé par le serveur : l'auteur du signalement est déterminé à partir du jeton JWT, pas du paramètre — indiquer l'ID de l'utilisateur connecté par convention.",
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(mediaType: 'multipart/form-data', schema: new OA\Schema(
            required: ['image'],
            properties: [
                new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'jpg, png, jpeg, gif ou svg — 2 Mo max'),
                new OA\Property(property: 'type', type: 'string', nullable: true, example: 'wild_dumps'),
                new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: 6.1319),
                new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: 1.2228),
                new OA\Property(property: 'description', type: 'string', nullable: true, example: "Dépôt d'ordures près du marché"),
            ]
        ))
    ),
    parameters: [
        new OA\Parameter(name: 'user_id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), description: 'Non utilisé côté serveur (voir description)'),
    ],
    responses: [
        new OA\Response(response: 201, description: 'Signalement créé', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'message', type: 'string', example: 'signalement inserer avec succes'),
            new OA\Property(property: 'report', ref: '#/components/schemas/Report'),
        ])),
        new OA\Response(response: 401, description: 'Non authentifié'),
        new OA\Response(response: 422, description: 'Validation échouée (photo manquante ou invalide)'),
    ]
)]
// TODO: Ici c'est l'api pour l'utilisateur qui est connecter.
public function reportStore(ReportStoreRequest $request)
{
    $file = $request->file('image');
    $imageName =  time().".".$file->getClientOriginalExtension(); 
   
    $report=Report::create(
        array_merge(
            $request->validated() + ['user_id'=>auth('api')->id()],
            [
                'image' => $imageName,
                ]
            ),
                
                
    );
      // Save Image in Storage folder
      Storage::disk('public')->put($imageName, file_get_contents($request->image));

   
    return response()->json(
        [
            'message' => "signalement inserer avec succes",
            'report'=>$report
        ],
        201
    );

}

#[OA\Post(
    path: '/auth/reports/public',
    tags: ['Signalements'],
    summary: 'Créer un signalement anonyme',
    description: "Aucune authentification requise — utilisé quand le citoyen choisit de signaler sans compte ou en mode anonyme. Le signalement créé n'a pas de user_id.",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(mediaType: 'multipart/form-data', schema: new OA\Schema(
            required: ['image'],
            properties: [
                new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'jpg, png, jpeg, gif ou svg — 2 Mo max'),
                new OA\Property(property: 'type', type: 'string', nullable: true, example: 'wild_dumps'),
                new OA\Property(property: 'latitude', type: 'number', format: 'float', nullable: true, example: 6.1319),
                new OA\Property(property: 'longitude', type: 'number', format: 'float', nullable: true, example: 1.2228),
                new OA\Property(property: 'description', type: 'string', nullable: true, example: "Dépôt d'ordures près du marché"),
            ]
        ))
    ),
    responses: [
        new OA\Response(response: 201, description: 'Signalement créé', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'message', type: 'string', example: 'signalement inserer avec succes'),
            new OA\Property(property: 'report', ref: '#/components/schemas/Report'),
        ])),
        new OA\Response(response: 422, description: 'Validation échouée (photo manquante ou invalide)'),
    ]
)]
// TODO: Ici c'est l'api pour l'utilisateur qui n'est pas connecter.
public function reportPublicStore(ReportStoreRequest $request)
{
    $file = $request->file('image');
    $imageName =  time().".".$file->getClientOriginalExtension(); 

     
    $report=Report::create(
        array_merge(
        $request->validated() ,
           [
                'image' =>   $imageName,
           ]
           )
    );
        // Save Image in Storage folder
        Storage::disk('public')->put($imageName, file_get_contents($request->image));
    return response()->json(
        [
            'message' => "signalement inserer avec succes",
            'report'=>$report
        ],
        201
    );

}

#[OA\Get(
    path: '/auth/reports/all',
    tags: ['Signalements'],
    summary: 'Lister les signalements (paginé)',
    description: "Retourne les signalements les plus récents d'abord, paginés. Utiliser 'page' pour naviguer et 'per_page' pour ajuster la taille de page (15 par défaut, 50 maximum).",
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15, maximum: 50)),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Page de signalements', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'reports', properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Report')),
                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                new OA\Property(property: 'last_page', type: 'integer', example: 5),
                new OA\Property(property: 'per_page', type: 'integer', example: 15),
                new OA\Property(property: 'total', type: 'integer', example: 62),
            ], type: 'object'),
        ])),
    ]
)]
public function showReports(Request $request)
{
    $perPage = max(1, min((int) $request->query('per_page', 15), 50));
    $reports = Report::latest()->paginate($perPage);

    return response()->json(
        [
            'reports'=>$reports
        ],200
    );

}
#[OA\Get(
    path: '/image/{filename}',
    tags: ['Signalements'],
    summary: "Récupérer l'image d'un signalement",
    description: 'Endpoint public (pas de Bearer requis) qui sert le fichier stocké lors de la création du signalement.',
    parameters: [
        new OA\Parameter(name: 'filename', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: '1721234567.jpg'),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Fichier image', content: new OA\MediaType(mediaType: 'image/*', schema: new OA\Schema(type: 'string', format: 'binary'))),
        new OA\Response(response: 404, description: 'Fichier introuvable'),
    ]
)]
public function getImage($filename)
{
 $path = storage_path('app/public/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404);
}

#[OA\Get(
    path: '/auth/users/all',
    tags: ['Authentification'],
    summary: 'Lister tous les utilisateurs',
    description: "Retourne l'ensemble des utilisateurs de la plateforme (tous rôles confondus), sans filtre ni pagination.",
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(response: 200, description: 'Liste des utilisateurs', content: new OA\JsonContent(properties: [
            new OA\Property(property: 'users', type: 'array', items: new OA\Items(ref: '#/components/schemas/User')),
        ])),
    ]
)]
public function showUsers()
{
    $users=User::all();
    return response()->json(
        [
            'users'=> $users
        ],200
    );

}

}
