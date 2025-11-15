<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php echo view('partials/admin_navbar', ['active_page' => 'users']); ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-fill-gear"></i> Edit User</h5>
                    </div>
                    <div class="card-body">
                        <?php if(session('errors')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong><i class="bi bi-exclamation-triangle"></i> Validation Errors:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach(session('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('admin/users/update/'.$user['id']) ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="bi bi-person"></i> Username
                                </label>
                                <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" id="username" name="username" 
                                       value="<?= old('username', $user['username']) ?>" required>
                                <?php if(session('errors.username')): ?>
                                    <div class="invalid-feedback"><?= session('errors.username') ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Email
                                </label>
                                <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" id="email" name="email" 
                                       value="<?= old('email', $user['email']) ?>" required>
                                <?php if(session('errors.email')): ?>
                                    <div class="invalid-feedback"><?= session('errors.email') ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <i class="bi bi-shield-check"></i> Role
                                </label>
                                <select class="form-control <?= session('errors.role') ? 'is-invalid' : '' ?>" id="role" name="role" required>
                                    <option value="user" <?= old('role', $user['role']) === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= old('role', $user['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <?php if(session('errors.role')): ?>
                                    <div class="invalid-feedback"><?= session('errors.role') ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-key"></i> New Password
                                </label>
                                <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" id="password" name="password" 
                                       placeholder="Leave blank to keep current password">
                                <small class="text-muted">Only fill this if you want to change the password</small>
                                <?php if(session('errors.password')): ?>
                                    <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
