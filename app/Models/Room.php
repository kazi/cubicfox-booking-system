<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class)->orderBy('day');
    }
}
