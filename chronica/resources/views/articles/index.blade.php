@extends('layouts.main')

@section('title', 'Tous les articles')

@section('content')
    <div class="container">
        <h1>Tous les articles</h1>
        <a href="{{ route('articles.create') }}" class="btn btn-primary">Créer un nouvel article</a>
        <div class="row mt-4">
            @foreach ($articles as $article)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $article->cover_image) }}" class="card-img-top" alt="{{ $article->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ Str::limit($article->content, 100) }}</p>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary">Lire plus</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
