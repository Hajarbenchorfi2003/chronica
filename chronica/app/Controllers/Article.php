<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CommentModel;

class Article extends BaseController
{
    protected $articleModel;
    protected $commentModel;
    protected $auth;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
        $this->auth = new Auth();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $data['articles'] = $this->articleModel->getArticlesWithAuthor(10, $page);
        $data['pager'] = $this->articleModel->pager;

        return view('articles/index', $data);
    }

    public function view($slug)
    {
        $article = $this->articleModel->getArticleBySlug($slug);
        
        if (!$article) {
            return redirect()->to('/articles')->with('error', 'Article non trouvé');
        }

        $page = $this->request->getGet('page') ?? 1;
        $data = [
            'article' => $article,
            'comments' => $this->commentModel->getCommentsByArticle($article['id'], 10, $page),
            'commentCount' => $this->commentModel->getCommentCountByArticle($article['id']),
            'pager' => $this->commentModel->pager
        ];

        return view('articles/view', $data);
    }

    public function create()
    {
        if (!$this->auth->isAuthor()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'title' => $this->request->getPost('title'),
                'content' => $this->request->getPost('content'),
                'category' => $this->request->getPost('category'),
                'author_id' => session()->get('user_id'),
                'status' => $this->request->getPost('status') ?? 'draft'
            ];

            // Générer le slug à partir du titre
            $data['slug'] = url_title($data['title'], '-', true);

            if ($this->articleModel->save($data)) {
                return redirect()->to('/articles')->with('success', 'Article créé avec succès');
            }

            return redirect()->back()->withInput()->with('errors', $this->articleModel->errors());
        }

        return view('articles/create');
    }

    public function edit($id)
    {
        if (!$this->auth->isAuthor()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $article = $this->articleModel->find($id);

        if (!$article) {
            return redirect()->to('/articles')->with('error', 'Article non trouvé');
        }

        // Vérifier si l'utilisateur est l'auteur ou l'admin
        if (!$this->auth->isAdmin() && $article['author_id'] != session()->get('user_id')) {
            return redirect()->to('/articles')->with('error', 'Accès non autorisé');
        }

        if ($this->request->getMethod() === 'post') {
            $data = [
                'title' => $this->request->getPost('title'),
                'content' => $this->request->getPost('content'),
                'category' => $this->request->getPost('category'),
                'status' => $this->request->getPost('status') ?? 'draft'
            ];

            // Générer le nouveau slug si le titre a changé
            if ($data['title'] !== $article['title']) {
                $data['slug'] = url_title($data['title'], '-', true);
            }

            if ($this->articleModel->update($id, $data)) {
                return redirect()->to('/articles')->with('success', 'Article mis à jour avec succès');
            }

            return redirect()->back()->withInput()->with('errors', $this->articleModel->errors());
        }

        return view('articles/edit', ['article' => $article]);
    }

    public function delete($id)
    {
        if (!$this->auth->isAuthor()) {
            return redirect()->to('/login')->with('error', 'Accès non autorisé');
        }

        $article = $this->articleModel->find($id);

        if (!$article) {
            return redirect()->to('/articles')->with('error', 'Article non trouvé');
        }

        // Vérifier si l'utilisateur est l'auteur ou l'admin
        if (!$this->auth->isAdmin() && $article['author_id'] != session()->get('user_id')) {
            return redirect()->to('/articles')->with('error', 'Accès non autorisé');
        }

        if ($this->articleModel->delete($id)) {
            return redirect()->to('/articles')->with('success', 'Article supprimé avec succès');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression de l\'article');
    }

    public function category($category)
    {
        $page = $this->request->getGet('page') ?? 1;
        $data['articles'] = $this->articleModel->getArticlesByCategory($category, 10, $page);
        $data['pager'] = $this->articleModel->pager;
        $data['category'] = $category;

        return view('articles/category', $data);
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $page = $this->request->getGet('page') ?? 1;

        if (!$keyword) {
            return redirect()->to('/articles');
        }

        $data['articles'] = $this->articleModel->searchArticles($keyword, 10, $page);
        $data['pager'] = $this->articleModel->pager;
        $data['keyword'] = $keyword;

        return view('articles/search', $data);
    }
} 