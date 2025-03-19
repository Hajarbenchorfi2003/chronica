<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'slug', 'description'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[categories.name,id,{id}]',
        'slug' => 'required|is_unique[categories.slug,id,{id}]',
        'description' => 'permit_empty|max_length[255]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom de la catégorie est requis',
            'min_length' => 'Le nom doit contenir au moins 2 caractères',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères',
            'is_unique' => 'Ce nom est déjà utilisé'
        ],
        'slug' => [
            'required' => 'Le slug est requis',
            'is_unique' => 'Ce slug est déjà utilisé'
        ],
        'description' => [
            'max_length' => 'La description ne peut pas dépasser 255 caractères'
        ]
    ];

    public function getCategoriesWithArticleCount()
    {
        $builder = $this->db->table($this->table);
        $builder->select('categories.*, COUNT(articles.id) as article_count');
        $builder->join('articles', 'articles.category_id = categories.id', 'left');
        $builder->groupBy('categories.id');
        $builder->orderBy('categories.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getCategoryWithArticles($id, $limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('categories.*, articles.*, users.username as author_name');
        $builder->join('articles', 'articles.category_id = categories.id');
        $builder->join('users', 'users.id = articles.author_id');
        $builder->where('categories.id', $id);
        $builder->where('articles.status', 'published');
        $builder->orderBy('articles.created_at', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    public function getCategoryBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }
} 