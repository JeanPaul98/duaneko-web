<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Report',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 42),
        new OA\Property(property: 'user_id', type: 'integer', nullable: true, example: 12, description: 'Absent pour un signalement anonyme'),
        new OA\Property(property: 'type', type: 'string', example: 'wild_dumps', description: 'Catégorie du signalement'),
        new OA\Property(property: 'description', type: 'string', example: "Dépôt d'ordures près du marché"),
        new OA\Property(property: 'latitude', type: 'number', format: 'float', example: 6.1319),
        new OA\Property(property: 'longitude', type: 'number', format: 'float', example: 1.2228),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'in_progress', 'done'], example: 'pending'),
        new OA\Property(property: 'image', type: 'string', example: '1721234567.jpg', description: 'Nom de fichier, à récupérer via GET /image/{filename}'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ]
)]
class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'image',
        'type',
        'latitude',
        'longitude',
        'status',
        'description',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
