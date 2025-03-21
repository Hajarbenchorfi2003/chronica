@extends('layouts.main')

@section('title', $article->title)

@section('content')
<div class="container-fluid py-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- News Detail Start -->
                <div class="position-relative mb-3">
                    <img class="img-fluid w-100" src="{{ $article->image_url }}" style="object-fit: cover;">
                    <div class="overlay position-relative bg-light">
                        <div class="mb-3">
                            <a href="#">{{ $article->category }}</a>
                            <span class="px-1">/</span>
                            <span>{{ $article->created_at->format('F d, Y') }}</span>
                        </div>
                        <div>
                            <h3 class="mb-3">{{ $article->title }}</h3>
                            <p>{{ $article->content }}</p>
                            <h4 class="mb-3">{{ $article->subtitle }}</h4>
                            <img class="img-fluid w-50 float-left mr-4 mb-2" src="{{ $article->image_2 }}">
                            <p>{{ $article->additional_content }}</p>
                            <h5 class="mb-3">{{ $article->further_subtitle }}</h5>
                            <img class="img-fluid w-50 float-right ml-4 mb-2" src="{{ $article->image_3 }}">
                            <p>{{ $article->further_content }}</p>
                        </div>
                    </div>
                </div>
                <!-- News Detail End -->

                <!-- Comment List Start -->
                @foreach($article->comments as $comment)
                    <div class="bg-light mb-3" style="padding: 30px;">
                        <h3 class="mb-4">{{ count($article->comments) }} Comments</h3>
                        <div class="media mb-4">
                            <img src="{{ $comment->user->avatar }}" alt="Image" class="img-fluid mr-3 mt-1" style="width: 45px;">
                            <div class="media-body">
                                <h6><a href="#">{{ $comment->user->name }}</a> <small><i>{{ $comment->created_at->format('F d, Y') }}</i></small></h6>
                                <p>{{ $comment->content }}</p>
                                <button class="btn btn-sm btn-outline-secondary">Reply</button>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- Comment List End -->

                <!-- Comment Form Start -->
                <div class="bg-light mb-3" style="padding: 30px;">
                    <h3 class="mb-4">Leave a comment</h3>
                    <form action="{{ route('comments.store', $article->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" cols="30" rows="5" class="form-control" name="message"></textarea>
                        </div>
                        <div class="form-group mb-0">
                            <input type="submit" value="Leave a comment" class="btn btn-primary font-weight-semi-bold py-2 px-3">
                        </div>
                    </form>
                </div>
                <!-- Comment Form End -->
            </div>

            <div class="col-lg-4 pt-3 pt-lg-0">
                <!-- Tags Start -->
                <div class="pb-3">
                    <div class="bg-light py-2 px-4 mb-3">
                        <h3 class="m-0">Tags</h3>
                    </div>
                    <div class="d-flex flex-wrap m-n1">
                        @foreach($tags as $tag)
                            <a href="{{ route('tag.show', $tag->id) }}" class="btn btn-sm btn-outline-secondary m-1">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
                <!-- Tags End -->
            </div>
        </div>
    </div>
</div>
@endsection
