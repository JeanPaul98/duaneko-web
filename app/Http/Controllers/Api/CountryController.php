<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use OpenApi\Attributes as OA;

class CountryController extends Controller
{
    #[OA\Get(
        path: '/countries',
        tags: ['Pays'],
        summary: 'Lister les pays actifs et leur indicatif téléphonique',
        description: "Endpoint public (utilisé avant connexion par les formulaires d'inscription et de connexion). Ne renvoie que les pays actifs ayant un indicatif, configurés dans Paramètres > Pays.",
        responses: [
            new OA\Response(response: 200, description: 'Liste des pays', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'countries', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Togo'),
                    new OA\Property(property: 'iso_code', type: 'string', example: 'TG'),
                    new OA\Property(property: 'phone_code', type: 'string', example: '+228'),
                ])),
            ])),
        ]
    )]
    public function index()
    {
        $countries = Country::where('is_active', true)
            ->whereNotNull('phone_code')
            ->orderBy('name')
            ->get(['name', 'iso_code', 'phone_code']);

        return response()->json(['countries' => $countries]);
    }
}
