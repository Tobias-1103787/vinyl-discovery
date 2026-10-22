<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Label extends Model
{
    public function Vinyls(): HasMany {
        return $this->hasMany(Vinyl::class);
    }
}
