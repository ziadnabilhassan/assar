<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use HasTranslations;
    public $translatable = ['name', 'description'];
    protected $guarded = [];

    public function variants()
    {
        return $this->hasMany(Variant::class)->oldest();
    }
    public function oneVariant()
    {
        return $this->hasOne(Variant::class)->oldest();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function oneImage()
    {
        return $this->hasOne(ProductImage::class);
    }

    public function scopeFilter($query, $request)
    {
        return $query
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where("name->en", 'like', "%" . $request->search . "%")
                        ->orWhere("name->ar", 'like', "%" . $request->search . "%");
                });
            })
            ->when($request->filled('categories'), function ($query) use ($request) {
                return $query->whereIn('category_id', $request->categories);
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                return $query->where('category_id', $request->category);
            })
            ->when($request->filled('min') && $request->filled('max'), function ($query) use ($request) {
                return $query->whereHas('variants', function ($priceQuery) use ($request) {
                    $priceQuery->whereBetween('price', [$request->min, $request->max]);
                });
            })
            ->when($request->filled('colors'), function ($query) use ($request) {
                return $query->whereHas('variants', function ($colorQuery) use ($request) {
                    $colorQuery->whereIn('color_id', $request->colors);
                });
            })
            ->when($request->filled('stock'), function ($query) {
                return $query->whereHas('variants', function ($stockQuery) {
                    $stockQuery->where('quantity', '>', 0);
                });
            })
            ->when($request->filled('discount'), function ($query) {
                return $query->whereHas('variants', function ($discountQuery) {
                    $discountQuery->whereColumn('old_price', '>', 'price');
                });
            });
    }
}