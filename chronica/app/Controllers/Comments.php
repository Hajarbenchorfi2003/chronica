<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\ArticleModel;

class Comments extends BaseController
{
    protected $commentModel;
    protected $articleModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->articleModel = new ArticleModel();
    }

    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour commenter');
        }

        // Validation des données
        $rules = [
            'content' => 'required|min_length[3]|max_length[1000]',
            'article_id' => 'required|integer|is_not_unique[articles.id]',
            'parent_id' => 'permit_empty|integer|is_not_unique[comments.id]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $commentData = [
            'content' => $this->request->getPost('content'),
            'article_id' => $this->request->getPost('article_id'),
            'user_id' => session()->get('id'),
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'status' => 'pending'
        ];

        $this->commentModel->insert($commentData);

        return redirect()->back()->with('success', 'Votre commentaire a été soumis pour modération');
    }

    public function approve($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $comment = $this->commentModel->find($id);
        
        if (!$comment) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Commentaire non trouvé');
        }

        $this->commentModel->approve($id);

        return redirect()->back()->with('success', 'Commentaire approuvé avec succès');
    }

    public function reject($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $comment = $this->commentModel->find($id);
        
        if (!$comment) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Commentaire non trouvé');
        }

        $this->commentModel->reject($id);

        return redirect()->back()->with('success', 'Commentaire rejeté avec succès');
    }

    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $comment = $this->commentModel->find($id);
        
        if (!$comment) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Commentaire non trouvé');
        }

        $this->commentModel->delete($id);

        return redirect()->back()->with('success', 'Commentaire supprimé avec succès');
    }

    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $data = [
            'title' => 'Gestion des commentaires',
            'comments' => $this->commentModel->getCommentsWithRelations()
        ];

        return view('admin/comments', $data);
    }

    public function pending()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $data = [
            'title' => 'Commentaires en attente',
            'comments' => $this->commentModel->getPendingComments()
        ];

        return view('admin/comments', $data);
    }

    public function getReplies($commentId)
    {
        $replies = $this->commentModel->getReplies($commentId);
        return $this->response->setJSON($replies);
    }
} 