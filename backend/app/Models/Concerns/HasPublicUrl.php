<?php

namespace App\Models\Concerns;

trait HasPublicUrl
{
    protected function publicUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return asset('storage/'.ltrim($path, '/'));
    }
}
