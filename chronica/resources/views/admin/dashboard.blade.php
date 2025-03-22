@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container mt-4">
<!-- Bouton Ajouter Catégorie -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
    Ajouter une Catégorie
</button>
 
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

    <!-- Tableau des Catégories -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Slug</th>
                <th>description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editCategory({{ $category }})" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Modifier</button>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
<!-- MODAL AJOUTER CATÉGORIE -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Ajouter une Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nom de la Catégorie</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="categorySlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="categorySlug" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label for="categorydescription" class="form-label">description</label>
                        <input type="text" class="form-control" id="categorydescription" name="description" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL ÉDITER CATÉGORIE -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Modifier la Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Nom de la Catégorie</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategorySlug" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="editCategorySlug" name="slug" required>
                    </div>
                    <div class="mb-3">
                        <label for="categorydescription" class="form-label">description</label>
                        <input type="text" class="form-control" id="editCategorydescription" name="description" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Modifier</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editCategory(category) {
        document.getElementById('editCategoryId').value = category.id;
        document.getElementById('editCategoryName').value = category.name;
        document.getElementById('editCategorySlug').value = category.slug;
        document.getElementById('editCategorydescription').value = category.description;
        document.getElementById('editCategoryForm').action = '/categories/' + category.id;
    }
</script>
@endsection
 

