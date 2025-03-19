<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Connexion<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Connexion</h4>
            </div>
            <div class="card-body">
            <form action="/login" method="post" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= old('email') ?>" required>
                        <?php if (session('errors.email')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                               id="password" name="password" required>
                        <?php if (session('errors.password')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Pas encore de compte ? <a href="/register" class="text-decoration-none">Inscrivez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 