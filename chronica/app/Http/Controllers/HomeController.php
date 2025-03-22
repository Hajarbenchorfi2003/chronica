<?php
namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Affiche la page d'accueil ou redirige selon le rôle de l'utilisateur.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    

     public function index()
     {
        if (Auth::check() && Auth::user()->role === 'admin') {
             return view('admin.dashboard');
        }
     
        $featuredArticles = Article::where('status', 'published')->latest()->take(5)->get();
        $categories = Category::with('articles')->get();
     
        return view('home', compact('featuredArticles', 'categories'));
     }
     
}
 

