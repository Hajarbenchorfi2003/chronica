<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'author1',
                'email' => 'author1@example.com',
                'password' => password_hash('author123', PASSWORD_DEFAULT),
                'role' => 'author',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'author2',
                'email' => 'author2@example.com',
                'password' => password_hash('author123', PASSWORD_DEFAULT),
                'role' => 'author',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'user2',
                'email' => 'user2@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role' => 'user',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
} 