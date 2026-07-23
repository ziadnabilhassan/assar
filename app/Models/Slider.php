<?php

namespace App\Models;

use App\Models\Concerns\HasFullImageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasFullImageUrl;
    public $translatable = ['title','text'];
    protected $guarded = [];
}
