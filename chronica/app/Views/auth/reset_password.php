<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Réinitialiser le mot de passe<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Réinitialiser le mot de passe</h4>
            </div>
            <div class="card-body">
                <form action="/reset-password" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                               id="password" name="password" required>
                        <?php if (session('errors.password')): ?>
                            <div class="invalid-feedback"><?= session('errors.password') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control <?= session('errors.password_confirm') ? 'is-invalid' : '' ?>" 
                               id="password_confirm" name="password_confirm" required>
                        <?php if (session('errors.password_confirm')): ?>
                            <div class="invalid-feedback"><?= session('errors.password_confirm') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Vous vous souvenez de votre mot de passe ? <a href="/login" class="text-decoration-none">Connectez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 