<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    

    public function index()
    {
        // Vérifier si l'utilisateur est un administrateur
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count(); // Assuming 'status' is the field that determines whether an article is published
        $totalCategories = Category::count();
          $activeUsers = User::where('status', 'active')->count();

// ✅ Récupérer les 5 articles les plus récents
$recentArticles = Article::latest()->take(5)->get();

// ✅ Récupérer les 5 derniers commentaires
$recentComments = Comment::latest()->take(5)->get();
        // Récupérer les données nécessaires
        $data = [
            'totalUsers' => User::count(),
            'totalArticles' => $totalArticles,
            'publishedArticles' => $publishedArticles,
            'totalComments' => Comment::count(),
            'pendingComments' => Comment::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', $data, compact('totalCategories', 'activeUsers', 'recentArticles', 'recentComments'));
    }

    public function users()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        if (request()->isMethod('post')) {
            $data = request()->only('username', 'email', 'password', 'role');
            $data['password'] = bcrypt($data['password']); // Hachage du mot de passe

            User::create($data);

            return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès');
        }

        return view('admin.create_user');
    }

    public function editUser($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        $user = User::findOrFail($id);

        if (request()->isMethod('post')) {
            $data = request()->only('username', 'email', 'role');

            if (request('password')) {
                $data['password'] = bcrypt(request('password'));
            }

            $user->update($data);
            return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès');
        }

        return view('admin.edit_user', compact('user'));
    }

    public function deleteUser($id)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        $user = User::findOrFail($id);

        if ($user->role == 'admin') {
            return redirect()->route('admin.users')->with('error', 'Impossible de supprimer un administrateur');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
    }

    public function articles()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        $articles = Article::with('author')->paginate(10);
        return view('admin.articles', compact('articles'));
    }

    public function comments()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé');
        }

        $comments = Comment::with(['user', 'article'])->paginate(10);
        return view('admin.comments', compact('comments'));
    }
}
