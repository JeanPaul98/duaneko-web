<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DivisionLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'depth',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function administrativeDivisions(): HasMany
    {
        return $this->hasMany(AdministrativeDivision::class);
    }
}
