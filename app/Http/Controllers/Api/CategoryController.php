<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use Illuminate\Support\Facades\Storage; // Make sure to import Storage facade

class CategoryController extends Controller
{
    public function index()
    {
        // Retrieve categories with their content count
        $categories = Category::withCount('contents')->get();

        // Loop through categories to add image URL
        foreach ($categories as $category) {
            if ($category->image) {
                $category->image = Storage::url($category->image);
            }
        }

        // Return categories with the imageUrl
        return CategoryResource::collection($categories);
    }

    public function show(Category $category)
    {
        // Load related contents and count
        $category->load('contents');
        $category->loadCount('contents');

        // Generate the image URL
        if ($category->image) {
            $category->image = Storage::url($category->image);
        }

        // Return the category resource with the imageUrl
        return new CategoryResource($category);
    }

    public function store(Request $request)
    {
        // Validasi input gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Menyimpan gambar ke direktori public
        $path = $request->file('image')->store('category', 'public');

        // Membuat entry baru di tabel category
        $category = Category::create([
            'image' => $path,  // Menyimpan path file gambar
        ]);

        // Mengembalikan response dengan URL gambar
        return response()->json([
            'data' => $category,
            'image_url' => Storage::url($category->image),  // Menggunakan $category->image
        ]);
    }
}