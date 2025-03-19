<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    public function login()
    {        
        // die('Méthode login appelée !');
        if ($this->request->getMethod() === 'post') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $this->session->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                    'logged_in' => true
                ]);

                return redirect()->to('/dashboard');
            }

            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role' => 'visitor'
            ];

            if ($this->userModel->save($data)) {
                return redirect()->to('/login')->with('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');
            }

            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return view('auth/register');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/');
    }

    public function profile()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);

        if ($this->request->getMethod() === 'post') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email')
            ];

            if ($this->request->getPost('password')) {
                $data['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->update($userId, $data)) {
                $this->session->set('username', $data['username']);
                return redirect()->to('/profile')->with('success', 'Profil mis à jour avec succès');
            }

            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return view('auth/profile', ['user' => $user]);
    }

    public function isLoggedIn()
    {
        return $this->session->get('logged_in') === true;
    }

    public function isAdmin()
    {
        return $this->session->get('role') === 'admin';
    }

    public function isAuthor()
    {
        return in_array($this->session->get('role'), ['admin', 'author']);
    }
} 