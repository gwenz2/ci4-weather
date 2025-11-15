<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Weather Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4 class="mb-0"><i class="bi bi-person-plus"></i> Weather Dashboard Sign Up</h4>
                    </div>
                    <div class="card-body">
                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
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
                        
                        <form action="<?= base_url('signup/register') ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label"><i class="bi bi-person"></i> Username</label>
                                <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= old('username') ?>" placeholder="Choose a username" required>
                                <?php if(session('errors.username')): ?>
                                    <div class="invalid-feedback"><?= session('errors.username') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope"></i> Email</label>
                                <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= old('email') ?>" placeholder="Enter your email address" required>
                                <?php if(session('errors.email')): ?>
                                    <div class="invalid-feedback"><?= session('errors.email') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="bi bi-lock"></i> Password</label>
                                <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Create a password (min 6 characters)" required>
                                <?php if(session('errors.password')): ?>
                                    <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label"><i class="bi bi-lock-fill"></i> Confirm Password</label>
                                <input type="password" class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                                <?php if(session('errors.confirm_password')): ?>
                                    <div class="invalid-feedback"><?= session('errors.confirm_password') ?></div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="role" value="user">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-person-check"></i> Sign Up
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            <p class="text-muted">Already have an account? <a href="<?= base_url('login') ?>">Login here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
