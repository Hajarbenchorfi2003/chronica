<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Supprimer la session de l'utilisateur.
     */
    /**
     * Gérer la redirection après l'authentification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        // Vérifiez le rôle de l'utilisateur
        if ($user->role === 'admin') {
            return redirect()->route('admin'); // Rediriger vers le tableau de bord pour les administrateurs
        }

        return redirect('/'); // Rediriger vers la page d'accueil pour les autres utilisateurs
    }
    public function destroy(Request $request)
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();

        // Déconnexion de l'utilisateur
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Vérifier le rôle de l'utilisateur et rediriger en conséquence
         

        // Redirection vers la page d'accueil pour les autres utilisateurs
        return redirect('/');
    }
}
