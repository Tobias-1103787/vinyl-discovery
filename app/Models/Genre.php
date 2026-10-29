<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $fillable = ['name'];

    public function Vinyls(): BelongsToMany {
        return $this->belongsToMany(Vinyl::class);
    }
}
