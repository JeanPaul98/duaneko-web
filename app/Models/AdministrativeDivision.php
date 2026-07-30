<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdministrativeDivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'division_level_id',
        'parent_id',
        'name',
        'code',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function divisionLevel(): BelongsTo
    {
        return $this->belongsTo(DivisionLevel::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AdministrativeDivision::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AdministrativeDivision::class, 'parent_id');
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }

    public function fullPath(): string
    {
        $names = [$this->name];
        $current = $this->parent;

        while ($current) {
            $names[] = $current->name;
            $current = $current->parent;
        }

        return implode(' > ', array_reverse($names));
    }
}
