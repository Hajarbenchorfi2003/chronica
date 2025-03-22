@extends('layouts.main')

@section('title', 'Category')

@section('content')

    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="container">
            <nav class="breadcrumb bg-transparent m-0 p-0">
                <a class="breadcrumb-item" href="#">Home</a>
                <a class="breadcrumb-item" href="#">Category</a>
                <span class="breadcrumb-item active">{{$category->name}}</span>
            </nav>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- News With Sidebar Start -->
    <div class="container-fluid py-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
                                <h3 class="m-0">{{$category->name}}</h3>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap">  
                            @foreach($featuredArticles as $article)
                                <div class="col-lg-6">
                                    <div class="position-relative mb-3">
                                        <img class="img-fluid w-100" src="{{ asset( $article->cover_image) }}" style="object-fit: cover;">
                                        <div class="overlay position-relative bg-light">
                                            <div class="mb-2" style="font-size: 14px;">
                                                <a href="#">{{ $article->category->name ?? 'Uncategorized' }}</a>
                                                <span class="px-1">/</span>
                                                <span>{{ $article->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <a class="h4" href="{{ route('articles.show', $article->slug) }}">{{ $article->title }}</a>
                                            <p class="m-0">{{ $article->excerpt }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="row">
                            <div class="col-12">
                                <nav aria-label="Page navigation">
                                    {{ $featuredArticles->links() }}
                                </nav>
                            </div>
                        </div>
                    </div>
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
                <div class="col-lg-4 pt-3 pt-lg-0">
                    <!-- Social Follow Start -->
                    <div class="pb-3">
                        <div class="bg-light py-2 px-4 mb-3">
                            <h3 class="m-0">Follow Us</h3>
                        </div>
                        <div class="d-flex mb-3">
                            <a href="" class="d-block w-50 py-2 px-3 text-white text-decoration-none mr-2" style="background: #39569E;">
                                <small class="fab fa-facebook-f mr-2"></small><small>12,345 Fans</small>
                            </a>
                            <a href="" class="d-block w-50 py-2 px-3 text-white text-decoration-none ml-2" style="background: #52AAF4;">
                                <small class="fab fa-twitter mr-2"></small><small>12,345 Followers</small>
                            </a>
                        </div>
                        <div class="d-flex mb-3">
                            <a href="" class="d-block w-50 py-2 px-3 text-white text-decoration-none mr-2" style="background: #0185AE;">
                                <small class="fab fa-linkedin-in mr-2"></small><small>12,345 Connects</small>
                            </a>
                            <a href="" class="d-block w-50 py-2 px-3 text-white text-decoration-none ml-2" style="background: #C8359D;">
                                <small class="fab fa-instagram mr-2"></small><small>12,345 Followers</small>
                            </a>
                        </div>
                        <div class="d-flex mb-3">
                            <a href="" class="d-block w-50 py-2 px-3 text-white text-decoration-none mr-2" style="background: #DC472E;">
                                <small class="fab fa-youtube mr-2"></small><small>12,345 Subscribers</small>
                            </a>
                            <a href="" class="d-block w-50 py-2 px-3 text-white text-decoration-none ml-2" style="background: #1AB7EA;">
                                <small class="fab fa-vimeo-v mr-2"></small><small>12,345 Followers</small>
                            </a>
                        </div>
                    </div>
                    <!-- Social Follow End -->

                    <!-- Tags Start -->
                    <div class="pb-3">
                        <div class="bg-light py-2 px-4 mb-3">
                            <h3 class="m-0">Tags</h3>
                        </div>
                        <div class="d-flex flex-wrap">
                            @foreach($tags as $tag)
                                <a href="{{ route('articles.byTag', $tag->slug) }}" class="btn btn-sm btn-outline-secondary m-1">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <!-- Tags End -->
                </div>
            </div>
        </div>
    </div>
    <!-- News With Sidebar End -->

@endsection
