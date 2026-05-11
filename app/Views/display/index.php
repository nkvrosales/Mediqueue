<?= $this->extend('layouts/display') ?>
<?= $this->section('content') ?>

<div class="display-page">
    <div class="display-header">
        <h1><i class="bi bi-hospital me-3"></i>Now Serving</h1>
        <div class="display-time" id="display-time"><?= date('h:i:s A') ?></div>
    </div>

    <div class="container-fluid">
        <div class="row g-4" id="display-board">
            <?php foreach ($departments as $dept): ?>
            <div class="col-md-4 col-lg-3" id="dept-col-<?= $dept['id'] ?>">
                <div class="display-dept-card">
                    <div class="dept-header" style="background: <?= esc($dept['color']) ?>">
                        <span><?= esc($dept['name']) ?></span>
                        <span class="badge bg-dark bg-opacity-25" id="dept-code-<?= $dept['id'] ?>"><?= esc($dept['code']) ?></span>
                    </div>
                    <div class="dept-body">
                        <div class="display-ticket-number" id="serving-<?= $dept['id'] ?>">---</div>
                        <div class="display-counter" id="counter-<?= $dept['id'] ?>">Waiting...</div>
                        <div class="display-waiting" id="waiting-<?= $dept['id'] ?>">
                            <i class="bi bi-people me-1"></i> <span>0</span> in queue
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted" style="font-size:14px;"><i class="bi bi-info-circle me-1"></i> Display auto-refreshes every 5 seconds</p>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function updateDisplay() {
    $.get('<?= base_url('display/data') ?>', function(data) {
        // Update each department
        data.departments.forEach(function(dept) {
            const servingTickets = data.serving[dept.id] || [];
            const waitCount = data.waitingCounts[dept.id] || 0;

            if (servingTickets.length > 0) {
                const t = servingTickets[0]; // Show first serving ticket
                const oldNum = $('#serving-' + dept.id).text();
                $('#serving-' + dept.id).text(t.ticket_number);
                if (oldNum !== t.ticket_number && oldNum !== '---') {
                    $('#serving-' + dept.id).css('animation', 'none');
                    setTimeout(function() { $('#serving-' + dept.id).css('animation', 'pulse 2s ease-in-out infinite'); }, 10);
                }
                $('#counter-' + dept.id).text(t.counter_name ? 'at ' + t.counter_name : '');
            } else {
                $('#serving-' + dept.id).text('---');
                $('#counter-' + dept.id).text('Waiting...');
            }
            $('#waiting-' + dept.id).html('<i class="bi bi-people me-1"></i> ' + waitCount + ' in queue');
        });
    });
}

// Update time
setInterval(function() {
    const now = new Date();
    $('#display-time').text(now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
}, 1000);

// Update display every 5 seconds
updateDisplay();
setInterval(updateDisplay, 5000);
</script>
<?= $this->endSection() ?>
