<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helper
{
    public static function storeImage($image, $path)
    {
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $path = 'images/' . $path;
        $storedPath = $image->storeAs($path, $fileName, 'public');
        return Storage::url($storedPath);
    }

    public static function updateImage($image, $oldImage, $path)
    {
        self::deleteImage($oldImage);
        return self::storeImage($image, $path);
    }

    public static function deleteImage($path)
    {
        $relativePath = str_replace('/storage/', '', $path);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    public static function link($route, $id, $title)
    {
        $slug = preg_replace('/[^\p{Arabic}A-Za-z0-9-]+/u', '-', $title);
        return route($route, ['id' => $id, 'slug' => $slug]);
    }

    public static function limit($text, $num = 200)
    {
        $text = strip_tags($text); // Remove all HTML tags
        return Str::limit($text, $num);
        // return Str::limit(preg_replace('/<[^>]+>/', ' ', $text), $num);
    }
}
