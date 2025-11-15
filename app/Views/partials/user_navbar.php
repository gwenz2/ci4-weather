<!-- User Navigation Bar -->
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
                    <a class="nav-link <?= (isset($active_page) && $active_page == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('user/dashboard') ?>">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= (isset($active_page) && $active_page == 'cities') ? 'active' : '' ?>" href="<?= base_url('user/cities') ?>">
                        <i class="bi bi-geo-alt"></i> Cities
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
