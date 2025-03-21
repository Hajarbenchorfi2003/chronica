<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('article', 'user')->get();
        return view('admin.comments.index', compact('comments'));
    }
    public function store(Request $request, $articleId)
    {
        $request->validate([
            'content' => 'required|min:3',
        ]);

        Comment::create([
            'article_id' => $articleId,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'status' => 'pending', // Ou 'approved' selon ton système de validation des commentaires
        ]);

        return back()->with('success', 'Commentaire ajouté !');
    }

    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $comment->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.comments.index')->with('success', 'Commentaire mis à jour.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Commentaire supprimé.');
    }
}
