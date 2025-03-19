<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleModel extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'title', 'slug', 'content', 'excerpt', 'cover_image',
        'category_id', 'author_id', 'status', 'views'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug' => 'required|is_unique[articles.slug,id,{id}]',
        'content' => 'required',
        'excerpt' => 'required|max_length[255]',
        'category_id' => 'required|integer|is_not_unique[categories.id]',
        'author_id' => 'required|integer|is_not_unique[users.id]',
        'status' => 'required|in_list[draft,published]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Le titre est requis',
            'min_length' => 'Le titre doit contenir au moins 3 caractères',
            'max_length' => 'Le titre ne peut pas dépasser 255 caractères'
        ],
        'slug' => [
            'required' => 'Le slug est requis',
            'is_unique' => 'Ce slug est déjà utilisé'
        ],
        'content' => [
            'required' => 'Le contenu est requis'
        ],
        'excerpt' => [
            'required' => 'L\'extrait est requis',
            'max_length' => 'L\'extrait ne peut pas dépasser 255 caractères'
        ],
        'category_id' => [
            'required' => 'La catégorie est requise',
            'integer' => 'La catégorie doit être un nombre entier',
            'is_not_unique' => 'La catégorie n\'existe pas'
        ],
        'author_id' => [
            'required' => 'L\'auteur est requis',
            'integer' => 'L\'auteur doit être un nombre entier',
            'is_not_unique' => 'L\'auteur n\'existe pas'
        ],
        'status' => [
            'required' => 'Le statut est requis',
            'in_list' => 'Le statut n\'est pas valide'
        ]
    ];

    public function getArticlesWithRelations($conditions = [], $limit = 10)
    {
        $builder = $this->builder()
            ->select('articles.*, users.username as author_name, categories.name as category_name')
            ->join('users', 'users.id = articles.author_id')
            ->join('categories', 'categories.id = articles.category_id')
            ->orderBy('articles.created_at', 'DESC');

        // Correction : Ajout du préfixe "articles." pour éviter l’ambiguïté
        foreach ($conditions as $key => $value) {
            if ($key === 'status') {
                $builder->where("articles.status", $value);
            } else {
                $builder->where("articles.{$key}", $value);
            }
        }

        return $this->paginate($limit);
    }

    public function getArticleWithRelations($id)
    {
        return $this->builder()
            ->select('articles.*, users.username as author_name, categories.name as category_name')
            ->join('users', 'users.id = articles.author_id')
            ->join('categories', 'categories.id = articles.category_id')
            ->where('articles.id', $id)
            ->get()
            ->getRowArray();
    }

    public function getArticlesByAuthor($authorId, $limit = 10)
    {
        return $this->getArticlesWithRelations(['author_id' => $authorId], $limit);
    }

    public function getArticlesByCategory($categoryId, $limit = 10)
    {
        return $this->getArticlesWithRelations(['category_id' => $categoryId], $limit);
    }

    public function getPublishedArticles($limit = 10)
    {
        return $this->getArticlesWithRelations(['status' => 'published'], $limit);
    }

    public function incrementViews($id)
    {
        return $this->set('views', 'views + 1', false)
                    ->where('id', $id)
                    ->update();
    }

    public function getPopularArticles($limit = 5)
    {
        return $this->builder()
            ->select('articles.*, users.username as author_name, categories.name as category_name')
            ->join('users', 'users.id = articles.author_id')
            ->join('categories', 'categories.id = articles.category_id')
            ->where('articles.status', 'published')
            ->orderBy('articles.views', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getPopularArticlesByCategory($categoryId, $limit = 5)
    {
        return $this->builder()
            ->select('articles.*, users.username as author_name, categories.name as category_name')
            ->join('users', 'users.id = articles.author_id')
            ->join('categories', 'categories.id = articles.category_id')
            ->where('articles.status', 'published')
            ->where('articles.category_id', $categoryId)
            ->orderBy('articles.views', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}
