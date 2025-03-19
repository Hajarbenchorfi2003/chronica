<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[50]|is_unique[tags.name,id,{id}]'
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom du tag est requis',
            'min_length' => 'Le nom doit contenir au moins 2 caractères',
            'max_length' => 'Le nom ne peut pas dépasser 50 caractères',
            'is_unique' => 'Ce tag existe déjà'
        ]
    ];

    public function getTagsWithArticleCount()
    {
        $builder = $this->db->table($this->table);
        $builder->select('tags.*, COUNT(articles_tags.article_id) as article_count');
        $builder->join('articles_tags', 'articles_tags.tag_id = tags.id', 'left');
        $builder->groupBy('tags.id');
        $builder->orderBy('tags.name', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getPopularTags($limit = 10)
    {
        $builder = $this->db->table($this->table);
        $builder->select('tags.*, COUNT(articles_tags.article_id) as article_count');
        $builder->join('articles_tags', 'articles_tags.tag_id = tags.id');
        $builder->join('articles', 'articles.id = articles_tags.article_id');
        $builder->where('articles.status', 'published');
        $builder->groupBy('tags.id');
        $builder->orderBy('article_count', 'DESC');
        $builder->limit($limit);

        return $builder->get()->getResultArray();
    }

    public function getArticlesByTag($tagId, $limit = 10, $offset = 0)
    {
        $builder = $this->db->table('articles_tags');
        $builder->select('articles.*, users.username as author_name, categories.name as category_name');
        $builder->join('articles', 'articles.id = articles_tags.article_id');
        $builder->join('users', 'users.id = articles.author_id');
        $builder->join('categories', 'categories.id = articles.category_id');
        $builder->where('articles_tags.tag_id', $tagId);
        $builder->where('articles.status', 'published');
        $builder->orderBy('articles.created_at', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    public function getTagBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    public function addTagsToArticle($articleId, $tagIds)
    {
        $data = [];
        foreach ($tagIds as $tagId) {
            $data[] = [
                'article_id' => $articleId,
                'tag_id' => $tagId
            ];
        }
        return $this->db->table('articles_tags')->insertBatch($data);
    }

    public function removeTagsFromArticle($articleId)
    {
        return $this->db->table('articles_tags')
                       ->where('article_id', $articleId)
                       ->delete();
    }

    public function getArticleTags($articleId)
    {
        $builder = $this->db->table('articles_tags');
        $builder->select('tags.*');
        $builder->join('tags', 'tags.id = articles_tags.tag_id');
        $builder->where('articles_tags.article_id', $articleId);
        $builder->orderBy('tags.name', 'ASC');

        return $builder->get()->getResultArray();
    }
} 