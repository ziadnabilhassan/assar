<?php

namespace App\Models;

use App\Models\Concerns\HasFullImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    use HasFullImageUrl;
    protected $guarded = [];
}
