<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($article['title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <!-- Contenu de l'article -->
        <div class="col-md-8">
            <article class="card mb-4">
                <div class="card-body">
                    <!-- En-tête de l'article -->
                    <header class="mb-4">
                        <h1 class="card-title"><?= esc($article['title']) ?></h1>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <div class="me-3">
                                <i class="fas fa-user"></i> <?= esc($article['author_name']) ?>
                            </div>
                            <div class="me-3">
                                <i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                            </div>
                            <div class="me-3">
                                <i class="fas fa-eye"></i> <?= $article['views'] ?> vues
                            </div>
                            <div>
                                <i class="fas fa-folder"></i> <?= esc($article['category']) ?>
                            </div>
                        </div>
                    </header>

                    <!-- Contenu de l'article -->
                    <div class="article-content mb-4">
                        <?= $article['content'] ?>
                    </div>

                    <!-- Tags et partage -->
                    <footer class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="tags">
                                <?php foreach ($article['tags'] as $tag): ?>
                                    <a href="/articles/tag/<?= urlencode($tag) ?>" class="badge bg-secondary text-decoration-none me-2">
                                        <?= esc($tag) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="share-buttons">
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="shareOnFacebook()">
                                    <i class="fab fa-facebook-f"></i> Partager
                                </button>
                                <button class="btn btn-sm btn-outline-info me-2" onclick="shareOnTwitter()">
                                    <i class="fab fa-twitter"></i> Partager
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="shareOnLinkedIn()">
                                    <i class="fab fa-linkedin-in"></i> Partager
                                </button>
                            </div>
                        </div>
                    </footer>
                </div>
            </article>

            <!-- Section des commentaires -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Commentaires (<?= count($comments) ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <!-- Formulaire de commentaire -->
                        <form action="/comments/create" method="post" class="mb-4">
                            <?= csrf_field() ?>
                            <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="3" 
                                          placeholder="Écrivez votre commentaire..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Publier</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <a href="/login" class="alert-link">Connectez-vous</a> pour laisser un commentaire.
                        </div>
                    <?php endif; ?>

                    <!-- Liste des commentaires -->
                    <?php if (empty($comments)): ?>
                        <p class="text-muted">Aucun commentaire pour le moment.</p>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($comments as $comment): ?>
                                <div class="comment mb-4" id="comment-<?= $comment['id'] ?>">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0"><?= esc($comment['username']) ?></h6>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                                </small>
                                            </div>
                                            <p class="mb-2"><?= nl2br(esc($comment['content'])) ?></p>
                                            <?php if (session()->get('isLoggedIn')): ?>
                                                <button type="button" class="btn btn-sm btn-link reply-btn" 
                                                        data-comment-id="<?= $comment['id'] ?>">
                                                    <i class="fas fa-reply"></i> Répondre
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Réponses aux commentaires -->
                                    <?php if (!empty($comment['replies'])): ?>
                                        <div class="replies ms-5 mt-3">
                                            <?php foreach ($comment['replies'] as $reply): ?>
                                                <div class="reply mb-3">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-user-circle fa-2x text-muted"></i>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <h6 class="mb-0"><?= esc($reply['username']) ?></h6>
                                                                <small class="text-muted">
                                                                    <?= date('d/m/Y H:i', strtotime($reply['created_at'])) ?>
                                                                </small>
                                                            </div>
                                                            <p class="mb-0"><?= nl2br(esc($reply['content'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Articles similaires -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Articles similaires</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($related_articles)): ?>
                        <p class="text-muted">Aucun article similaire</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($related_articles as $related): ?>
                                <a href="/articles/<?= $related['slug'] ?>" class="list-group-item list-group-item-action">
                                    <h6 class="mb-1"><?= esc($related['title']) ?></h6>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($related['created_at'])) ?>
                                    </small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Catégories -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Catégories</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($categories)): ?>
                        <p class="text-muted">Aucune catégorie</p>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($categories as $category): ?>
                                <a href="/articles/category/<?= urlencode($category['name']) ?>" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <?= esc($category['name']) ?>
                                    <span class="badge bg-primary rounded-pill"><?= $category['count'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tags populaires -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tags populaires</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($popular_tags)): ?>
                        <p class="text-muted">Aucun tag populaire</p>
                    <?php else: ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($popular_tags as $tag): ?>
                                <a href="/articles/tag/<?= urlencode($tag['name']) ?>" 
                                   class="badge bg-secondary text-decoration-none">
                                    <?= esc($tag['name']) ?>
                                    <span class="ms-1"><?= $tag['count'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de réponse -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Répondre au commentaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/comments/reply" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                <input type="hidden" name="parent_id" id="reply_parent_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <textarea class="form-control" name="content" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Répondre</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const replyModal = new bootstrap.Modal(document.getElementById('replyModal'));

    // Gestion des réponses aux commentaires
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            document.getElementById('reply_parent_id').value = commentId;
            replyModal.show();
        });
    });
});

// Fonctions de partage
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent(document.title);
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank');
}

function shareOnLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.linkedin.com/shareArticle?mini=true&url=${url}`, '_blank');
}
</script>
<?= $this->endSection() ?> 