<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Weather Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php echo view('./partials/user_navbar', ['active_page' => 'dashboard']); ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Welcome, <?= session()->get('username') ?>!</h2>
                <p class="text-muted">View weather forecasts for your favorite cities.</p>
            </div>
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

        <!-- Weather for Favorite Cities -->
        <div class="row mt-4">
            <?php if (empty($favorites)): ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> You haven't added any favorite cities yet. 
                        <a href="<?= base_url('user/cities') ?>" class="alert-link">Browse cities</a> to add your favorites and see weather forecasts!
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($favorites as $city): ?>
                    <div class="col-md-12 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-geo-alt-fill"></i> <?= $city['name'] ?>, <?= $city['country'] ?>
                                </h5>
                                <a href="<?= base_url('user/favorites/remove/'.$city['id']) ?>" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Remove
                                </a>
                            </div>
                            <div class="card-body">
                                <?php if ($city['weather']): ?>
                                    <!-- Today's Weather -->
                                    <h6 class="text-primary mb-3"><i class="bi bi-calendar-check"></i> Today's Weather (<?= $today_date ?>)</h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Hour(s)</th>
                                                    <th>Condition</th>
                                                    <th>Temp (°C)</th>
                                                    <th>Precipitation (mm)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($city['weather']['today'] as $data): ?>
                                                    <tr<?= isset($data['is_current']) && $data['is_current'] ? ' style="background:#ffe082;"' : '' ?>>
                                                        <td><?= $data['time_range'] ?></td>
                                                        <td><b><?= $data['condition'] ?></b></td>
                                                        <td><?= number_format($data['temp'], 1) ?></td>
                                                        <td><?= number_format($data['precipitation'], 2) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Tomorrow's Weather -->
                                    <h6 class="text-info mb-3"><i class="bi bi-calendar2"></i> Tomorrow's Weather (<?= $tomorrow_date ?>)</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Hour(s)</th>
                                                    <th>Condition</th>
                                                    <th>Temp (°C)</th>
                                                    <th>Precipitation (mm)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($city['weather']['tomorrow'] as $data): ?>
                                                    <tr>
                                                        <td><?= $data['time_range'] ?></td>
                                                        <td><b><?= $data['condition'] ?></b></td>
                                                        <td><?= number_format($data['temp'], 1) ?></td>
                                                        <td><?= number_format($data['precipitation'], 2) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="small mb-0 mt-2 <?= $city['weather']['source'] === 'live' ? 'text-success' : 'text-info' ?>">
                                        <i class="bi bi-<?= $city['weather']['source'] === 'live' ? 'wifi' : ($city['weather']['source'] === 'cache' ? 'hdd' : 'exclamation-triangle') ?>"></i> 
                                        Weather data from Open-Meteo API 
                                        <strong>(<?= ucfirst(str_replace('-', ' ', $city['weather']['source'])) ?>)</strong>
                                        <?php if (isset($city['weather']['cached_time'])): ?>
                                            - Last updated: <?= $city['weather']['cached_time'] ?>
                                        <?php endif; ?>
                                    </p>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Weather data not available. City coordinates may be missing.
                                    </div>
                                <?php endif; ?>
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
