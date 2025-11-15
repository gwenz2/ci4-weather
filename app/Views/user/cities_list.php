<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities - Weather Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php echo view('partials/user_navbar', ['active_page' => 'cities']); ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <h2><i class="bi bi-geo-alt-fill"></i> Browse Cities</h2>
        <p class="text-muted">Add cities to your favorites to see weather forecasts on your dashboard. (Maximum 2 favorites)</p>

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
        
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>City Name</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($cities)): ?>
                    <tr>
                        <td colspan="3" class="text-center">No cities available. Contact admin to add cities.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cities as $city): ?>
                        <tr>
                            <td><i class="bi bi-geo-alt"></i> <?= $city['name'] ?></td>
                            <td><?= $city['country'] ?></td>
                            <td>
                                <?php if ($city['is_favorite']): ?>
                                    <a href="<?= base_url('user/favorites/remove/'.$city['id']) ?>" class="btn btn-sm btn-danger">
                                        <i class="bi bi-star-fill"></i> Remove from Favorites
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('user/favorites/add/'.$city['id']) ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-star"></i> Add to Favorites
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
