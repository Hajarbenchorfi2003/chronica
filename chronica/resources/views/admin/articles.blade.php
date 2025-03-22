@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Gestion des Articles</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Statut</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>{{ $article->id }}</td>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->author->username ?? 'Inconnu' }}</td>
                    <td>{{ $article->status }}</td>
                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $articles->links() }}
</div>
@endsection
