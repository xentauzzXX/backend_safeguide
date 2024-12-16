<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        // Mengambil semua data banner
        $banners = Banner::all();

        // Mengubah URL gambar agar bisa diakses
        $banners = $banners->map(function ($banner) {
            $banner->photo = Storage::url($banner->photo); // Menambahkan URL lengkap untuk gambar
            return $banner;
        });

        return response()->json([
            'data' => $banners
        ]);
    }

    public function show($id)
    {
        // Mencari banner berdasarkan ID
        $banner = Banner::find($id);

        // Jika banner tidak ditemukan, kembalikan error 404
        if (!$banner) {
            return response()->json([
                'error' => 'Banner not found'
            ], 404);
        }

        // Mengubah URL gambar agar bisa diakses
        $banner->photo = Storage::url($banner->photo); // Menambahkan URL lengkap untuk gambar

        // Jika ditemukan, kembalikan banner dalam objek 'data'
        return response()->json([
            'data' => $banner
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input gambar
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Menyimpan gambar ke direktori public
        $path = $request->file('photo')->store('banners', 'public');

        // Membuat entry baru di tabel banner
        $banner = Banner::create([
            'photo' => $path,
        ]);

        // Mengembalikan response dengan URL gambar
        return response()->json([
            'data' => $banner,
            'image_url' => Storage::url($banner->photo),
        ]);
    }
}