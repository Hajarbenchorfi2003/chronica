@extends('layouts.main')

@section('title', 'Modifier l\'article')

@section('content')
    <div class="container">
        <h1>Modifier l'article</h1>
        <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                    <option value="">Sélectionner une catégorie</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cover_image" class="form-label">Image de couverture</label>
                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
                @error('cover_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags (facultatif)</label>
                <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $article->tags->pluck('id')->implode(',')) }}" placeholder="Séparés par des virgules">
            </div>

            <div class="mb-3">
                <label class="form-label">Statut</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="draft" {{ old('status', $article->status) === 'draft' ? 'checked' : '' }}>
                    <label class="form-check-label">Brouillon</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" value="published" {{ old('status', $article->status) === 'published' ? 'checked' : '' }}>
                    <label class="form-check-label">Publier</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
@endsection
