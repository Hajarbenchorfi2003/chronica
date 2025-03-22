@extends('layouts.main')

@section('title', 'Search Results')

@section('content')

    <!-- Search Bar -->
    <div class="container my-4">
        <form action="{{ route('articles.search') }}" method="GET" class="d-flex mb-4">
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
    </div>

    <!-- Search Results -->
    <div class="container">
        <h2>Search Results</h2>
        
        @if($articles->isEmpty())
            <p>No articles found for your search criteria.</p>
        @else
            <div class="row">
                @foreach($articles as $article)
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="position-relative">
                            <img class="img-fluid w-100" src="{{ asset($article->cover_image) }}" style="object-fit: cover;">
                            <div class="overlay position-relative bg-light p-3">
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
        @endif
    </div>

@endsection
