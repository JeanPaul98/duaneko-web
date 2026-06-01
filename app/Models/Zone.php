<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    use HasSlug, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'google_map_name',
        'northeast_latitude',
        'northeast_longitude',
        'southwest_latitude',
        'southwest_longitude',
        'color',
        'company_id'
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
     * Get the company that owns the manager.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    
}
