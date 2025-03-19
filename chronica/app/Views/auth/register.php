<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Inscription<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Inscription</h4>
            </div>
            <div class="card-body">
                <form action="/register" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                               id="username" name="username" value="<?= old('username') ?>" required>
                        <?php if (session('errors.username')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.username') ?>
                            </div>
                        <?php endif; ?>
                    </div>

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

                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" 
                               id="password_confirm" name="password_confirm" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">S'inscrire</button>
                    </div>
                </form>

                <hr>

                <div class="text-center">
                    <p class="mb-0">Déjà un compte ? <a href="/login" class="text-decoration-none">Connectez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;

    if (password !== passwordConfirm) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas');
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 