@extends('layouts.main')

@section('title', 'Tous les articles')

@section('content')
    <!-- Main News Section Start -->
    <div class="container my-4">
        <form action="{{ route('articles.index') }}" method="GET" class="d-flex mb-4">
            <input type="text" name="title" class="form-control" placeholder="Search by title" value="{{ request()->title }}">
            <select name="category_id" class="form-control ml-2">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request()->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="tag" class="form-control ml-2" placeholder="Search by tag" value="{{ request()->tag }}">
            <button type="submit" class="btn btn-primary ml-2">Search</button>
        </form>
        <a href="{{ route('articles.create') }}" class="btn btn-primary">Créer un nouvel article</a>
    </div>
    <div class="container-fluid py-3">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-8">
                    <!-- Featured Articles -->
                    <div class="row">
                        @foreach($articles as $article)
                            <div class="col-lg-4 col-md-6 col-12 mb-4">
                                <div class="  ">
                                    
                                        <img class="img-fluid w-100 h-100 " src="{{ asset($article->cover_image) }}" style="object-fit: cover;">
                                  
                                   
                                    <div class="  bg-light p-3 h-100">
                                        <div class="mb-2" style="font-size: 13px;">
                                            <a href="{{ route('articles.byCategory', $article->category->slug) }}">{{ $article->category->name }}</a>
                                            <span class="px-1">/</span>
                                            <span>{{ $article->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <a class="h4 m-0" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                </div>

                <div class="col-lg-4">
                    <!-- Categories Section -->
                    <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                        <h3 class="m-0">Categories</h3>
                    </div>

                    @foreach($categories as $category)
                        <div class="position-relative overflow-hidden mb-3" style="height: 80px;">
                            <img class="img-fluid w-100 h-100" src="{{ asset('assest/img/cat-500x80-4.jpg') }}" style="object-fit: cover;">
                             <a href="{{ route('articles.byCategory', $category->slug) }}" class="overlay align-items-center justify-content-center h4 m-0 text-white text-decoration-none">
                                {{ $category->name }}
                            </a> 
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>  
    @if($articles->isEmpty())
    <p class="text-center">Aucun article trouvé.</p>
@endif
@endsection
