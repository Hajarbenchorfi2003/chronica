<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CommentModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    protected $articleModel;
    protected $commentModel;
    protected $categoryModel;
    protected $auth;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->commentModel = new CommentModel();
        $this->categoryModel = new CategoryModel();
        $this->auth = new Auth();
    }

    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        
        $data = [
            'title' => 'Accueil',
            'articles' => $this->articleModel->getArticlesWithRelations(['status' => 'published'], 10),
            'categories' => $this->categoryModel->findAll(),
            'pager' => $this->articleModel->pager
        ];

        // Ajouter le nombre de commentaires pour chaque article
        foreach ($data['articles'] as &$article) {
            $article['comment_count'] = $this->commentModel->where('article_id', $article['id'])
                                                         ->where('status', 'approved')
                                                         ->countAllResults();
        }

        return view('home', $data);
    }

    public function about()
    {
        return view('home/about');
    }

    public function contact()
    {
        if ($this->request->getMethod() === 'post') {
            // Logique d'envoi de formulaire de contact
            return redirect()->to('/contact')->with('success', 'Message envoyé avec succès');
        }

        return view('home/contact');
    }

    public function search()
    {
        $keyword = $this->request->getGet('q');
        $page = $this->request->getGet('page') ?? 1;

        if (!$keyword) {
            return redirect()->to('/');
        }

        $data = [
            'articles' => $this->articleModel->searchArticles($keyword, 10, $page),
            'pager' => $this->articleModel->pager,
            'keyword' => $keyword,
            'categories' => $this->getCategories()
        ];

        return view('home/search', $data);
    }

    public function category($category)
    {
        $page = $this->request->getGet('page') ?? 1;
        $data = [
            'articles' => $this->articleModel->getArticlesByCategory($category, 10, $page),
            'pager' => $this->articleModel->pager,
            'category' => $category,
            'categories' => $this->getCategories()
        ];

        return view('home/category', $data);
    }

    private function getCategories()
    {
        $articles = $this->articleModel->select('category')
                                     ->where('status', 'published')
                                     ->findAll();

        $categories = [];
        foreach ($articles as $article) {
            $categories[$article['category']] = true;
        }

        return array_keys($categories);
    }

    private function getRecentComments($limit = 5)
    {
        return $this->commentModel->select('comments.*, users.username as author_name, articles.title as article_title')
                                 ->join('users', 'users.id = comments.user_id')
                                 ->join('articles', 'articles.id = comments.article_id')
                                 ->where('comments.status', 'approved')
                                 ->orderBy('comments.created_at', 'DESC')
                                 ->limit($limit)
                                 ->findAll();
    }
}
