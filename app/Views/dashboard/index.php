<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="stat-card primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Tickets</div>
                    <div class="stat-value" id="stat-total"><?= $stats['total'] ?></div>
                </div>
                <div class="stat-icon primary"><i class="bi bi-ticket-perforated"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Waiting</div>
                    <div class="stat-value" id="stat-waiting"><?= $stats['waiting'] ?></div>
                </div>
                <div class="stat-icon warning"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card info">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Serving</div>
                    <div class="stat-value" id="stat-serving"><?= $stats['serving'] ?></div>
                </div>
                <div class="stat-icon info"><i class="bi bi-person-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Served</div>
                    <div class="stat-value" id="stat-served"><?= $stats['served'] ?></div>
                </div>
                <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Skipped</div>
                    <div class="stat-value" id="stat-skipped"><?= $stats['skipped'] ?></div>
                </div>
                <div class="stat-icon danger"><i class="bi bi-skip-forward"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Avg Wait</div>
                    <div class="stat-value" id="stat-avgwait"><?= $stats['avg_wait'] ?><small style="font-size:14px"> min</small></div>
                </div>
                <div class="stat-icon primary"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts + Department Stats -->
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-custom p-3">
            <h6 class="mb-3" style="font-weight: 700;"><i class="bi bi-bar-chart me-2"></i>Department Overview</h6>
            <canvas id="deptChart" height="280"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-custom p-3">
            <h6 class="mb-3" style="font-weight: 700;"><i class="bi bi-building me-2"></i>Today by Department</h6>
            <div class="table-responsive">
                <table class="table table-dark-custom mb-0">
                    <thead><tr><th>Department</th><th class="text-center">Total</th><th class="text-center">Waiting</th><th class="text-center">Served</th></tr></thead>
                    <tbody>
                    <?php if (!empty($deptStats)): ?>
                        <?php foreach ($deptStats as $ds): ?>
                        <tr>
                            <td>
                                <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:<?= $ds['color'] ?>;margin-right:8px;"></span>
                                <?= esc($ds['department_name']) ?>
                            </td>
                            <td class="text-center"><?= $ds['total'] ?></td>
                            <td class="text-center"><span class="badge-status badge-waiting"><?= $ds['waiting'] ?></span></td>
                            <td class="text-center"><span class="badge-status badge-served"><?= $ds['served'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">No tickets issued today</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
$(function() {
    // Load chart
    $.get('<?= base_url('dashboard/chart-data') ?>', function(data) {
        const ctx = document.getElementById('deptChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [
                    { label: 'Total', data: data.totals, backgroundColor: 'rgba(99,102,241,0.7)', borderRadius: 6 },
                    { label: 'Served', data: data.served, backgroundColor: 'rgba(16,185,129,0.7)', borderRadius: 6 },
                    { label: 'Waiting', data: data.waiting, backgroundColor: 'rgba(245,158,11,0.7)', borderRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { labels: { color: '#94a3b8', font: { family: 'Inter' } } } },
                scales: {
                    x: { ticks: { color: '#64748b' }, grid: { color: 'rgba(51,65,85,0.3)' } },
                    y: { ticks: { color: '#64748b' }, grid: { color: 'rgba(51,65,85,0.3)' }, beginAtZero: true }
                }
            }
        });
    });

    // Auto-refresh stats every 30s
    setInterval(function() {
        $.get('<?= base_url('dashboard/stats') ?>', function(data) {
            App.animateCounter('#stat-total', data.stats.total);
            App.animateCounter('#stat-waiting', data.stats.waiting);
            App.animateCounter('#stat-serving', data.stats.serving);
            App.animateCounter('#stat-served', data.stats.served);
            App.animateCounter('#stat-skipped', data.stats.skipped);
        });
    }, 30000);
});
</script>
<?= $this->endSection() ?>
