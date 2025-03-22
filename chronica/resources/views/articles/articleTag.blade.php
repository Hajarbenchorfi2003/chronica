@extends('layouts.main')

@section('title', "Articles tagués: " . $tag->name)

@section('content')
    <div class="container">
        <h1 class="mb-4">Articles avec le tag : {{ $tag->name }}</h1>

        @if($articles->count() > 0)
            <div class="row">
                @foreach($articles as $article)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset(  $article->cover_image) }}" class="card-img-top w-100 h-100" alt="{{ $article->title }}">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </h5>
                                <p class="card-text">{{ $article->excerpt }}</p>
                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">Lire plus</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4">
                <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                    <h3 class="m-0">Categories</h3>
                </div>
                @foreach($categorys as $cta)
                <div class="position-relative overflow-hidden mb-3" style="height: 80px;">
                    <img class="img-fluid w-100 h-100" src="{{ asset('assest/img/cat-500x80-4.jpg') }}" style="object-fit: cover;">
                    <a href="{{ route('articles.byCategory', $cta->slug) }}" class="overlay align-items-center justify-content-center h4 m-0 text-white text-decoration-none">
                        {{$cta->name}}
                    </a>
                </div>
                @endforeach 
                
                
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>
        @else
            <p>Aucun article trouvé avec ce tag.</p>
        @endif
    </div>
@endsection
