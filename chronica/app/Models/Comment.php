<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'article_id', 'user_id', 'parent_id', 'status'
    ];

    // Relation avec l'article (Un commentaire appartient à un article)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Relation avec l'utilisateur (Un commentaire appartient à un utilisateur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le commentaire parent (Un commentaire peut avoir un parent)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Relation avec les commentaires enfants (Un commentaire peut avoir des enfants)
    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
