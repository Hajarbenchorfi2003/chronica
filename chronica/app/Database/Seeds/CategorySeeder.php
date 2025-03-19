<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Technologie',
                'slug' => 'technologie',
                'description' => 'Articles sur les nouvelles technologies, l\'informatique et l\'innovation',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Lifestyle',
                'slug' => 'lifestyle',
                'description' => 'Articles sur le mode de vie, le bien-être et les tendances',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Actualités',
                'slug' => 'actualites',
                'description' => 'Articles sur l\'actualité et les événements importants',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Culture',
                'slug' => 'culture',
                'description' => 'Articles sur l\'art, la littérature, le cinéma et la musique',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Sport',
                'slug' => 'sport',
                'description' => 'Articles sur les sports, les compétitions et les athlètes',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('categories')->insertBatch($data);
    }
} 