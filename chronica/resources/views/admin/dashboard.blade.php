@extends('layouts.main')

@section('title', 'Tableau de bord')

@section('content')
<div class="container">
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

    <div class="row">
        <!-- Articles récents -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Articles récents</h5>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach ($recentArticles as $article)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none">
                                                {{ $article->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            Par {{ $article->author->name }} • 
                                            {{ $article->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $article->status === 'published' ? 'success' : 'warning' }}">
                                        {{ $article->status === 'published' ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Commentaires récents -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Commentaires récents</h5>
                    <a href="{{ route('admin.comments.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach ($recentComments as $comment)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('articles.show', $comment->article->slug) }}" class="text-decoration-none">
                                                {{ $comment->article->title }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                        Par {{ $comment->author->name }} • 
                                        {{ $comment->created_at->format('d/m/Y H:i') }}
                                     </small>
                                        </div>
                                     <span class="badge bg-{{ $comment->status === 'approved' ? 'success' : ($comment->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ $comment->status === 'approved' ? 'Approuvé' : ($comment->status === 'rejected' ? 'Rejeté' : 'En attente') }}
                                     </span>
                                     </div>
                                     </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


                               
