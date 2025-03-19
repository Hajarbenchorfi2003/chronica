<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'content', 'article_id', 'user_id', 'parent_id', 'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'content' => 'required|min_length[3]|max_length[1000]',
        'article_id' => 'required|integer|is_not_unique[articles.id]',
        'user_id' => 'required|integer|is_not_unique[users.id]',
        'parent_id' => 'permit_empty|integer|is_not_unique[comments.id]',
        'status' => 'required|in_list[pending,approved,rejected]'
    ];

    protected $validationMessages = [
        'content' => [
            'required' => 'Le contenu du commentaire est requis',
            'min_length' => 'Le commentaire doit contenir au moins 3 caractères',
            'max_length' => 'Le commentaire ne peut pas dépasser 1000 caractères'
        ],
        'article_id' => [
            'required' => 'L\'article est requis',
            'integer' => 'L\'article doit être un nombre entier',
            'is_not_unique' => 'L\'article n\'existe pas'
        ],
        'user_id' => [
            'required' => 'L\'utilisateur est requis',
            'integer' => 'L\'utilisateur doit être un nombre entier',
            'is_not_unique' => 'L\'utilisateur n\'existe pas'
        ],
        'parent_id' => [
            'integer' => 'Le commentaire parent doit être un nombre entier',
            'is_not_unique' => 'Le commentaire parent n\'existe pas'
        ],
        'status' => [
            'required' => 'Le statut est requis',
            'in_list' => 'Le statut n\'est pas valide'
        ]
    ];

    public function getCommentsWithRelations($conditions = [], $limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('comments.*, users.username as author_name, articles.title as article_title');
        $builder->join('users', 'users.id = comments.user_id');
        $builder->join('articles', 'articles.id = comments.article_id');

        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $builder->where("comments.{$key}", $value);
            }
        }

        $builder->orderBy('comments.created_at', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    public function getCommentsByArticle($articleId, $limit = 10, $offset = 0)
    {
        return $this->getCommentsWithRelations(
            ['article_id' => $articleId, 'status' => 'approved'],
            $limit,
            $offset
        );
    }

    public function getPendingComments($limit = 10, $offset = 0)
    {
        return $this->getCommentsWithRelations(
            ['status' => 'pending'],
            $limit,
            $offset
        );
    }

    public function getReplies($commentId)
    {
        return $this->getCommentsWithRelations(
            ['parent_id' => $commentId, 'status' => 'approved']
        );
    }

    public function approve($id)
    {
        return $this->update($id, ['status' => 'approved']);
    }

    public function reject($id)
    {
        return $this->update($id, ['status' => 'rejected']);
    }

    public function getCommentCountByArticle($articleId)
    {
        return $this->where('article_id', $articleId)
                    ->where('status', 'approved')
                    ->countAllResults();
    }

    public function getApprovedComments($articleId, $limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('comments.*, users.username as author_name');
        $builder->join('users', 'users.id = comments.user_id');
        $builder->where('article_id', $articleId);
        $builder->where('status', 'approved');
        $builder->orderBy('created_at', 'DESC');
        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }
} 