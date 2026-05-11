<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card-custom p-3 mb-4">
    <form id="reportForm">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" id="date-from" class="form-control form-control-dark" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" id="date-to" class="form-control form-control-dark" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Department</label>
                <select name="department_id" id="report-dept" class="form-select form-select-dark">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-gradient-primary w-100" id="btn-report">
                    <i class="bi bi-funnel me-1"></i>Generate Report
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4" id="report-summary" style="display:none;">
    <div class="col-md-3"><div class="stat-card primary"><div class="stat-label">Total Tickets</div><div class="stat-value" id="rpt-total">0</div></div></div>
    <div class="col-md-3"><div class="stat-card success"><div class="stat-label">Served</div><div class="stat-value" id="rpt-served">0</div></div></div>
    <div class="col-md-3"><div class="stat-card danger"><div class="stat-label">Skipped</div><div class="stat-value" id="rpt-skipped">0</div></div></div>
    <div class="col-md-3"><div class="stat-card info"><div class="stat-label">Avg Wait Time</div><div class="stat-value" id="rpt-avgwait">0<small style="font-size:14px"> min</small></div></div></div>
</div>

<!-- Report Table -->
<div class="card-custom" id="report-table" style="display:none;">
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead><tr><th>Ticket #</th><th>Department</th><th>Service</th><th>Status</th><th>Priority</th><th>Issued</th><th>Called</th><th>Completed</th><th>Staff</th></tr></thead>
            <tbody id="report-body"></tbody>
        </table>
    </div>
</div>

<div id="report-empty" class="text-center text-muted py-5" style="display:none;">
    <i class="bi bi-clipboard-data" style="font-size:48px;display:block;margin-bottom:12px"></i>
    No tickets found for the selected criteria
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$('#reportForm').on('submit', function(e) {
    e.preventDefault();
    $('#btn-report').prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Loading...');
    $.post('<?= base_url('reports/generate') ?>', {
        date_from: $('#date-from').val(),
        date_to: $('#date-to').val(),
        department_id: $('#report-dept').val()
    }, function(data) {
        if (data.tickets.length > 0) {
            $('#report-summary').show();
            $('#report-table').show();
            $('#report-empty').hide();
            $('#rpt-total').text(data.summary.total);
            $('#rpt-served').text(data.summary.served);
            $('#rpt-skipped').text(data.summary.skipped);
            $('#rpt-avgwait').html(data.summary.avg_wait + '<small style="font-size:14px"> min</small>');

            let html = '';
            data.tickets.forEach(function(t) {
                html += '<tr>' +
                    '<td><strong>' + t.ticket_number + '</strong></td>' +
                    '<td>' + t.department_name + '</td>' +
                    '<td>' + (t.service_name || '-') + '</td>' +
                    '<td><span class="badge-status badge-' + t.status + '">' + t.status.charAt(0).toUpperCase() + t.status.slice(1) + '</span></td>' +
                    '<td><span class="badge-status badge-' + t.priority + '">' + t.priority.charAt(0).toUpperCase() + t.priority.slice(1) + '</span></td>' +
                    '<td>' + (t.issued_at ? App.formatTime(t.issued_at) : '-') + '</td>' +
                    '<td>' + (t.called_at ? App.formatTime(t.called_at) : '-') + '</td>' +
                    '<td>' + (t.completed_at ? App.formatTime(t.completed_at) : '-') + '</td>' +
                    '<td>' + (t.staff_name || '-') + '</td></tr>';
            });
            $('#report-body').html(html);
        } else {
            $('#report-summary, #report-table').hide();
            $('#report-empty').show();
        }
        $('#btn-report').prop('disabled', false).html('<i class="bi bi-funnel me-1"></i>Generate Report');
    });
});
</script>
<?= $this->endSection() ?>
