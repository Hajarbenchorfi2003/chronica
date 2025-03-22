@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container mt-4">

 
<!-- Statistiques générales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Articles</h6>
                        <h2 class="mt-2 mb-0">{{ $totalArticles }}</h2>
                        <small>dont {{ $publishedArticles }} publiés</small>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Commentaires</h6>
                        <h2 class="mt-2 mb-0">{{ $totalComments }}</h2>
                        <small>dont {{ $pendingComments }} en attente</small>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Utilisateurs</h6>
                        <h2 class="mt-2 mb-0">{{ $totalUsers }}</h2>
                        <small>dont {{ $activeUsers }} actifs</small>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Catégories</h6>
                        <h2 class="mt-2 mb-0">{{ $totalCategories }}</h2>
                        <small>articles répartis</small>
                    </div>
                    <div class="fs-1">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row mt-4">
        <!-- Section des Articles Récents -->
        <div class="col-md-6">
            <h3>Articles Récents</h3>
            <ul class="list-group">
            @foreach($recentArticles as $article)
                    <li class="list-group-item">
                        <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                        <span class="badge bg-info float-end">{{ $article->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach  
            </ul>
        </div>

        <!-- Section des Commentaires Récents -->
        <div class="col-md-6">
            <h3>Commentaires Récents</h3>
            <ul class="list-group">
              @foreach($recentComments as $comment)
                    <li class="list-group-item">
                        <strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}
                        <span class="badge bg-info float-end">{{ $comment->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach  
            </ul>
        </div>
    </div>
</div>
@endsection
 

