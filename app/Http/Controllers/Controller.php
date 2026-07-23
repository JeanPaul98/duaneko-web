<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Duneko API',
    description: "API mobile de Duneko / EcoLink Africa — signalements citoyens, authentification et réinitialisation de mot de passe.\n\nDestinée à l'équipe mobile (app citoyenne Flutter)."
)]
#[OA\Server(url: '/api', description: 'Serveur courant')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: "Jeton JWT obtenu via /auth/login. À envoyer dans l'en-tête Authorization: Bearer {token}."
)]
#[OA\Tag(name: 'Authentification', description: 'Inscription, connexion, session du citoyen')]
#[OA\Tag(name: 'Signalements', description: 'Créer et consulter les signalements de propreté urbaine')]
#[OA\Tag(name: 'Mot de passe', description: 'Réinitialisation du mot de passe par code envoyé par email')]
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
