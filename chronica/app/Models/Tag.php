<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Relation Many-to-Many avec les articles
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag');
    }
}
