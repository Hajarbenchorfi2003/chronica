<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'content' => 'Excellent article sur l\'IA ! Les exemples sont très pertinents.',
                'article_id' => 1,
                'user_id' => 4, // user1
                'parent_id' => null,
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'content' => 'Merci pour votre commentaire ! L\'IA est en effet un sujet passionnant.',
                'article_id' => 1,
                'user_id' => 2, // author1
                'parent_id' => 1, // Réponse au premier commentaire
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'content' => 'Ces conseils de bien-être sont très utiles, merci !',
                'article_id' => 2,
                'user_id' => 5, // user2
                'parent_id' => null,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'content' => 'Résumé très complet des actualités de la semaine.',
                'article_id' => 3,
                'user_id' => 4, // user1
                'parent_id' => null,
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'content' => 'J\'aimerais voir plus de détails sur certains événements.',
                'article_id' => 3,
                'user_id' => 5, // user2
                'parent_id' => null,
                'status' => 'approved',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('comments')->insertBatch($data);
    }
} 