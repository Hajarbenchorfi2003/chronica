<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'cover_image', 'category_id', 'author_id', 'status', 'views'
    ];

    // Relation avec l'utilisateur (Un article appartient à un utilisateur)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Relation avec la catégorie (Un article appartient à une catégorie)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation Many-to-Many avec les tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }

    // Relation One-to-Many avec les commentaires
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
