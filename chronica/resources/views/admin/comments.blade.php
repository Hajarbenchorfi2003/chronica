@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Gestion des Commentaires</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Article</th>
                <th>Commentaire</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->user->username }}</td>
                    <td>{{ $comment->article->title }}</td>
                    <td>{{ $comment->content }}</td>
                    <td>{{ $comment->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $comments->links() }}
</div>
@endsection
