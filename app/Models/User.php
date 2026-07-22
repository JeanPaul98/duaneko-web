<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Attributes as OA;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[OA\Schema(
    schema: 'User',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 12),
        new OA\Property(property: 'first_name', type: 'string', example: 'Ama'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Koffi'),
        new OA\Property(property: 'email', type: 'string', nullable: true, example: 'ama.koffi@example.com'),
        new OA\Property(property: 'phone_number', type: 'string', example: '90000000'),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'validated', 'rejected'], example: 'validated'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
    ]
)]
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->getRoleNames()->first(),
        ];
    }

    public function isValidated(): bool
    {
        return $this->status === 'validated';
    }

    public function full_name()
    {
        return "$this->first_name $this->last_name";
    }

    /**
     * Get the company this staff member (manager/agent) belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the ramassages this agent is assigned to.
     */
    public function ramassages(): HasMany
    {
        return $this->hasMany(Ramassage::class, 'agent_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password',
        'company_id',
        'status',
        'date_of_birth',
        'gender',
        'address',
        'city',
        'district',
        'id_document_type',
        'id_document_number',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];
}
