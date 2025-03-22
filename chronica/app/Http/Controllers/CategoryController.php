<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Tag;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }
    public function show($slug)
    {
        // Récupérer la catégorie par son slug
        $category = Category::where('slug', $slug)->firstOrFail();
        $categorys = Category::all();
        // Récupérer les articles en vedette de la catégorie
        $featuredArticles = Article::where('category_id', $category->id)
            ->where('is_featured', true) // Si vous avez une colonne pour savoir si l'article est en vedette
            ->paginate(6); // Nombre d'articles à afficher par page

        // Récupérer tous les tags pour l'affichage des tags
        $tags = Tag::all();

        return view('category.show', compact('category','categorys', 'featuredArticles', 'tags'));
    }
}
