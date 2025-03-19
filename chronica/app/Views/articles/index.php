<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Articles<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Liste des articles -->
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Articles</h2>
            <?php if (in_array(session()->get('role'), ['admin', 'author'])): ?>
                <a href="/articles/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel article
                </a>
            <?php endif; ?>
        </div>

        <?php if (empty($articles)): ?>
            <div class="alert alert-info">
                Aucun article n'a été publié pour le moment.
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <article class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h3 class="card-title">
                                <a href="/articles/<?= $article['slug'] ?>" class="text-decoration-none">
                                    <?= esc($article['title']) ?>
                                </a>
                            </h3>
                            <?php if (in_array(session()->get('role'), ['admin', 'author']) && 
                                    (session()->get('role') === 'admin' || $article['author_id'] == session()->get('user_id'))): ?>
                                <div class="dropdown">
                                    <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="/articles/edit/<?= $article['id'] ?>">
                                                <i class="fas fa-edit me-2"></i> Modifier
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="/articles/delete/<?= $article['id'] ?>" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                <i class="fas fa-trash me-2"></i> Supprimer
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-text text-muted mb-2">
                            <small>
                                Par <?= esc($article['author_name']) ?> | 
                                <?= date('d/m/Y', strtotime($article['created_at'])) ?> |
                                <?= esc($article['category']) ?>
                                <?php if ($article['status'] === 'draft'): ?>
                                    <span class="badge bg-warning">Brouillon</span>
                                <?php endif; ?>
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
        <!-- Recherche -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Rechercher</h5>
            </div>
            <div class="card-body">
                <form action="/articles/search" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Rechercher un article...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Catégories -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Catégories</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php foreach ($categories as $category): ?>
                        <li class="mb-2">
                            <a href="/articles/category/<?= urlencode($category) ?>" class="text-decoration-none">
                                <i class="fas fa-folder me-2"></i>
                                <?= esc($category) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Articles populaires -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Articles populaires</h5>
            </div>
            <div class="card-body">
                <?php if (empty($popularArticles)): ?>
                    <p class="text-muted mb-0">Aucun article populaire pour le moment.</p>
                <?php else: ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($popularArticles as $article): ?>
                            <li class="mb-3">
                                <a href="/articles/<?= $article['slug'] ?>" class="text-decoration-none">
                                    <?= esc($article['title']) ?>
                                </a>
                                <small class="text-muted d-block">
                                    <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                                </small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 