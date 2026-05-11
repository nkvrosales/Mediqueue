<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <select id="dept-select" class="form-select form-select-dark">
            <?php foreach ($departments as $d): ?>
            <option value="<?= $d['id'] ?>" <?= ($d['id'] == $departmentId) ? 'selected' : '' ?>><?= esc($d['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <select id="counter-select" class="form-select form-select-dark">
            <option value="">Select Counter</option>
            <?php foreach ($counters as $c): ?>
            <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-4">
        <button class="btn btn-gradient-primary w-100" id="btn-call-next" onclick="callNext()">
            <i class="bi bi-megaphone me-2"></i>Call Next Patient
        </button>
    </div>
</div>

<div class="row g-3">
    <!-- Now Serving -->
    <div class="col-lg-4">
        <div class="now-serving-card mb-3" id="now-serving-panel">
            <div class="serving-label"><i class="bi bi-broadcast me-1"></i> Now Serving</div>
            <?php if ($currentServing): ?>
            <div class="serving-number" id="serving-num"><?= esc($currentServing['ticket_number']) ?></div>
            <div class="serving-counter" id="serving-counter">at <?= esc($currentServing['counter_name'] ?? '-') ?></div>
            <div class="mt-3 d-flex gap-2 justify-content-center">
                <button class="btn btn-sm btn-success" onclick="completeServing(<?= $currentServing['id'] ?>)"><i class="bi bi-check-lg me-1"></i>Done</button>
                <button class="btn btn-sm btn-warning" onclick="skipServing(<?= $currentServing['id'] ?>)"><i class="bi bi-skip-forward me-1"></i>Skip</button>
            </div>
            <?php else: ?>
            <div class="serving-number" id="serving-num" style="font-size:20px;color:var(--text-muted);">---</div>
            <div class="serving-counter" id="serving-counter">No patient being served</div>
            <?php endif; ?>
        </div>

        <!-- Stats -->
        <div class="card-custom p-3">
            <h6 class="mb-3" style="font-weight:700"><i class="bi bi-graph-up me-2"></i>Queue Stats</h6>
            <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:var(--card-border)!important">
                <span class="text-muted">Waiting</span><span class="fw-bold" id="q-waiting"><?= count($waitingTickets) ?></span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:var(--card-border)!important">
                <span class="text-muted">Served Today</span><span class="fw-bold text-success" id="q-served">0</span>
            </div>
            <div class="d-flex justify-content-between py-2">
                <span class="text-muted">Avg Wait</span><span class="fw-bold" id="q-avgwait">0 min</span>
            </div>
        </div>
    </div>

    <!-- Waiting Queue -->
    <div class="col-lg-4">
        <div class="card-custom p-3">
            <h6 class="mb-3" style="font-weight:700"><i class="bi bi-people me-2"></i>Waiting Queue</h6>
            <div id="waiting-list" style="max-height:450px;overflow-y:auto;">
                <?php if (!empty($waitingTickets)): ?>
                    <?php foreach ($waitingTickets as $t): ?>
                    <div class="queue-item">
                        <div>
                            <div class="queue-number"><?= esc($t['ticket_number']) ?></div>
                            <div class="queue-service"><?= esc($t['service_name'] ?? 'General') ?></div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <?php if ($t['priority'] === 'priority'): ?>
                            <span class="badge-status badge-priority">P</span>
                            <?php endif; ?>
                            <span class="text-muted" style="font-size:12px"><?= date('h:i A', strtotime($t['issued_at'])) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px"></i>Queue is empty</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Served -->
    <div class="col-lg-4">
        <div class="card-custom p-3">
            <h6 class="mb-3" style="font-weight:700"><i class="bi bi-clock-history me-2"></i>Recently Served</h6>
            <div id="recent-list">
                <?php if (!empty($recentServed)): ?>
                    <?php foreach ($recentServed as $t): ?>
                    <div class="queue-item">
                        <div>
                            <div class="queue-number"><?= esc($t['ticket_number']) ?></div>
                            <div class="queue-service"><?= esc($t['counter_name'] ?? '-') ?></div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge-status badge-<?= $t['status'] ?>"><?= ucfirst($t['status']) ?></span>
                            <?php if ($t['status'] === 'skipped'): ?>
                            <button class="btn btn-sm btn-outline-info" onclick="recallTicket(<?= $t['id'] ?>)" title="Recall"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px"></i>No recent tickets</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
let currentDept = $('#dept-select').val();

$('#dept-select').on('change', function() {
    currentDept = $(this).val();
    window.location.href = '<?= base_url('queue') ?>?department_id=' + currentDept;
});

function callNext() {
    const counter = $('#counter-select').val();
    if (!counter) { App.toast('Please select a counter first.', 'warning'); return; }
    $('#btn-call-next').prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Calling...');
    $.post('<?= base_url('queue/call-next') ?>', { department_id: currentDept, counter_id: counter }, function(res) {
        if (res.success) { App.toast(res.message, 'success'); refreshQueue(); }
        else { App.toast(res.message, 'warning'); }
        $('#btn-call-next').prop('disabled', false).html('<i class="bi bi-megaphone me-2"></i>Call Next Patient');
    });
}

function completeServing(id) {
    $.post('<?= base_url('queue/complete') ?>/' + id, {}, function(res) {
        App.toast(res.message, 'success'); refreshQueue();
    });
}

function skipServing(id) {
    $.post('<?= base_url('queue/skip') ?>/' + id, {}, function(res) {
        App.toast(res.message, 'success'); refreshQueue();
    });
}

function recallTicket(id) {
    $.post('<?= base_url('queue/recall') ?>/' + id, {}, function(res) {
        App.toast(res.message, 'success'); refreshQueue();
    });
}

function refreshQueue() {
    $.get('<?= base_url('queue/status') ?>?department_id=' + currentDept, function(data) {
        // Update Now Serving
        if (data.currentServing) {
            let s = data.currentServing;
            $('#now-serving-panel').html(
                '<div class="serving-label"><i class="bi bi-broadcast me-1"></i> Now Serving</div>' +
                '<div class="serving-number">' + s.ticket_number + '</div>' +
                '<div class="serving-counter">at ' + (s.counter_name || '-') + '</div>' +
                '<div class="mt-3 d-flex gap-2 justify-content-center">' +
                '<button class="btn btn-sm btn-success" onclick="completeServing('+s.id+')"><i class="bi bi-check-lg me-1"></i>Done</button>' +
                '<button class="btn btn-sm btn-warning" onclick="skipServing('+s.id+')"><i class="bi bi-skip-forward me-1"></i>Skip</button></div>'
            );
        } else {
            $('#now-serving-panel').html('<div class="serving-label"><i class="bi bi-broadcast me-1"></i> Now Serving</div><div class="serving-number" style="font-size:20px;color:var(--text-muted);">---</div><div class="serving-counter">No patient being served</div>');
        }
        // Update Waiting
        let wHtml = '';
        if (data.waitingTickets.length > 0) {
            data.waitingTickets.forEach(function(t) {
                wHtml += '<div class="queue-item"><div><div class="queue-number">'+t.ticket_number+'</div><div class="queue-service">'+(t.service_name||'General')+'</div></div><div class="d-flex align-items-center gap-2">'+(t.priority==='priority'?'<span class="badge-status badge-priority">P</span>':'')+'<span class="text-muted" style="font-size:12px">'+App.formatTime(t.issued_at)+'</span></div></div>';
            });
        } else { wHtml = '<div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px"></i>Queue is empty</div>'; }
        $('#waiting-list').html(wHtml);
        // Update Recent
        let rHtml = '';
        if (data.recentServed.length > 0) {
            data.recentServed.forEach(function(t) {
                rHtml += '<div class="queue-item"><div><div class="queue-number">'+t.ticket_number+'</div><div class="queue-service">'+(t.counter_name||'-')+'</div></div><div class="d-flex align-items-center gap-2"><span class="badge-status badge-'+t.status+'">'+t.status.charAt(0).toUpperCase()+t.status.slice(1)+'</span>'+(t.status==='skipped'?'<button class="btn btn-sm btn-outline-info" onclick="recallTicket('+t.id+')" title="Recall"><i class="bi bi-arrow-counterclockwise"></i></button>':'')+'</div></div>';
            });
        } else { rHtml = '<div class="text-center text-muted py-4"><i class="bi bi-inbox" style="font-size:32px;display:block;margin-bottom:8px"></i>No recent tickets</div>'; }
        $('#recent-list').html(rHtml);
        // Stats
        if (data.stats) {
            $('#q-waiting').text(data.stats.waiting);
            $('#q-served').text(data.stats.served);
            $('#q-avgwait').text((data.stats.avg_wait||0) + ' min');
        }
        // Counters
        if (data.counters) {
            let cVal = $('#counter-select').val();
            let cHtml = '<option value="">Select Counter</option>';
            data.counters.forEach(function(c) { cHtml += '<option value="'+c.id+'"'+(c.id==cVal?' selected':'')+'>'+c.name+'</option>'; });
            $('#counter-select').html(cHtml);
        }
    });
}

// Auto-refresh every 10 seconds
setInterval(refreshQueue, 10000);
</script>
<?= $this->endSection() ?>
