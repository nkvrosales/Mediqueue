<?= $this->extend('layouts/kiosk') ?>
<?= $this->section('content') ?>

<div class="kiosk-page">
    <div class="kiosk-header">
        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
            <div style="width:56px;height:56px;background:var(--gradient-1);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;color:white;box-shadow:var(--shadow-glow);">
                <i class="bi bi-hospital"></i>
            </div>
        </div>
        <h1>MediQueue</h1>
        <p class="text-muted" style="font-size:18px;">Select a department to get your queue number</p>
    </div>

    <div class="container">
        <!-- Step 1: Select Department -->
        <div id="step-dept">
            <h5 class="text-center mb-4" style="font-weight:700"><i class="bi bi-1-circle me-2"></i>Select Department</h5>
            <div class="row g-4 justify-content-center">
                <?php foreach ($departments as $dept): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="dept-card" onclick="selectDept(<?= $dept['id'] ?>, '<?= esc($dept['name']) ?>', '<?= esc($dept['code']) ?>', '<?= esc($dept['color']) ?>')" data-dept-id="<?= $dept['id'] ?>">
                        <div class="dept-icon" style="background: <?= esc($dept['color']) ?>">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="dept-name"><?= esc($dept['name']) ?></div>
                        <div class="dept-code"><?= esc($dept['code']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Step 2: Select Service (optional) + Name -->
        <div id="step-service" style="display:none;" class="fade-in">
            <div class="text-center mb-4">
                <button class="btn btn-glass mb-3" onclick="goBack()"><i class="bi bi-arrow-left me-2"></i>Back</button>
                <h5 style="font-weight:700"><i class="bi bi-2-circle me-2"></i>Select Service <small class="text-muted">(Optional)</small></h5>
                <p class="text-muted" id="selected-dept-label"></p>
            </div>
            <div class="row justify-content-center mb-4">
                <div class="col-md-6">
                    <div id="services-list" class="row g-3"></div>
                    <div class="mt-4">
                        <label class="form-label">Patient Name <small class="text-muted">(Optional)</small></label>
                        <input type="text" id="patient-name" class="form-control form-control-dark" placeholder="Enter your name">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Priority</label>
                        <select id="priority" class="form-select form-select-dark">
                            <option value="normal">Normal</option>
                            <option value="priority">Priority (Senior/PWD)</option>
                        </select>
                    </div>
                    <button class="btn btn-gradient-primary w-100 mt-4 py-3" onclick="generateTicket()" id="btn-generate" style="font-size:18px;">
                        <i class="bi bi-ticket-perforated me-2"></i>Get Queue Number
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--darker); border: 2px solid var(--primary);">
            <div class="modal-body">
                <div class="ticket-display print-ticket">
                    <div style="font-size:12px;text-transform:uppercase;letter-spacing:2px;color:var(--text-muted);font-weight:600;">Your Queue Number</div>
                    <div class="ticket-number" id="ticket-num"></div>
                    <div class="ticket-dept" id="ticket-dept"></div>
                    <div style="font-size:13px;color:var(--text-muted);" id="ticket-service"></div>
                    <hr style="border-color:var(--card-border);margin:20px 0;">
                    <div class="ticket-queue-info">
                        <i class="bi bi-people me-1"></i> <span id="ticket-waiting"></span> patient(s) ahead of you
                    </div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:8px;" id="ticket-time"></div>
                </div>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button class="btn btn-glass" onclick="printTicket()"><i class="bi bi-printer me-2"></i>Print</button>
                <button class="btn btn-gradient-primary" onclick="newTicket()"><i class="bi bi-plus-lg me-2"></i>New Ticket</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
let selectedDept = null;
let selectedService = null;

function selectDept(id, name, code, color) {
    selectedDept = { id, name, code, color };
    selectedService = null;
    $('#selected-dept-label').html('<span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:'+color+';margin-right:6px;"></span>' + name);
    $('#step-dept').hide();
    $('#step-service').show();
    // Load services
    $.get('<?= base_url('kiosk/services') ?>/' + id, function(data) {
        let html = '';
        if (data.services.length > 0) {
            data.services.forEach(function(s) {
                html += '<div class="col-6"><div class="dept-card" style="padding:16px" onclick="selectService(this,'+s.id+')" data-svc-id="'+s.id+'"><div class="dept-name" style="font-size:14px">'+s.name+'</div><div class="dept-code">'+s.code+'</div></div></div>';
            });
        } else {
            html = '<div class="col-12 text-center text-muted py-3">No specific services — general queue</div>';
        }
        $('#services-list').html(html);
    });
}

function selectService(el, id) {
    $('.dept-card[data-svc-id]').removeClass('selected');
    $(el).closest('.dept-card').addClass('selected');
    selectedService = id;
}

function goBack() {
    $('#step-service').hide();
    $('#step-dept').show();
    selectedDept = null;
    selectedService = null;
}

function generateTicket() {
    if (!selectedDept) return;
    $('#btn-generate').prop('disabled', true).html('<i class="bi bi-arrow-repeat spin"></i> Generating...');
    $.post('<?= base_url('kiosk/generate') ?>', {
        department_id: selectedDept.id,
        service_id: selectedService,
        patient_name: $('#patient-name').val(),
        priority: $('#priority').val()
    }, function(res) {
        if (res.success) {
            $('#ticket-num').text(res.ticket.ticket_number);
            $('#ticket-dept').text(res.department.name);
            $('#ticket-service').text(res.ticket.service_id ? 'Service ticket' : 'General queue');
            $('#ticket-waiting').text(res.waiting_before);
            $('#ticket-time').text('Issued: ' + new Date().toLocaleTimeString());
            new bootstrap.Modal('#ticketModal').show();
        } else {
            App.toast(res.message, 'error');
        }
        $('#btn-generate').prop('disabled', false).html('<i class="bi bi-ticket-perforated me-2"></i>Get Queue Number');
    }).fail(function() {
        App.toast('Failed to generate ticket', 'error');
        $('#btn-generate').prop('disabled', false).html('<i class="bi bi-ticket-perforated me-2"></i>Get Queue Number');
    });
}

function printTicket() { window.print(); }
function newTicket() {
    bootstrap.Modal.getInstance('#ticketModal').hide();
    goBack();
    $('#patient-name').val('');
    $('#priority').val('normal');
}
</script>
<?= $this->endSection() ?>
