<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input query
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');

        // Cari konten berdasarkan nama, deskripsi, atau kategori
        $contents = Content::with(['category', 'photos', 'tutorials']) // Eager load category, photos, tutorials
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->get();

        // Formatkan data untuk sesuai dengan struktur yang diinginkan
        $formattedContents = $contents->map(function ($content) {
            return [
                'id' => $content->id,
                'name' => $content->name,
                'thumbnail' => $content->thumbnail ? "/storage/" . $content->thumbnail : null, // Path relatif thumbnail
                'url_video' => $content->url_video,  // Sesuaikan jika perlu
                'category_id' => $content->category_id,
                'description' => $content->description,
                'slug' => $content->slug,
                'deleted_at' => $content->deleted_at,
                'created_at' => $content->created_at->toIso8601String(),
                'updated_at' => $content->updated_at->toIso8601String(),
                'category' => [
                    'id' => $content->category->id,
                    'name' => $content->category->name,
                    'slug' => $content->category->slug,
                    'image' => $content->category->image ? "/storage/" . $content->category->image : null, // Path relatif kategori
                    'deleted_at' => $content->category->deleted_at,
                    'created_at' => $content->category->created_at->toIso8601String(),
                    'updated_at' => $content->category->updated_at->toIso8601String(),
                ],
                'photos' => $content->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'photo' => $photo->photo ? "/storage/" . $photo->photo : null, // Path relatif foto
                        'content_id' => $photo->content_id,
                        'deleted_at' => $photo->deleted_at,
                        'created_at' => $photo->created_at->toIso8601String(),
                        'updated_at' => $photo->updated_at->toIso8601String(),
                    ];
                }),
                'tutorials' => $content->tutorials->map(function ($tutorial) {
                    return [
                        'id' => $tutorial->id,
                        'name' => $tutorial->name,
                        'content_id' => $tutorial->content_id,
                        'deleted_at' => $tutorial->deleted_at,
                        'created_at' => $tutorial->created_at->toIso8601String(),
                        'updated_at' => $tutorial->updated_at->toIso8601String(),
                    ];
                }),
            ];
        });

        // Return hasil pencarian dalam format 'data' yang berisi objek dalam array
        return response()->json([
            'data' => $formattedContents
        ]);
    }
}