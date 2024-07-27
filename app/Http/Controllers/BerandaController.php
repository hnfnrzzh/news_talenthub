<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Post;
use App\Models\Kategori;

use App\Models\Wilayah;

class BerandaController extends Controller
{
    public function index()
    {
        $news = Post::with(['category', 'region'])->latest()->get();

        // Get categories by name
        $edukasiCategory = Kategori::where('name', 'edukasi')->first();
        $komunitasCategory = Kategori::where('name', 'komunitas')->first();
        $opiniCategory = Kategori::where('name', 'opini')->first();

        // Get news based on the categories
        $editorChoiceMain = Post::where('category_id', $edukasiCategory->id)->latest()->first();
        $editorChoiceNews = Post::where('category_id', $edukasiCategory->id)->latest()->take(5)->get();

        $komunitasMain = Post::where('category_id', $komunitasCategory->id)->latest()->first();
        $komunitasNews = Post::where('category_id', $komunitasCategory->id)->latest()->take(5)->get();

        $opiniMain = Post::where('category_id', $opiniCategory->id)->latest()->first();
        $opiniNews = Post::where('category_id', $opiniCategory->id)->latest()->take(5)->get();

        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $news->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedNews = new LengthAwarePaginator($currentItems, $news->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $popularNews = Post::with('category')->orderBy('views', 'desc')->take(5)->get();
        $categories = Kategori::all();
        $regions = Wilayah::all();

        return view('user.home', compact('news', 'paginatedNews', 'popularNews', 'categories', 'regions', 'editorChoiceMain', 'editorChoiceNews', 'komunitasMain', 'komunitasNews', 'opiniMain', 'opiniNews'));
    }

    public function category($category)
    {
        $categoryModel = Kategori::where('name', $category)->firstOrFail();
        $paginatedNews = Post::where('category_id', $categoryModel->id)->latest()->paginate(6);
        
        $popularNews =Post::with('category')->orderBy('views', 'desc')->take(5)->get();
        $categories = Kategori::all();
        $regions = Wilayah::all();
    
        return view('user.category', compact('category', 'paginatedNews', 'popularNews', 'categories', 'regions'));
    }
    

    public function region($region)
    {
        $regionModel = Wilayah::where('name', $region)->firstOrFail();
        $news = Post::where('region_id', $regionModel->id)->latest()->paginate(6);
        
        $popularNews = Post::with('category')->orderBy('views', 'desc')->take(5)->get();
        $categories = Kategori::all();
        $regions = Wilayah::all();

        return view('user.region', compact('region', 'news', 'popularNews', 'categories', 'regions'));
    }

    public function detail($id)
    {
        $newsItem = Post::with(['category', 'region'])->findOrFail($id);
        $popularNews = Post::with('category')->orderBy('views', 'desc')->take(5)->get();
        $categories = Kategori::all();
        $regions = Wilayah::all();
        $relatedNews = Post::where('category_id', $newsItem->category_id)->where('id', '!=', $id)->take(3)->get();

        return view('user.detail', compact('newsItem', 'popularNews', 'categories', 'regions', 'relatedNews'));
    }
}