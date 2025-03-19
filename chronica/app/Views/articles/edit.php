<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Modifier l'article<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Markdown Editor CSS -->
<link href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Modifier l'article</h4>
            </div>
            <div class="card-body">
                <form action="/articles/edit/<?= $article['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" 
                               id="title" name="title" value="<?= old('title', $article['title']) ?>" required>
                        <?php if (session('errors.title')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.title') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Catégorie</label>
                        <select class="form-select <?= session('errors.category') ? 'is-invalid' : '' ?>" 
                                id="category" name="category" required>
                            <option value="">Sélectionner une catégorie</option>
                            <option value="Tech" <?= old('category', $article['category']) === 'Tech' ? 'selected' : '' ?>>Tech</option>
                            <option value="Lifestyle" <?= old('category', $article['category']) === 'Lifestyle' ? 'selected' : '' ?>>Lifestyle</option>
                            <option value="Actualités" <?= old('category', $article['category']) === 'Actualités' ? 'selected' : '' ?>>Actualités</option>
                            <option value="Sport" <?= old('category', $article['category']) === 'Sport' ? 'selected' : '' ?>>Sport</option>
                            <option value="Culture" <?= old('category', $article['category']) === 'Culture' ? 'selected' : '' ?>>Culture</option>
                            <option value="Autre" <?= old('category', $article['category']) === 'Autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                        <?php if (session('errors.category')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.category') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Contenu</label>
                        <textarea class="form-control <?= session('errors.content') ? 'is-invalid' : '' ?>" 
                                  id="content" name="content" rows="10" required><?= old('content', $article['content']) ?></textarea>
                        <?php if (session('errors.content')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.content') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" <?= old('status', $article['status']) === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                            <option value="published" <?= old('status', $article['status']) === 'published' ? 'selected' : '' ?>>Publié</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/articles/<?= $article['slug'] ?>" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Markdown Editor JS -->
<script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
<script>
const easyMDE = new EasyMDE({
    element: document.getElementById('content'),
    spellChecker: false,
    status: false,
    toolbar: [
        'bold', 'italic', 'heading', '|',
        'quote', 'unordered-list', 'ordered-list', '|',
        'link', 'image', '|',
        'preview', 'side-by-side', 'fullscreen', '|',
        'guide'
    ],
    uploadImage: true,
    imageUploadEndpoint: '/upload-image',
    imageMaxSize: 5 * 1024 * 1024, // 5MB
    imageAccept: 'image/png, image/jpeg, image/gif'
});
</script>
<?= $this->endSection() ?> 