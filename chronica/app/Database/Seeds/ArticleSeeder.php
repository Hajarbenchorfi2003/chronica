<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'title' => 'Les dernières innovations en Intelligence Artificielle',
                'slug' => 'innovations-intelligence-artificielle',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                'excerpt' => 'Découvrez les avancées récentes en IA et leur impact sur notre quotidien.',
                'cover_image' => 'ai-innovation.jpg',
                'category_id' => 1, // Technologie
                'author_id' => 2, // author1
                'status' => 'published',
                'views' => 150,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Guide du bien-être au quotidien',
                'slug' => 'guide-bien-etre-quotidien',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                'excerpt' => 'Conseils pratiques pour améliorer votre qualité de vie jour après jour.',
                'cover_image' => 'wellbeing.jpg',
                'category_id' => 2, // Lifestyle
                'author_id' => 3, // author2
                'status' => 'published',
                'views' => 120,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Actualités : Résumé de la semaine',
                'slug' => 'actualites-resume-semaine',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                'excerpt' => 'Les événements marquants de la semaine en un coup d\'œil.',
                'cover_image' => 'news-summary.jpg',
                'category_id' => 3, // Actualités
                'author_id' => 2, // author1
                'status' => 'published',
                'views' => 200,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Les tendances culturelles de 2024',
                'slug' => 'tendances-culturelles-2024',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.',
                'excerpt' => 'Découvrez ce qui fait vibrer le monde de la culture cette année.',
                'cover_image' => 'culture-trends.jpg',
                'category_id' => 4, // Culture
                'author_id' => 3, // author2
                'status' => 'draft',
                'views' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('articles')->insertBatch($data);
    }
} 