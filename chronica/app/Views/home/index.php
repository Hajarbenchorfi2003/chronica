<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Accueil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Articles récents -->
    <div class="col-md-8">
        <h2 class="mb-4">Articles récents</h2>
        <?php if (empty($articles)): ?>
            <div class="alert alert-info">
                Aucun article n'a été publié pour le moment.
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <article class="card mb-4">
                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="/articles/<?= $article['slug'] ?>" class="text-decoration-none">
                                <?= esc($article['title']) ?>
                            </a>
                        </h3>
                        <div class="card-text text-muted mb-2">
                            <small>
                                Par <?= esc($article['author_name']) ?> | 
                                <?= date('d/m/Y', strtotime($article['created_at'])) ?> |
                                <?= esc($article['category']) ?>
                            </small>
                        </div>
                        <p class="card-text">
                            <?= character_limiter(strip_tags($article['content']), 200) ?>
                        </p>
                        <a href="/articles/<?= $article['slug'] ?>" class="btn btn-primary">
                            Lire la suite
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Catégories -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Catégories</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php foreach ($categories as $category): ?>
                        <li class="mb-2">
                            <a href="/category/<?= urlencode($category) ?>" class="text-decoration-none">
                                <i class="fas fa-folder me-2"></i>
                                <?= esc($category) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Commentaires récents -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Commentaires récents</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentComments)): ?>
                    <p class="text-muted mb-0">Aucun commentaire récent.</p>
                <?php else: ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($recentComments as $comment): ?>
                            <li class="mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-comment text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <p class="mb-1">
                                            <?= esc($comment['content']) ?>
                                        </p>
                                        <small class="text-muted">
                                            Par <?= esc($comment['author_name']) ?> sur
                                            <a href="/articles/<?= url_title($comment['article_title'], '-', true) ?>" class="text-decoration-none">
                                                <?= esc($comment['article_title']) ?>
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- À propos -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">À propos de Chronica</h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    Chronica est une plateforme de blog moderne et interactive où vous pouvez partager vos idées,
                    connaissances et expériences avec le monde entier.
                </p>
                <a href="/about" class="btn btn-outline-primary">En savoir plus</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 