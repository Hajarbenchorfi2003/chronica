<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Nouvel article<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
<style>
    .editor-toolbar {
        border: 1px solid #dee2e6;
        border-bottom: none;
    }
    .CodeMirror {
        border: 1px solid #dee2e6;
        border-top: none;
        min-height: 400px;
    }
    .preview-image {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Nouvel article</h5>
                </div>
                <div class="card-body">
                    <form action="/articles/create" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <!-- Titre -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" 
                                   id="title" name="title" value="<?= old('title') ?>" required>
                            <?php if (session('errors.title')): ?>
                                <div class="invalid-feedback"><?= session('errors.title') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select <?= session('errors.category') ? 'is-invalid' : '' ?>" 
                                    id="category" name="category" required>
                                <option value="">Sélectionner une catégorie</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= old('category') == $category['id'] ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.category')): ?>
                                <div class="invalid-feedback"><?= session('errors.category') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Image de couverture -->
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Image de couverture</label>
                            <input type="file" class="form-control <?= session('errors.cover_image') ? 'is-invalid' : '' ?>" 
                                   id="cover_image" name="cover_image" accept="image/*">
                            <div class="form-text">
                                Format recommandé : JPG, PNG. Taille maximale : 2MB. Dimensions recommandées : 1200x630px
                            </div>
                            <?php if (session('errors.cover_image')): ?>
                                <div class="invalid-feedback"><?= session('errors.cover_image') ?></div>
                            <?php endif; ?>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control <?= session('errors.tags') ? 'is-invalid' : '' ?>" 
                                   id="tags" name="tags" value="<?= old('tags') ?>" 
                                   placeholder="Séparés par des virgules (ex: technologie, web, design)">
                            <?php if (session('errors.tags')): ?>
                                <div class="invalid-feedback"><?= session('errors.tags') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Contenu -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Contenu</label>
                            <textarea id="content" name="content"><?= old('content') ?></textarea>
                            <?php if (session('errors.content')): ?>
                                <div class="invalid-feedback"><?= session('errors.content') ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Statut -->
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_draft" 
                                       value="draft" <?= old('status', 'draft') === 'draft' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_draft">Brouillon</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status_published" 
                                       value="published" <?= old('status') === 'published' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_published">Publier</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/articles" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Aide à l'édition -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aide à l'édition</h5>
                </div>
                <div class="card-body">
                    <h6>Syntaxe Markdown</h6>
                    <ul class="list-unstyled">
                        <li><code>#</code> Titre 1</li>
                        <li><code>##</code> Titre 2</li>
                        <li><code>**texte**</code> Gras</li>
                        <li><code>*texte*</code> Italique</li>
                        <li><code>[lien](url)</code> Lien</li>
                        <li><code>![alt](image.jpg)</code> Image</li>
                        <li><code>></code> Citation</li>
                        <li><code>-</code> Liste</li>
                        <li><code>1.</code> Liste numérotée</li>
                        <li><code>```</code> Code</li>
                    </ul>
                </div>
            </div>

            <!-- Prévisualisation -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Prévisualisation</h5>
                </div>
                <div class="card-body">
                    <div id="preview"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
<script>
// Initialisation de l'éditeur Markdown
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
    imageUploadEndpoint: '/articles/upload-image',
    imageMaxSize: 2 * 1024 * 1024, // 2MB
    imageAccept: 'image/png, image/jpeg, image/gif',
    previewRender: function(plainText, preview) {
        preview.innerHTML = marked.parse(plainText);
    }
});

// Prévisualisation de l'image de couverture
document.getElementById('cover_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${e.target.result}" class="preview-image">`;
        }
        reader.readAsDataURL(file);
    }
});

// Validation du formulaire
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const category = document.getElementById('category').value;
    const content = easyMDE.value().trim();

    if (!title) {
        e.preventDefault();
        alert('Le titre est requis');
        return;
    }

    if (!category) {
        e.preventDefault();
        alert('La catégorie est requise');
        return;
    }

    if (!content) {
        e.preventDefault();
        alert('Le contenu est requis');
        return;
    }
});
</script>
<?= $this->endSection() ?> 