<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
         
        // Vérifiez le rôle de l'utilisateur
        if ($user->role === 'admin') {
            return view('admin.dashboard');
        }

        return redirect('/'); 
    }
}
