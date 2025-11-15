<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit City - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php echo view('partials/admin_navbar', ['active_page' => 'cities']); ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil"></i> Edit City</h4>
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

                        <form action="<?= base_url('admin/cities/update/'.$city['id']) ?>" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">City Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= old('name', $city['name']) ?>" required>
                                <?php if(session('errors.name')): ?>
                                    <div class="invalid-feedback"><?= session('errors.name') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= session('errors.country') ? 'is-invalid' : '' ?>" id="country" name="country" value="<?= old('country', $city['country']) ?>" required>
                                <?php if(session('errors.country')): ?>
                                    <div class="invalid-feedback"><?= session('errors.country') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                                <input type="number" step="0.00000001" class="form-control <?= session('errors.latitude') ? 'is-invalid' : '' ?>" id="latitude" name="latitude" value="<?= old('latitude', $city['latitude'] ?? '') ?>" required>
                                <small class="text-muted">Use <a href="https://www.latlong.net/" target="_blank">LatLong.net</a> to find coordinates</small>
                                <?php if(session('errors.latitude')): ?>
                                    <div class="invalid-feedback"><?= session('errors.latitude') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                                <input type="number" step="0.00000001" class="form-control <?= session('errors.longitude') ? 'is-invalid' : '' ?>" id="longitude" name="longitude" value="<?= old('longitude', $city['longitude'] ?? '') ?>" required>
                                <?php if(session('errors.longitude')): ?>
                                    <div class="invalid-feedback"><?= session('errors.longitude') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('admin/cities') ?>" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update City
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
