<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'iso_code',
        'phone_code',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function divisionLevels(): HasMany
    {
        return $this->hasMany(DivisionLevel::class)->orderBy('depth');
    }

    public function administrativeDivisions(): HasMany
    {
        return $this->hasMany(AdministrativeDivision::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
