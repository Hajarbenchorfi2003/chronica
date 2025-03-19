<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\ArticleModel;

class Comment extends BaseController
{
    protected $commentModel;
    protected $articleModel;
    protected $auth;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->articleModel = new ArticleModel();
        $this->auth = new Auth();
    }

    public function create()
    {
        if (!$this->auth->isLoggedIn()) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté pour commenter');
        }

        $articleId = $this->request->getPost('article_id');
        $article = $this->articleModel->find($articleId);

        if (!$article) {
            return redirect()->back()->with('error', 'Article non trouvé');
        }

        $data = [
            'content' => $this->request->getPost('content'),
            'article_id' => $articleId,
            'user_id' => session()->get('user_id'),
            'parent_id' => $this->request->getPost('parent_id') ?? null,
            'status' => $this->auth->isAuthor() ? 'approved' : 'pending'
        ];

        if ($this->commentModel->save($data)) {
            return redirect()->back()->with('success', 'Commentaire ajouté avec succès');
        }

        return redirect()->back()->withInput()->with('errors', $this->commentModel->errors());
    }

    public function reply($commentId)
    {
        if (!$this->auth->isAuthor()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $parentComment = $this->commentModel->find($commentId);
        
        if (!$parentComment) {
            return redirect()->back()->with('error', 'Commentaire non trouvé');
        }

        $data = [
            'content' => $this->request->getPost('content'),
            'article_id' => $parentComment['article_id'],
            'user_id' => session()->get('user_id'),
            'parent_id' => $commentId,
            'status' => 'approved'
        ];

        if ($this->commentModel->save($data)) {
            return redirect()->back()->with('success', 'Réponse ajoutée avec succès');
        }

        return redirect()->back()->withInput()->with('errors', $this->commentModel->errors());
    }

    public function moderate()
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $page = $this->request->getGet('page') ?? 1;
        $data['comments'] = $this->commentModel->getPendingComments(10, $page);
        $data['pager'] = $this->commentModel->pager;

        return view('comments/moderate', $data);
    }

    public function approve($id)
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        if ($this->commentModel->approveComment($id)) {
            return redirect()->back()->with('success', 'Commentaire approuvé avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'approbation du commentaire');
    }

    public function reject($id)
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        if ($this->commentModel->rejectComment($id)) {
            return redirect()->back()->with('success', 'Commentaire rejeté avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors du rejet du commentaire');
    }

    public function delete($id)
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        if ($this->commentModel->delete($id)) {
            return redirect()->back()->with('success', 'Commentaire supprimé avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression du commentaire');
    }
} 