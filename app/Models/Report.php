<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
   
    
}
