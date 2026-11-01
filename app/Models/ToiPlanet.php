<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToiPlanet extends Model
{
    protected $fillable = [
        'star_id', 'toi_number', 'master_priority', 
        'sg1b_priority', 'tess_notes', 'personal_notes'
    ];

    public function star(): BelongsTo
    {
        return $this->belongsTo(Star::class);
    }
}