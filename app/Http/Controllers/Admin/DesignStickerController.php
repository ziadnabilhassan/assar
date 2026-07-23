<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\DesignSticker;
use Illuminate\Http\Request;

class DesignStickerController extends Controller
{
    public function index()
    {
        $stickers = DesignSticker::orderBy('sort_order')->orderByDesc('id')->get();
        return view('admin.design-stickers.index', compact('stickers'));
    }

    public function create()
    {
        return view('admin.design-stickers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
            'category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['image'] = Helper::storeImage($request->file('image'), 'design-stickers');
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        DesignSticker::create($data);

        return redirect()->route('design-stickers.index');
    }

    public function edit($id)
    {
        $sticker = DesignSticker::findOrFail($id);
        return view('admin.design-stickers.edit', compact('sticker'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
            'category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        unset($data['image']);

        $sticker = DesignSticker::findOrFail($id);

        if ($request->hasFile('image')) {
            $data['image'] = Helper::updateImage($request->file('image'), $sticker->image, 'design-stickers');
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        $sticker->update($data);

        return redirect()->route('design-stickers.index');
    }

    public function destroy(Request $request, $id)
    {
        $sticker = DesignSticker::findOrFail($request->id);
        Helper::deleteImage($sticker->image);
        $sticker->delete();

        return back();
    }
}
