<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Gestion des articles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Gestion des articles</h3>
                    <a href="/articles/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouvel article
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">Tous les statuts</option>
                                <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Brouillons</option>
                                <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>Publiés</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">Toutes les catégories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= urlencode($category) ?>" <?= $currentCategory === $category ? 'selected' : '' ?>>
                                        <?= esc($category) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="authorFilter">
                                <option value="">Tous les auteurs</option>
                                <?php foreach ($authors as $author): ?>
                                    <option value="<?= $author['id'] ?>" <?= $authorId === $author['id'] ? 'selected' : '' ?>>
                                        <?= esc($author['username']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <form action="/admin/articles" method="get" class="d-flex">
                                <input type="text" class="form-control me-2" name="search" 
                                       placeholder="Rechercher un article..." 
                                       value="<?= esc($search) ?>">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Liste des articles -->
                    <?php if (empty($articles)): ?>
                        <div class="alert alert-info">
                            Aucun article trouvé.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Auteur</th>
                                        <th>Catégorie</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articles as $article): ?>
                                        <tr>
                                            <td><?= $article['id'] ?></td>
                                            <td>
                                                <a href="/articles/<?= $article['slug'] ?>" target="_blank">
                                                    <?= esc($article['title']) ?>
                                                </a>
                                            </td>
                                            <td><?= esc($article['author_name']) ?></td>
                                            <td><?= esc($article['category']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($article['created_at'])) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $article['status'] === 'published' ? 'success' : 'warning' ?>">
                                                    <?= $article['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="/articles/edit/<?= $article['id'] ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                            data-article-id="<?= $article['id'] ?>"
                                                            data-article-title="<?= esc($article['title']) ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?= $pager->links() ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmer</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    let currentArticleId = null;

    // Gestion des filtres
    document.getElementById('statusFilter').addEventListener('change', function() {
        window.location.href = `/admin/articles?status=${this.value}`;
    });

    document.getElementById('categoryFilter').addEventListener('change', function() {
        window.location.href = `/admin/articles?category=${encodeURIComponent(this.value)}`;
    });

    document.getElementById('authorFilter').addEventListener('change', function() {
        window.location.href = `/admin/articles?author_id=${this.value}`;
    });

    // Gestion de la suppression d'article
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.articleId;
            const articleTitle = this.dataset.articleTitle;
            currentArticleId = articleId;
            
            document.getElementById('confirmMessage').textContent = 
                `Êtes-vous sûr de vouloir supprimer l'article "${articleTitle}" ?`;
            modal.show();
        });
    });

    document.getElementById('confirmAction').addEventListener('click', function() {
        if (!currentArticleId) return;

        fetch(`/admin/articles/delete/${currentArticleId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Une erreur est survenue.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue.');
        });

        modal.hide();
    });
});
</script>
<?= $this->endSection() ?> 