<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['username', 'email', 'password', 'role', 'status'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,author,user]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'username' => [
            'required' => 'Le nom d\'utilisateur est requis',
            'min_length' => 'Le nom d\'utilisateur doit contenir au moins 3 caractères',
            'max_length' => 'Le nom d\'utilisateur ne peut pas dépasser 100 caractères',
            'is_unique' => 'Ce nom d\'utilisateur est déjà utilisé'
        ],
        'email' => [
            'required' => 'L\'email est requis',
            'valid_email' => 'L\'email n\'est pas valide',
            'is_unique' => 'Cet email est déjà utilisé'
        ],
        'password' => [
            'required' => 'Le mot de passe est requis',
            'min_length' => 'Le mot de passe doit contenir au moins 6 caractères'
        ],
        'role' => [
            'required' => 'Le rôle est requis',
            'in_list' => 'Le rôle n\'est pas valide'
        ],
        'status' => [
            'required' => 'Le statut est requis',
            'in_list' => 'Le statut n\'est pas valide'
        ]
    ];

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByUsername(string $username)
    {
        return $this->where('username', $username)->first();
    }

    public function getAuthors()
    {
        return $this->where('role', 'author')
                    ->where('status', 'active')
                    ->findAll();
    }

    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function isActive($id)
    {
        $user = $this->find($id);
        return $user && $user['status'] === 'active';
    }
} 