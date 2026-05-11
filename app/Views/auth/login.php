<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <i class="bi bi-hospital"></i>
        </div>
        <h3 class="text-center mb-1" style="font-weight: 800;">MediQueue</h3>
        <p class="text-center text-muted mb-4" style="font-size: 14px;">Hospital Queue Management System</p>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; font-size: 14px;">
            <i class="bi bi-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--dark); border-color: var(--card-border); color: var(--text-muted);"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control form-control-dark" placeholder="Enter username" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--dark); border-color: var(--card-border); color: var(--text-muted);"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control form-control-dark" placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-gradient-primary w-100 py-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>
        <p class="text-center text-muted mt-4" style="font-size: 12px;">
            <i class="bi bi-shield-check me-1"></i>Secure Hospital Staff Portal
        </p>
    </div>
</div>

<?= $this->endSection() ?>
