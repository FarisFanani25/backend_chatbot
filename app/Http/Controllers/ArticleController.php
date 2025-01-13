<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Get all articles
    public function index()
{
    $articles = Article::all();

    
    // Tambahkan alias untuk id jika frontend menggunakan id_artikel
    $articles->map(function ($article) {
        $article->id_artikel = $article->id; // Alias untuk 'id'
        return $article;
    });

    return response()->json($articles);
}

    // Get a specific article by ID
    public function show($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }


    
    // Create a new article
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'role' => 'required|in:berita,jurnal,majalah',
            'author' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $data = $request->all();

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
            $data['image'] = $imagePath;
        }
    
        $article = Article::create($data);
    
        return response()->json(['message' => 'Article created successfully', 'article' => $article], 201);
    }


   // Update an existing article
   public function update(Request $request, $id)
{
    try {
        // Cek apakah artikel dengan ID tersebut ada
        $article = Article::find($id);

        if (!$article) {
            \Log::warning('Article not found', ['article_id' => $id]);
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Logging untuk debugging
        \Log::info('Request content type:', ['Content-Type' => $request->header('Content-Type')]);
        \Log::info('Request payload:', $request->all());

        // Validasi input
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'role' => 'nullable|in:berita,jurnal,majalah',
            'author' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \Log::info('Validated data:', $validatedData);

        // Ambil data dari request
        $data = $request->only(['title', 'content', 'role', 'author']);

        // Proses upload gambar jika ada
        if ($request->hasFile('image')) {
            \Log::info('Image file detected:', ['file' => $request->file('image')]);

            // Hapus gambar lama jika ada
            if ($article->image) {
                \Storage::disk('public')->delete($article->image);
                \Log::info('Old image deleted:', ['image' => $article->image]);
            }

            // Upload gambar baru
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path;
            \Log::info('New image uploaded:', ['path' => $path]);
        }

        // Update artikel
        $article->update($data);

        // Log data artikel yang telah diperbarui
        \Log::info('Updated article:', $article->toArray());

        // Return respon JSON
        return response()->json([
            'message' => 'Article updated successfully',
            'input_data' => $data,
            'updated_article' => $article,
        ]);
    } catch (\Exception $e) {
        // Log error secara detail
        \Log::error('Error during article update:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Return respon error 500
        return response()->json([
            'message' => 'An unexpected error occurred',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    
    // Delete an article
    // app/Http/Controllers/ArticleController.php

public function destroy($id)
{
    $article = Article::find($id);

    if (!$article) {
        return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
    }

    // Hapus gambar jika ada
    if ($article->image) {
        \Storage::disk('public')->delete($article->image);
    }

    // Hapus artikel
    $article->delete();

    return response()->json(['message' => 'Artikel berhasil dihapus']);
}


}
