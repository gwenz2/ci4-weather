<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Weather Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php echo view('partials/admin_navbar', ['active_page' => 'dashboard']); ?>

    <div class="container mt-4">
        <div class="mb-4">
            <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
            <p class="text-muted">Welcome back, <?= session()->get('username') ?>! Here's an overview of the system.</p>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Total Cities -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0">Total Cities</h6>
                                <h2 class="mb-0"><?= $total_cities ?></h2>
                            </div>
                            <div>
                                <i class="bi bi-geo-alt-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?= base_url('admin/cities') ?>" class="text-white text-decoration-none">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0">Total Users</h6>
                                <h2 class="mb-0"><?= $total_users ?></h2>
                            </div>
                            <div>
                                <i class="bi bi-people-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?= base_url('admin/users') ?>" class="text-white text-decoration-none">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0">Total Admins</h6>
                                <h2 class="mb-0"><?= $total_admins ?></h2>
                            </div>
                            <div>
                                <i class="bi bi-shield-fill-check" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?= base_url('admin/users') ?>" class="text-white text-decoration-none">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Total Favorites -->
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-0">Total Favorites</h6>
                                <h2 class="mb-0"><?= $total_favorites ?></h2>
                            </div>
                            <div>
                                <i class="bi bi-star-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <span class="text-white">Across all users</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-grid">
                                    <a href="<?= base_url('admin/cities') ?>" class="btn btn-outline-success btn-lg">
                                        <i class="bi bi-list-ul"></i> Manage Cities
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-grid">
                                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-info btn-lg">
                                        <i class="bi bi-people"></i> Manage Users
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-info-circle"></i> System Information</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Role:</strong> Administrator</li>
                            <li><strong>Username:</strong> <?= session()->get('username') ?></li>
                            <li><strong>Last Login:</strong> <?= date('F d, Y h:i A') ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
