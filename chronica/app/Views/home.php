<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $title ?? 'Accueil' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <!-- Articles récents -->
        <div class="col-lg-8">
            <h2 class="mb-4">Articles récents</h2>
            
            <?php if (empty($articles)) : ?>
                <div class="alert alert-info">
                    Aucun article n'a été publié pour le moment.
                </div>
            <?php else : ?>
                <?php foreach ($articles as $article) : ?>
                    <div class="card mb-4">
                        <?php if ($article['cover_image']) : ?>
                            <img src="<?= base_url('uploads/articles/' . $article['cover_image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= esc($article['title']) ?>">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h3 class="card-title">
                                <a href="<?= base_url('articles/' . $article['slug']) ?>" 
                                   class="text-decoration-none">
                                    <?= esc($article['title']) ?>
                                </a>
                            </h3>
                            
                            <div class="text-muted mb-2">
                                <small>
                                    Par <?= esc($article['author_name']) ?> | 
                                    <?= date('d/m/Y', strtotime($article['created_at'])) ?> |
                                    <?= $article['views'] ?> vues |
                                    <?= $article['comment_count'] ?> commentaires
                                </small>
                            </div>
                            
                            <p class="card-text">
                                <?= esc($article['excerpt']) ?>
                            </p>
                            
                            <a href="<?= base_url('articles/' . $article['slug']) ?>" 
                               class="btn btn-primary">
                                Lire la suite
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?= $pager->links('articles', 'bootstrap_pager') ?>
            <?php endif; ?>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Catégories -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Catégories</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($categories)) : ?>
                        <p class="text-muted">Aucune catégorie disponible.</p>
                    <?php else : ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($categories as $category) : ?>
                                <li class="mb-2">
                                    <a href="<?= base_url('categories/' . $category['slug']) ?>" 
                                       class="text-decoration-none">
                                        <?= esc($category['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- À propos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">À propos</h5>
                </div>
                <div class="card-body">
                    <p>
                        Bienvenue sur notre blog ! Nous partageons des articles sur divers sujets 
                        intéressants. N'hésitez pas à explorer nos catégories et à laisser des commentaires.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 