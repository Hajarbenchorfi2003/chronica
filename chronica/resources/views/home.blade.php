<!-- resources/views/home.blade.php -->

@extends('layouts.main')

@section('content')
    <!-- Main News Section Start -->
      <div class="container-fluid py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Featured Articles -->
                    <div class="owl-carousel owl-carousel-2 carousel-item-1 position-relative mb-3 mb-lg-0">
                        @foreach($featuredArticles as $article)
                            <div class="position-relative overflow-hidden" style="height: 435px;">
                                <img class="img-fluid h-100" src="{{ asset('storage/' . $article->cover_image) }}" style="object-fit: cover;">
                                <div class="overlay">
                                    <div class="mb-1">
                                        <a class="text-white" href="#">{{ $article->category->name ?? 'Uncategorized' }}</a>
                                        <span class="px-2 text-white">/</span>
                                        <a class="text-white" href="#">{{ $article->created_at->format('M d, Y') }}</a>
                                    </div>
                                    <a class="h2 m-0 text-white font-weight-bold" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Categories Section -->
                    <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                        <h3 class="m-0">Categories</h3>
                          {{-- <a class="text-secondary font-weight-medium text-decoration-none" href="{{ route('categories.index') }}">View All</a> --}} 
                    </div>

                    @foreach($categories as $category)
                        <div class="position-relative overflow-hidden mb-3" style="height: 80px;">
                            <img class="img-fluid w-100 h-100" src="{{ asset('storage/' . $category->image) }}" style="object-fit: cover;">
                            {{-- <a href="{{ route('categories.show', $category->slug) }}" class="overlay align-items-center justify-content-center h4 m-0 text-white text-decoration-none">
                                {{ $category->name }}
                            </a> --}}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>  
    <!-- Main News Section End -->

    <!-- Featured Articles Section Start -->
    <div class="container-fluid py-3">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                <h3 class="m-0">Featured Articles</h3>
                <a class="text-secondary font-weight-medium text-decoration-none" href="{{ route('articles.index') }}">View All</a>
            </div>
            <div class="owl-carousel owl-carousel-2 carousel-item-4 position-relative">
                @foreach($featuredArticles as $article)
                    <div class="position-relative overflow-hidden" style="height: 300px;">
                        <img class="img-fluid w-100 h-100" src="{{ asset('storage/' . $article->cover_image) }}" style="object-fit: cover;">
                        <div class="overlay">
                            <div class="mb-1" style="font-size: 13px;">
                                {{-- <a class="text-white" href="#">{{ $article->category->name ?? 'Uncategorized' }}</a> --}}
                                <span class="px-1 text-white">/</span>
                                <a class="text-white" href="#">{{ $article->created_at->format('M d, Y') }}</a>
                            </div>
                            <a class="h4 m-0 text-white" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Featured Articles Section End -->

    <!-- Category-wise Articles Start -->
    <div class="container-fluid">
        <div class="container">
            <div class="row">
             {{--    @foreach($categories as $category)
                    <div class="col-lg-6 py-3">
                        <div class="bg-light py-2 px-4 mb-3">
                            <h3 class="m-0">{{ $category->name }}</h3>
                        </div>
                        <div class="owl-carousel owl-carousel-3 carousel-item-2 position-relative">
                            @foreach($category->articles as $article)
                                <div class="position-relative">
                                    <img class="img-fluid w-100" src="{{ asset('storage/' . $article->cover_image) }}" style="object-fit: cover;">
                                    <div class="overlay position-relative bg-light">
                                        <div class="mb-2" style="font-size: 13px;">
                                             <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a> 
                                            <span class="px-1">/</span>
                                            <span>{{ $article->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <a class="h4 m-0" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach --}}
            </div>
        </div>
    </div>
    <!-- Category-wise Articles End -->
@endsection
