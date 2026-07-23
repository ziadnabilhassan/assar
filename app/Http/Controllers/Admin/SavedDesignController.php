<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavedDesign;
use Illuminate\Http\Request;

class SavedDesignController extends Controller
{
    public function index()
    {
        $savedDesigns = SavedDesign::with(['user', 'product', 'desgin'])->latest()->get();
        return view('admin.saved-designs.index', compact('savedDesigns'));
    }

    public function show($id)
    {
        $savedDesign = SavedDesign::with(['user', 'product', 'desgin'])->findOrFail($id);
        return view('admin.saved-designs.show', compact('savedDesign'));
    }

    public function destroy(Request $request, $id)
    {
        $savedDesign = SavedDesign::findOrFail($request->id);
        $savedDesign->delete();

        return back();
    }
}
