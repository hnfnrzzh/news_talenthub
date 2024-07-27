<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $regions = Wilayah ::latest()->paginate(5);
        return view('admin.regions.index', compact('regions'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Wilayah::create($request->all());
        return redirect()->route('admin.regions.index')->with('success', 'Region created successfully.');
    }

    public function show(Wilayah $region)
    {
        return view('admin.regions.show', compact('region'));
    }

    public function edit(Wilayah $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Wilayah $region)
    {
        $request->validate(['name' => 'required']);
        $region->update($request->all());
        return redirect()->route('admin.regions.index')->with('success', 'Region updated successfully.');
    }

    public function destroy(Wilayah $region)
    {
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Region deleted successfully.');
    }
}
