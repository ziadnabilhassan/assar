<?php

namespace App\Models;

use App\Models\Concerns\HasFullImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedDesign extends Model
{
    use HasFactory;
    use HasFullImageUrl;

    protected $guarded = [];

    protected $casts = [
        'design_data' => 'array',
        'sticker_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function desgin()
    {
        return $this->belongsTo(Desgin::class);
    }

    public function getPreviewImageAttribute($value): ?string
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

    public function getPreviewImageUrlAttribute($value): ?string
    {
        if ($value) {
            return $value;
        }

        return $this->preview_image;
    }
}
