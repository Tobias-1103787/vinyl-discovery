<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artist extends Model
{
    public function Vinyls(): BelongsToMany {
        return $this->belongsToMany(Vinyl::class);
    }
}
