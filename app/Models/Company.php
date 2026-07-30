<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Company extends Model
{
    use HasSlug, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'logo',
        'description',
        'address',
        'city',
        'district',
        'phone_number',
        'email',
        'country_id',
        'administrative_division_id',
        // 'slug'
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function administrativeDivision(): BelongsTo
    {
        return $this->belongsTo(AdministrativeDivision::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

     /**
     * Get the managers for the company.
     */
    public function managers(): Builder
    {
        return User::role('manager')->where('company_id', $this->id);
    }

     /**
     * Get the agents for the company.
     */
    public function agents(): Builder
    {
        return User::role('agent')->where('company_id', $this->id);
    }

      /**
     * Get the zones for the company.
     */
    public function zones(): HasMany 
    {
        return $this->hasMany(Zone::class);
    }
}
