<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Favorites - Weather Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('user/dashboard') ?>">
                <i class="bi bi-cloud-sun"></i> Weather Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('user/dashboard') ?>">
                            <i class="bi bi-house-door"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('user/cities') ?>">
                            <i class="bi bi-geo-alt"></i> Cities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('user/favorites') ?>">
                            <i class="bi bi-star"></i> Favorites
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2><i class="bi bi-star-fill text-warning"></i> My Favorite Cities</h2>
        <p class="text-muted">Quick access to your favorite cities for weather updates.</p>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <div class="row mt-4">
            <?php if (empty($favorites)): ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> You haven't added any favorites yet. 
                        <a href="<?= base_url('user/cities') ?>" class="alert-link">Browse cities</a> to add some!
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($favorites as $favorite): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill"></i> <?= $favorite['name'] ?>
                                </h5>
                                <p class="card-text"><?= $favorite['country'] ?></p>
                                <a href="<?= base_url('user/weather/'.$favorite['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-cloud-sun"></i> View Weather
                                </a>
                                <a href="<?= base_url('user/favorites/remove/'.$favorite['id']) ?>" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
