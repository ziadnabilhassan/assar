<?php

namespace App\Models\Concerns;

trait HasFullImageUrl
{
    public function getImageAttribute($value): ?string
    {
        if (! $value) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        $path = '/' . ltrim($value, '/');

        if (app()->bound('request')) {
            return request()->getSchemeAndHttpHost() . $path;
        }

        return url($path);
    }
}
