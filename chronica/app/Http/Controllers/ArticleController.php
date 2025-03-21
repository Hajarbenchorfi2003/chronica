<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Afficher tous les articles
    public function index()
    {
        $articles = Article::with('category', 'tags', 'comments')->get();
        return view('articles.index', compact('articles'));
    }

    // Afficher un article spécifique
    public function show($id)
    {
        $article = Article::with('category', 'tags', 'comments')->findOrFail($id);
        return view('articles.show', compact('article'));
    }

    // Formulaire pour créer un nouvel article
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('articles.create', compact('categories', 'tags'));
    }

    // Enregistrer un nouvel article
    public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|unique:articles',
        'content' => 'required|string',
        'excerpt' => 'nullable|string',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'category_id' => 'required|exists:categories,id',
        'status' => 'required|in:draft,published',
        'tags' => 'array|exists:tags,id',
    ]);

    // Si une image est envoyée, on la stocke dans le dossier public
    if ($request->hasFile('cover_image')) {
        $data['cover_image'] = $request->file('cover_image')->store('public/images');
    }

    $article = Article::create($data);
    if ($request->has('tags')) {
        $article->tags()->sync($request->tags);
    }

    return redirect()->route('articles.index');
}


    // Formulaire pour éditer un article existant
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        return view('articles.edit', compact('article', 'categories', 'tags'));
    }

    // Mettre à jour un article existant
    public function update(Request $request, Article $article)
    {
        // Validation des données
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles,slug,' . $article->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'tags' => 'array|exists:tags,id',
        ]);
    
        // Si une nouvelle image est téléchargée
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->cover_image) {
                Storage::delete($article->cover_image);
            }
    
            // Stocker la nouvelle image
            $data['cover_image'] = $request->file('cover_image')->store('public/images');
        }
    
        // Mettre à jour les autres champs
        $article->update($data);
    
        // Mettre à jour les tags
        if ($request->has('tags')) {
            $article->tags()->sync($request->tags);
        }
    
        return redirect()->route('articles.index');
    }
    

    // Supprimer un article
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('articles.index');
    }
    public function articlesByCategory($slug)
{
    $category = Category::where('slug', $slug)->firstOrFail();
    $articles = $category->articles()->latest()->paginate(10);

    return view('articles.index', compact('category', 'articles'));
}
public function articlesByTag($slug)
{
    $tag = Tag::where('slug', $slug)->firstOrFail();
    $articles = $tag->articles()->latest()->paginate(10);

    return view('articles.index', compact('tag', 'articles'));
}
public function search(Request $request)
    {
        $query = Article::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function ($query) use ($request) {
                $query->where('tags.name', 'like', '%' . $request->tag . '%');
            });
        }

        $articles = $query->get();

        return view('articles.index', compact('articles'));
    }

    public function like($articleId)
    {
        $article = Article::findOrFail($articleId); // Récupère l'article

        $user = auth()->user(); // Utilisateur connecté

        // Vérifie si l'utilisateur a déjà liké cet article
        if ($article->likes()->where('user_id', $user->id)->exists()) {
            // Si l'utilisateur a déjà liké, il retire son like
            $article->likes()->detach($user);
            return back()->with('success', 'Vous avez retiré votre like.');
        } else {
            // Si l'utilisateur n'a pas liké, il ajoute un like
            $article->likes()->attach($user);
            return back()->with('success', 'Vous avez aimé cet article.');
        }
    }

}
