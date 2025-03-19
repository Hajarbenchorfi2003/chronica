<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Mon profil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <h4><?= esc($user['username']) ?></h4>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 
                            ($user['role'] === 'author' ? 'primary' : 'secondary') ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <p class="mb-0"><?= esc($user['email']) ?></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date d'inscription</label>
                        <p class="mb-0"><?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
                    </div>
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit"></i> Modifier le profil
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Mes statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="stat-circle bg-primary text-white">
                                <i class="fas fa-newspaper fa-2x"></i>
                            </div>
                            <h3 class="mt-2"><?= $stats['total_articles'] ?></h3>
                            <p class="text-muted mb-0">Articles</p>
                        </div>
                        <div class="col-md-4 text-center mb-4">
                            <div class="stat-circle bg-success text-white">
                                <i class="fas fa-comments fa-2x"></i>
                            </div>
                            <h3 class="mt-2"><?= $stats['total_comments'] ?></h3>
                            <p class="text-muted mb-0">Commentaires</p>
                        </div>
                        <div class="col-md-4 text-center mb-4">
                            <div class="stat-circle bg-info text-white">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                            <h3 class="mt-2"><?= $stats['total_views'] ?></h3>
                            <p class="text-muted mb-0">Vues</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mes articles récents -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Mes articles récents</h5>
                    <a href="/articles/create" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Nouvel article
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_articles)): ?>
                        <p class="text-muted">Aucun article récent</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_articles as $article): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="/articles/<?= $article['slug'] ?>" class="text-decoration-none">
                                                    <?= esc($article['title']) ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <?= date('d/m/Y', strtotime($article['created_at'])) ?> • 
                                                <?= $article['views'] ?> vues
                                            </small>
                                        </div>
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
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mes commentaires récents -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Mes commentaires récents</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_comments)): ?>
                        <p class="text-muted">Aucun commentaire récent</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recent_comments as $comment): ?>
                                <div class="list-group-item">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="/articles/<?= $comment['article_slug'] ?>" class="text-decoration-none">
                                                <?= esc($comment['article_title']) ?>
                                            </a>
                                        </h6>
                                        <p class="mb-1 small"><?= nl2br(esc($comment['content'])) ?></p>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modification du profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/profile/update" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= esc($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= esc($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
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

<?= $this->section('styles') ?>
<style>
.avatar-circle {
    width: 100px;
    height: 100px;
    background-color: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.stat-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    let currentArticleId = null;

    // Gestion de la suppression d'article
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.articleId;
            const articleTitle = this.dataset.articleTitle;
            currentArticleId = articleId;
            
            document.getElementById('confirmMessage').textContent = 
                `Êtes-vous sûr de vouloir supprimer l'article "${articleTitle}" ?`;
            confirmModal.show();
        });
    });

    document.getElementById('confirmAction').addEventListener('click', function() {
        if (!currentArticleId) return;

        fetch(`/articles/delete/${currentArticleId}`, {
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

        confirmModal.hide();
    });

    // Validation du formulaire de modification du profil
    const form = document.querySelector('#editProfileModal form');
    form.addEventListener('submit', function(e) {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        if (newPassword && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
        }
    });
});
</script>
<?= $this->endSection() ?> 