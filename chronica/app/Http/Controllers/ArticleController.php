<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ArticleController extends Controller
{
    // Afficher tous les articles
    public function index(Request $request)
    {
        $query = Article::with('category', 'tags', 'comments');
    
        // Appliquer les filtres si remplis
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
    
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
    
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->tag . '%');
            });
        }
    
        // Récupérer les articles après filtrage
        $articles = $query->get();
        $categories = Category::with('articles')->get();
    
        return view('articles.index', compact('articles', 'categories'));
    }
    


    // Afficher un article spécifique
    public function show($slug)
    {
        // Find the article by its slug, including related categories, tags, and comments
        $article = Article::with('category', 'tags', 'comments')->where('slug', $slug)->firstOrFail();

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
        // Valider les données
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:articles',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|string', // Traitez les tags comme une chaîne séparée par des virgules
        ]);
    
        // Générer l'excerpt à partir du contenu si non fourni
        if (empty($data['excerpt'])) {
            $data['excerpt'] = substr(strip_tags($data['content']), 0, 255); // Prendre les 255 premiers caractères du contenu
        }
    
        // Ajouter l'ID de l'utilisateur connecté comme auteur
        $data['author_id'] = Auth::id();
    
        // Gérer les tags si fournis
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $tags = array_map('trim', $tags);
        } else {
            $tags = [];
        }
    
        // Générer le slug s'il n'est pas fourni
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
    
        // Gérer le téléchargement de l'image de couverture
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('images', 'public');
            $data['cover_image'] = 'storage/' . $imagePath;
        }
    
        // Créer l'article
        $article = Article::create($data);
    
        // Associer les tags si présents
        if (!empty($tags)) {
            $tagIds = Tag::whereIn('name', $tags)->pluck('id');
            $article->tags()->sync($tagIds);
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
    $categorys = Category::all();
    // Récupérer les articles en vedette de la catégorie
    $featuredArticles = $category->articles()->latest()->paginate(10);
    // Récupérer tous les tags pour l'affichage des tags
    $tags = Tag::all();
    return view('articles.articleCta', compact('category','categorys', 'featuredArticles', 'tags'));
}
public function articlesByTag($slug)
{
    $tag = Tag::where('slug', $slug)->firstOrFail();
    $articles = $tag->articles()->latest()->paginate(10);
    $categorys = Category::all();

    return view('articles.articleTag', compact('tag', 'articles','categorys'));
}
public function search(Request $request)
{
    $query = Article::query();

    // Vérifier si un mot-clé a été saisi
    if ($request->filled('title')) {
        $query->where('title', 'like', '%' . $request->title . '%');
    }

    // Vérifier si une catégorie a été sélectionnée
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Vérifier si un tag a été saisi
    if ($request->filled('tag')) {
        $query->whereHas('tags', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->tag . '%');
        });
    }

    // Si aucun filtre n'est appliqué, on affiche tous les articles
    $articles = $query->get();
    $categories = Category::all();

    return view('articles.search', compact('articles', 'categories'));
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
