<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        // 'slug'
    ];

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
    public function managers(): HasMany
    {
        return $this->hasMany(Manager::class);
    }

     /**
     * Get the agents for the company.
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

      /**
     * Get the zones for the company.
     */
    public function zones(): HasMany 
    {
        return $this->hasMany(Zone::class);
    }
}
