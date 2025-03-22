<?php
namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run()
    {
        $articles = [
            [
                'title' => 'First Article',
                'slug' => 'first-article',
                'content' => 'This is the content of the first article.',
                'excerpt' => 'This is the excerpt of the first article.',
                'cover_image' => 'first-article.jpg',
                'category_id' => 1, // Assumes you have a category with ID 1
                'author_id' => 1, // Assumes you have a user with ID 1
                'status' => 'published',
            ],
            // Ajoute d'autres articles ici
        ];

        foreach ($articles as $article) {
            Article::create($article);
        }
    }
}
