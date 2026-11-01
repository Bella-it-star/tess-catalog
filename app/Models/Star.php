<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Star extends Model
{
    protected $fillable = ['tic_id'];

    public function planets(): HasMany
    {
        return $this->hasMany(ToiPlanet::class);
    }
}