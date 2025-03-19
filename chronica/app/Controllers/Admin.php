<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ArticleModel;
use App\Models\CommentModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $articleModel;
    protected $commentModel;
    protected $auth;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
        $this->auth = new Auth();
    }

    public function index()
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $data = [
            'totalUsers' => $this->userModel->countAll(),
            'totalArticles' => $this->articleModel->countAll(),
            'totalComments' => $this->commentModel->countAll(),
            'pendingComments' => $this->commentModel->where('status', 'pending')->countAllResults()
        ];

        return view('admin/dashboard', $data);
    }

    public function users()
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $page = $this->request->getGet('page') ?? 1;
        $data['users'] = $this->userModel->paginate(10, 'users', $page);
        $data['pager'] = $this->userModel->pager;

        return view('admin/users', $data);
    }

    public function createUser()
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'role' => $this->request->getPost('role')
            ];

            if ($this->userModel->save($data)) {
                return redirect()->to('/admin/users')->with('success', 'Utilisateur créé avec succès');
            }

            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return view('admin/create_user');
    }

    public function editUser($id)
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur non trouvé');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role')
            ];

            if ($this->request->getPost('password')) {
                $data['password'] = $this->request->getPost('password');
            }

            if ($this->userModel->update($id, $data)) {
                return redirect()->to('/admin/users')->with('success', 'Utilisateur mis à jour avec succès');
            }

            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return view('admin/edit_user', ['user' => $user]);
    }

    public function deleteUser($id)
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Utilisateur non trouvé');
        }

        // Empêcher la suppression de l'administrateur principal
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/users')->with('error', 'Impossible de supprimer un administrateur');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')->with('success', 'Utilisateur supprimé avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'utilisateur');
    }

    public function articles()
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $page = $this->request->getGet('page') ?? 1;
        $data['articles'] = $this->articleModel->select('articles.*, users.username as author_name')
                                             ->join('users', 'users.id = articles.author_id')
                                             ->paginate(10, 'articles', $page);
        $data['pager'] = $this->articleModel->pager;

        return view('admin/articles', $data);
    }

    public function comments()
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $page = $this->request->getGet('page') ?? 1;
        $data['comments'] = $this->commentModel->select('comments.*, users.username as author_name, articles.title as article_title')
                                             ->join('users', 'users.id = comments.user_id')
                                             ->join('articles', 'articles.id = comments.article_id')
                                             ->paginate(10, 'comments', $page);
        $data['pager'] = $this->commentModel->pager;

        return view('admin/comments', $data);
    }
} 