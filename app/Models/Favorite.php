<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['vinyl_id', 'user_id'];

    public function Vinyls(): BelongsTo {
        return $this->belongsTo(Vinyl::class);
    }

    public function Users(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
