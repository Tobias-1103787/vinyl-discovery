<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vinyl extends Model
{
    protected $fillable = [
        'title', 'format', 'external_link', 'discogs_id',
        'cover_url', 'intact', 'release_date', 'description', 'created_by',
    ];

    protected static function booted(): void
    {
        static::updating(function (Vinyl $vinyl) {
            if ($vinyl->isDirty() && ! $vinyl->isDirty('intact')) {
                $vinyl->intact = false;
            }
        });
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class);
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
