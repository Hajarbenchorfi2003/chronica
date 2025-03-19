<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\CategoryModel;
use App\Models\TagModel;

class Articles extends BaseController
{
    protected $articleModel;
    protected $categoryModel;
    protected $tagModel;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Articles',
            'articles' => $this->articleModel->getArticlesWithRelations(),
            'categories' => $this->categoryModel->getCategoriesWithArticleCount(),
            'popularTags' => $this->tagModel->getPopularTags(10)
        ];

        return view('articles/index', $data);
    }

    public function view($slug)
    {
        $article = $this->articleModel->getArticleWithRelations($slug);
        
        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Article non trouvé');
        }

        // Incrémenter le compteur de vues
        $this->articleModel->incrementViews($article['id']);

        $data = [
            'title' => $article['title'],
            'article' => $article,
            'tags' => $this->tagModel->getArticleTags($article['id'])
        ];

        return view('articles/view', $data);
    }

    public function create()
    {
        if (!in_array(session()->get('role'), ['admin', 'author'])) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $data = [
            'title' => 'Nouvel article',
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('articles/create', $data);
    }

    public function store()
    {
        if (!in_array(session()->get('role'), ['admin', 'author'])) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        // Validation des données
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'category_id' => 'required|integer',
            'content' => 'required',
            'excerpt' => 'required|max_length[255]',
            'status' => 'required|in_list[draft,published]',
            'tags' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Gestion de l'image de couverture
        $coverImage = $this->request->getFile('cover_image');
        $coverImageName = null;

        if ($coverImage->isValid() && !$coverImage->hasMoved()) {
            $coverImageName = $coverImage->getRandomName();
            $coverImage->move('uploads/articles', $coverImageName);
        }

        // Création de l'article
        $articleData = [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('title'), '-', true),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'cover_image' => $coverImageName,
            'category_id' => $this->request->getPost('category_id'),
            'author_id' => session()->get('id'),
            'status' => $this->request->getPost('status')
        ];

        $articleId = $this->articleModel->insert($articleData);

        // Gestion des tags
        if ($this->request->getPost('tags')) {
            $tags = explode(',', $this->request->getPost('tags'));
            $tagIds = [];
            
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $existingTag = $this->tagModel->where('name', $tag)->first();
                    if (!$existingTag) {
                        $tagId = $this->tagModel->insert(['name' => $tag]);
                    } else {
                        $tagId = $existingTag['id'];
                    }
                    $tagIds[] = $tagId;
                }
            }

            if (!empty($tagIds)) {
                $this->tagModel->addTagsToArticle($articleId, $tagIds);
            }
        }

        return redirect()->to('/articles')->with('success', 'Article créé avec succès');
    }

    public function edit($id)
    {
        if (!in_array(session()->get('role'), ['admin', 'author'])) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $article = $this->articleModel->find($id);
        
        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Article non trouvé');
        }

        // Vérifier si l'utilisateur est l'auteur ou l'admin
        if (session()->get('role') !== 'admin' && $article['author_id'] !== session()->get('id')) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $data = [
            'title' => 'Modifier l\'article',
            'article' => $article,
            'categories' => $this->categoryModel->findAll(),
            'tags' => $this->tagModel->getArticleTags($id),
            'validation' => \Config\Services::validation()
        ];

        return view('articles/edit', $data);
    }

    public function update($id)
    {
        if (!in_array(session()->get('role'), ['admin', 'author'])) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $article = $this->articleModel->find($id);
        
        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Article non trouvé');
        }

        // Vérifier si l'utilisateur est l'auteur ou l'admin
        if (session()->get('role') !== 'admin' && $article['author_id'] !== session()->get('id')) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        // Validation des données
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'category_id' => 'required|integer',
            'content' => 'required',
            'excerpt' => 'required|max_length[255]',
            'status' => 'required|in_list[draft,published]',
            'tags' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Gestion de l'image de couverture
        $coverImage = $this->request->getFile('cover_image');
        $coverImageName = $article['cover_image'];

        if ($coverImage->isValid() && !$coverImage->hasMoved()) {
            // Supprimer l'ancienne image
            if ($coverImageName && file_exists('uploads/articles/' . $coverImageName)) {
                unlink('uploads/articles/' . $coverImageName);
            }

            $coverImageName = $coverImage->getRandomName();
            $coverImage->move('uploads/articles', $coverImageName);
        }

        // Mise à jour de l'article
        $articleData = [
            'title' => $this->request->getPost('title'),
            'slug' => url_title($this->request->getPost('title'), '-', true),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'cover_image' => $coverImageName,
            'category_id' => $this->request->getPost('category_id'),
            'status' => $this->request->getPost('status')
        ];

        $this->articleModel->update($id, $articleData);

        // Gestion des tags
        $this->tagModel->removeTagsFromArticle($id);

        if ($this->request->getPost('tags')) {
            $tags = explode(',', $this->request->getPost('tags'));
            $tagIds = [];
            
            foreach ($tags as $tag) {
                $tag = trim($tag);
                if (!empty($tag)) {
                    $existingTag = $this->tagModel->where('name', $tag)->first();
                    if (!$existingTag) {
                        $tagId = $this->tagModel->insert(['name' => $tag]);
                    } else {
                        $tagId = $existingTag['id'];
                    }
                    $tagIds[] = $tagId;
                }
            }

            if (!empty($tagIds)) {
                $this->tagModel->addTagsToArticle($id, $tagIds);
            }
        }

        return redirect()->to('/articles')->with('success', 'Article mis à jour avec succès');
    }

    public function delete($id)
    {
        if (!in_array(session()->get('role'), ['admin', 'author'])) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        $article = $this->articleModel->find($id);
        
        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Article non trouvé');
        }

        // Vérifier si l'utilisateur est l'auteur ou l'admin
        if (session()->get('role') !== 'admin' && $article['author_id'] !== session()->get('id')) {
            return redirect()->to('/')->with('error', 'Accès non autorisé');
        }

        // Supprimer l'image de couverture
        if ($article['cover_image'] && file_exists('uploads/articles/' . $article['cover_image'])) {
            unlink('uploads/articles/' . $article['cover_image']);
        }

        // Supprimer l'article (les relations seront supprimées automatiquement grâce aux contraintes de clé étrangère)
        $this->articleModel->delete($id);

        return redirect()->to('/articles')->with('success', 'Article supprimé avec succès');
    }
} 