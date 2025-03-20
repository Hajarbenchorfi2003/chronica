<?php
namespace App\Http\Controllers;

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
        $user = Auth::user();  // Récupère l'utilisateur actuellement authentifié
        
        // Si l'utilisateur est un admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');  // Redirige vers le tableau de bord admin
        }

        // Sinon, redirige vers la page des articles
        return redirect()->route('articles.index');  // Remplace 'articles.index' par la route de ta page des articles
    }
}
