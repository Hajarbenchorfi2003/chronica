<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Gestion des commentaires<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Gestion des commentaires</h5>
        </div>
        <div class="card-body">
            <!-- Filtres -->
            <form action="/admin/comments" method="get" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="pending" <?= (isset($status) && $status === 'pending') ? 'selected' : '' ?>>En attente</option>
                        <option value="approved" <?= (isset($status) && $status === 'approved') ? 'selected' : '' ?>>Approuvé</option>
                        <option value="rejected" <?= (isset($status) && $status === 'rejected') ? 'selected' : '' ?>>Rejeté</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Article</label>
                    <select name="article_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les articles</option>
                        <?php foreach ($articles as $article): ?>
                            <option value="<?= $article['id'] ?>" 
                                    <?= (isset($article_id) && $article_id == $article['id']) ? 'selected' : '' ?>>
                                <?= esc($article['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Utilisateur</label>
                    <select name="user_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les utilisateurs</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" 
                                    <?= (isset($user_id) && $user_id == $user['id']) ? 'selected' : '' ?>>
                                <?= esc($user['username']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rechercher</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." 
                               value="<?= esc($search ?? '') ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Liste des commentaires -->
            <?php if (empty($comments)): ?>
                <div class="alert alert-info">
                    Aucun commentaire trouvé.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Article</th>
                                <th>Auteur</th>
                                <th>Contenu</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                                <tr>
                                    <td><?= $comment['id'] ?></td>
                                    <td>
                                        <a href="/articles/<?= $comment['article_slug'] ?>" class="text-decoration-none">
                                            <?= esc($comment['article_title']) ?>
                                        </a>
                                    </td>
                                    <td><?= esc($comment['username']) ?></td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;">
                                            <?= esc($comment['content']) ?>
                                        </div>
                                        <?php if (!empty($comment['parent_id'])): ?>
                                            <small class="text-muted">
                                                <i class="fas fa-reply"></i> Réponse à un commentaire
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger'
                                        ];
                                        $statusText = [
                                            'pending' => 'En attente',
                                            'approved' => 'Approuvé',
                                            'rejected' => 'Rejeté'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $statusClass[$comment['status']] ?>">
                                            <?= $statusText[$comment['status']] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($comment['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-sm btn-success" 
                                                        onclick="approveComment(<?= $comment['id'] ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="rejectComment(<?= $comment['id'] ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteComment(<?= $comment['id'] ?>)">
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
                <?php if ($pager->getPageCount() > 1): ?>
                    <nav aria-label="Navigation des pages">
                        <?= $pager->links() ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer l'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="confirmForm" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary" id="confirmButton"></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
const confirmForm = document.getElementById('confirmForm');
const confirmMessage = document.getElementById('confirmMessage');
const confirmButton = document.getElementById('confirmButton');

function approveComment(commentId) {
    confirmMessage.textContent = 'Êtes-vous sûr de vouloir approuver ce commentaire ?';
    confirmButton.textContent = 'Approuver';
    confirmButton.className = 'btn btn-success';
    confirmForm.action = `/admin/comments/approve/${commentId}`;
    confirmModal.show();
}

function rejectComment(commentId) {
    confirmMessage.textContent = 'Êtes-vous sûr de vouloir rejeter ce commentaire ?';
    confirmButton.textContent = 'Rejeter';
    confirmButton.className = 'btn btn-danger';
    confirmForm.action = `/admin/comments/reject/${commentId}`;
    confirmModal.show();
}

function deleteComment(commentId) {
    confirmMessage.textContent = 'Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.';
    confirmButton.textContent = 'Supprimer';
    confirmButton.className = 'btn btn-danger';
    confirmForm.action = `/admin/comments/delete/${commentId}`;
    confirmModal.show();
}
</script>
<?= $this->endSection() ?> 