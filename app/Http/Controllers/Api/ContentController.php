<?php

namespace App\Http\Controllers\Api;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ContentResource;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    // Index method to retrieve all contents with accessible image URLs
    // Method index untuk mengambil semua konten dengan URL gambar yang dapat diakses
public function index()
{
    // Ambil semua konten beserta kategori, foto, dan tutorial terkait
    $contents = Content::with(['category', 'photos', 'tutorials'])->get();

    foreach ($contents as $content) {
        // Cek apakah thumbnail ada, lalu buat URL
        if ($content->thumbnail) {
            $content->thumbnail = Storage::url($content->thumbnail); // Menggunakan 'public' disk
        }

        // Loop melalui foto dan tambahkan URL jika ada
        foreach ($content->photos as $photo) {
            if ($photo->photo) {
                $photo->photo = Storage::url($photo->photo); // Menggunakan 'public' disk
            }
        }

        // Cek apakah kategori memiliki gambar dan buat URL
        if ($content->category && $content->category->image) {
            $content->category->image = Storage::url($content->category->image); // Menggunakan 'public' disk
        }
    }

    return response()->json([
        'data' => $contents
    ]);
}

// Method untuk mengambil konten berdasarkan kategori (nama atau ID)
public function getContentsByCategory($category)
{
    $contents = Content::with(['category', 'photos', 'tutorials'])
        ->whereHas('category', function ($query) use ($category) {
            $query->where('name', 'LIKE', "%{$category}%");
        })
        ->orWhereHas('category', function ($query) use ($category) {
            $query->where('id', $category);
        })
        ->get();

    // Loop untuk menghasilkan URL yang sesuai untuk thumbnail dan foto
    foreach ($contents as $content) {
        if ($content->thumbnail) {
            $content->thumbnail = Storage::url($content->thumbnail); // Menggunakan 'public' disk
        }

        foreach ($content->photos as $photo) {
            if ($photo->photo) {
                $photo->photo = Storage::url($photo->photo); // Menggunakan 'public' disk
            }
        }

        if ($content->category && $content->category->image) {
            $content->category->image = Storage::url($content->category->image); // Menggunakan 'public' disk
        }
    }

    return response()->json([
        'data' => $contents,
    ]);
    } 
}