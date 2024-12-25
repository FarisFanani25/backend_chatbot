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
    // Cari artikel berdasarkan ID
    $article = Article::find($id);

    if (!$article) {
        return response()->json(['message' => 'Article not found'], 404);
    }

    // Validasi data
    $request->validate([
        'title' => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'role' => 'nullable|in:berita,jurnal,majalah',
        'author' => 'nullable|string|max:100',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Proses data
    $data = $request->except(['image', '_method']); // Hilangkan `_method` untuk menghindari konflik

    // Jika ada file gambar, proses upload
    if ($request->hasFile('image')) {
        // Hapus gambar lama jika ada
        if ($article->image) {
            \Storage::disk('public')->delete($article->image);
        }

        $imagePath = $request->file('image')->store('articles', 'public');
        $data['image'] = $imagePath;
    }

    // Update artikel
    $article->update($data);

    return response()->json([
        'message' => 'Article updated successfully',
        'article' => $article
    ]);
}
    

    // Delete an article
    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Hapus gambar jika ada
        if ($article->image) {
            \Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully']);
    }
}
