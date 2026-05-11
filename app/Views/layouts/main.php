<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title><?= isset($title) ? $title . ' | ' : '' ?>Hospital Queue System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="app-wrapper">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-hospital"></i></div>
            <div>
                <h5>MediQueue</h5>
                <small>Hospital Queue System</small>
            </div>
        </div>
        <div class="sidebar-nav">
            <?php if (session()->get('role') === 'admin'): ?>
            <div class="nav-section">Main</div>
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="<?= base_url('queue') ?>" class="nav-link <?= uri_string() == 'queue' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> Queue Management
            </a>
            <div class="nav-section">Management</div>
            <a href="<?= base_url('departments') ?>" class="nav-link <?= uri_string() == 'departments' ? 'active' : '' ?>">
                <i class="bi bi-building"></i> Departments
            </a>
            <a href="<?= base_url('services') ?>" class="nav-link <?= uri_string() == 'services' ? 'active' : '' ?>">
                <i class="bi bi-gear-fill"></i> Services
            </a>
            <a href="<?= base_url('counters') ?>" class="nav-link <?= uri_string() == 'counters' ? 'active' : '' ?>">
                <i class="bi bi-window-stack"></i> Counters
            </a>
            <a href="<?= base_url('users') ?>" class="nav-link <?= uri_string() == 'users' ? 'active' : '' ?>">
                <i class="bi bi-person-badge-fill"></i> Users
            </a>
            <div class="nav-section">Analytics</div>
            <a href="<?= base_url('reports') ?>" class="nav-link <?= uri_string() == 'reports' ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-fill"></i> Reports
            </a>
            <?php else: ?>
            <div class="nav-section">Main</div>
            <a href="<?= base_url('queue') ?>" class="nav-link <?= uri_string() == 'queue' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> Queue Management
            </a>
            <?php endif; ?>
            <div class="nav-section">Public</div>
            <a href="<?= base_url('kiosk') ?>" class="nav-link" target="_blank">
                <i class="bi bi-tablet-fill"></i> Kiosk
            </a>
            <a href="<?= base_url('display') ?>" class="nav-link" target="_blank">
                <i class="bi bi-tv-fill"></i> Display Board
            </a>
        </div>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar"><?= strtoupper(substr(session()->get('full_name') ?? 'U', 0, 1)) ?></div>
                <div style="flex:1">
                    <div class="user-name"><?= esc(session()->get('full_name') ?? 'User') ?></div>
                    <div class="user-role"><?= ucfirst(session()->get('role') ?? 'staff') ?></div>
                </div>
                <a href="<?= base_url('logout') ?>" class="text-danger" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar">
            <div>
                <h4><?= $title ?? 'Dashboard' ?></h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:13px"><i class="bi bi-calendar3 me-1"></i><?= date('F j, Y') ?></span>
                <span class="text-muted" style="font-size:13px" id="live-clock"><i class="bi bi-clock me-1"></i><?= date('h:i A') ?></span>
            </div>
        </div>
        <div class="page-content fade-in">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<script>
// Live clock
setInterval(function() {
    const now = new Date();
    const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    $('#live-clock').html('<i class="bi bi-clock me-1"></i>' + time);
}, 1000);
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
