<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategori::latest()->paginate(5);
        return view('admin.categories.index', compact('categories'))->with('i', ($request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Kategori::create($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Kategori $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Kategori $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Kategori $category)
    {
        $request->validate(['name' => 'required']);
        $category->update($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Kategori $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }
}
