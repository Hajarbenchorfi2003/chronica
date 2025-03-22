@extends('layouts.main')

@section('title', 'Tous les articles')

@section('content')

 <!-- Featured Articles -->
 <div class="owl-carousel owl-carousel-2 carousel-item-1 position-relative mb-3 mb-lg-0">
    @foreach($articles as $article)
        <div class="position-relative overflow-hidden" style="height: 435px;">
             <img class="img-fluid h-100" src="{{ asset( $article->cover_image) }}" style="object-fit: cover;">
             
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
</div

@endsection