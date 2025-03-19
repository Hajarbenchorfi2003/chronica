<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Mon profil<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Mon profil</h4>
                </div>
                <div class="card-body">
                    <form action="/profile" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                                   id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                            <?php if (session('errors.username')): ?>
                                <div class="invalid-feedback"><?= session('errors.username') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                            <?php if (session('errors.email')): ?>
                                <div class="invalid-feedback"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                   id="password" name="password">
                            <div class="form-text">Laissez vide pour conserver le mot de passe actuel</div>
                            <?php if (session('errors.password')): ?>
                                <div class="invalid-feedback"><?= session('errors.password') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control <?= session('errors.password_confirm') ? 'is-invalid' : '' ?>" 
                                   id="password_confirm" name="password_confirm">
                            <?php if (session('errors.password_confirm')): ?>
                                <div class="invalid-feedback"><?= session('errors.password_confirm') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Mettre à jour le profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 