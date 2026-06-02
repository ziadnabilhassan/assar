<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\Models\Desgin;
use Illuminate\Http\Request;

class DesginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $desgins = Desgin::get();
        return view('admin.desgins.index', compact('desgins'));
    }

    public function create()
    {
        return view('admin.desgins.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'color' => 'required|string',
            'width' => 'required|string',
            'height' => 'required|string',
                        'image' => 'required|image',

        ]);
          $fileName = Helper::storeImage($request->file('image'), 'desgins');
        $data['image'] = $fileName;
        Desgin::create($data);
        return redirect()->route('desgins.index');

    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $desgin = Desgin::findOrFail($id);
        return view('admin.desgins.edit', compact('desgin'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
                  'name' => 'string',
            'color' => 'string',
            'width' => 'string',
            'height' => 'string',
                        'image' => 'image',

        ]);
          unset($data['image']);
         $desgin = Desgin::findOrFail($id);
        if ($request->image) {
            $fileName = Helper::updateImage($request->file('image'), $desgin->image, 'desgins');
            $data['image'] = $fileName;
        }
        $desgin->update($data);
        return redirect()->route('desgins.index');
    }

    public function destroy(Request $request, $id)
    {
        $desgin = Desgin::findOrFail($request->id);
        $desgin->delete();
        return back();
    }
}
