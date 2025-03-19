<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Gestion des utilisateurs<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Gestion des utilisateurs</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Ajouter un utilisateur
            </button>
        </div>
        <div class="card-body">
            <!-- Filtres -->
            <form action="/admin/users" method="get" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les rôles</option>
                        <option value="admin" <?= (isset($role) && $role === 'admin') ? 'selected' : '' ?>>Administrateur</option>
                        <option value="author" <?= (isset($role) && $role === 'author') ? 'selected' : '' ?>>Auteur</option>
                        <option value="user" <?= (isset($role) && $role === 'user') ? 'selected' : '' ?>>Utilisateur</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tous les statuts</option>
                        <option value="active" <?= (isset($status) && $status === 'active') ? 'selected' : '' ?>>Actif</option>
                        <option value="inactive" <?= (isset($status) && $status === 'inactive') ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rechercher</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par nom d'utilisateur ou email..." 
                               value="<?= esc($search ?? '') ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Liste des utilisateurs -->
            <?php if (empty($users)): ?>
                <div class="alert alert-info">
                    Aucun utilisateur trouvé.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= esc($user['username']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <?php
                                        $roleClass = [
                                            'admin' => 'danger',
                                            'author' => 'primary',
                                            'user' => 'secondary'
                                        ];
                                        $roleText = [
                                            'admin' => 'Administrateur',
                                            'author' => 'Auteur',
                                            'user' => 'Utilisateur'
                                        ];
                                        ?>
                                        <span class="badge bg-<?= $roleClass[$user['role']] ?>">
                                            <?= $roleText[$user['role']] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>">
                                            <?= $user['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    onclick="editUser(<?= $user['id'] ?>, '<?= esc($user['username']) ?>', 
                                                    '<?= esc($user['email']) ?>', '<?= $user['role'] ?>', 
                                                    '<?= $user['status'] ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($user['id'] !== session()->get('user_id')): ?>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteUser(<?= $user['id'] ?>, '<?= esc($user['username']) ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($pager->getPageCount() > 1): ?>
                    <nav aria-label="Navigation des pages">
                        <?= $pager->links() ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal d'ajout d'utilisateur -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/users/create" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user">Utilisateur</option>
                            <option value="author">Auteur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de modification d'utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nouveau mot de passe (optionnel)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                        <div class="form-text">Laissez vide pour conserver le mot de passe actuel</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Rôle</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="user">Utilisateur</option>
                            <option value="author">Auteur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status_active" value="active" checked>
                            <label class="form-check-label" for="status_active">Actif</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive">
                            <label class="form-check-label" for="status_inactive">Inactif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
const editUserForm = document.getElementById('editUserForm');
const deleteForm = document.getElementById('deleteForm');

function editUser(id, username, email, role, status) {
    editUserForm.action = `/admin/users/update/${id}`;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_role').value = role;
    document.getElementById(`status_${status}`).checked = true;
    editUserModal.show();
}

function deleteUser(id, username) {
    deleteMessage.textContent = `Êtes-vous sûr de vouloir supprimer l'utilisateur "${username}" ? Cette action est irréversible.`;
    deleteForm.action = `/admin/users/delete/${id}`;
    deleteModal.show();
}
</script>
<?= $this->endSection() ?> 