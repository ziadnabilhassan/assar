<?php

namespace App\Models;

use App\Models\Concerns\HasFullImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignSticker extends Model
{
    use HasFactory;
    use HasFullImageUrl;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
