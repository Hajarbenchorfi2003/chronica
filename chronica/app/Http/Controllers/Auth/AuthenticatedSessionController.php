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
    public function destroy(Request $request)
    {
        Auth::logout(); // Déconnexion de l'utilisateur
        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/'); // Redirection après déconnexion
    }
   
   
}
