<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities Management - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php echo view('partials/admin_navbar', ['active_page' => 'cities']); ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-geo-alt-fill"></i> Cities Management</h2>
                <p class="text-muted">Manage all cities available in the system</p>
            </div>
            <a href="<?= base_url('admin/cities/create') ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Add New City
            </a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>City Name</th>
                                <th>Country</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($cities)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No cities found. Add your first city!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($cities as $city): ?>
                                <tr>
                                    <td><?= $city['id'] ?></td>
                                    <td><i class="bi bi-geo-alt"></i> <?= $city['name'] ?></td>
                                    <td><?= $city['country'] ?></td>
                                    <td><?= isset($city['created_at']) ? date('M d, Y', strtotime($city['created_at'])) : 'N/A' ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/cities/edit/'.$city['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="<?= base_url('admin/cities/delete/'.$city['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this city?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
