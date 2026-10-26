<?php

namespace App\Services;
use Calliostro\Discogs\DiscogsClientFactory;
use Illuminate\Support\Facades\Cache;

class DiscogsService
{
    /**
     * Create a new class instance.
     */
    protected $client;
    public function __construct()
    {
        //
    }

    public function isVinyl($release): bool
    {
        return collect($release['formats'] ?? [])
            ->contains(fn ($f) => strcasecmp($f['name'] ?? '', 'Vinyl') === 0);
    }
}
