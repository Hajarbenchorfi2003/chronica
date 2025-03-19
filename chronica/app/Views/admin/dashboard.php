<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Tableau de bord<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <!-- Statistiques générales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Articles</h6>
                            <h2 class="mt-2 mb-0"><?= $totalArticles ?></h2>
                            <small>dont <?= $publishedArticles ?> publiés</small>
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
                            <h2 class="mt-2 mb-0"><?= $totalComments ?></h2>
                            <small>dont <?= $pendingComments ?> en attente</small>
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
                            <h2 class="mt-2 mb-0"><?= $totalUsers ?></h2>
                            <small>dont <?= $activeUsers ?> actifs</small>
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
                            <h2 class="mt-2 mb-0"><?= $totalCategories ?></h2>
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

    <div class="row">
        <!-- Articles récents -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Articles récents</h5>
                    <a href="/admin/articles" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentArticles as $article): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="/articles/<?= $article['slug'] ?>" class="text-decoration-none">
                                                <?= esc($article['title']) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            Par <?= esc($article['author']) ?> • 
                                            <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?= $article['status'] === 'published' ? 'success' : 'warning' ?>">
                                        <?= $article['status'] === 'published' ? 'Publié' : 'Brouillon' ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commentaires récents -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Commentaires récents</h5>
                    <a href="/admin/comments" class="btn btn-sm btn-primary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentComments as $comment): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="/articles/<?= $comment['article_slug'] ?>" class="text-decoration-none">
                                                <?= esc($comment['article_title']) ?>
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            Par <?= esc($comment['author']) ?> • 
                                            <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                        </small>
                                    </div>
                                    <span class="badge bg-<?= $comment['status'] === 'approved' ? 'success' : 
                                        ($comment['status'] === 'rejected' ? 'danger' : 'warning') ?>">
                                        <?= $comment['status'] === 'approved' ? 'Approuvé' : 
                                            ($comment['status'] === 'rejected' ? 'Rejeté' : 'En attente') ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques par catégorie -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Articles par catégorie</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($categoryStats as $category): ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span><?= esc($category['name']) ?></span>
                                <span><?= $category['count'] ?> articles</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?= ($category['count'] / $totalArticles) * 100 ?>%"
                                     aria-valuenow="<?= $category['count'] ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="<?= $totalArticles ?>">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Activité récente -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">Activité récente</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentActivity as $activity): ?>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-<?= $activity['icon'] ?> text-<?= $activity['color'] ?>"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1"><?= esc($activity['description']) ?></p>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($activity['created_at'])) ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 