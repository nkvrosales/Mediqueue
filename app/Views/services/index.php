<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage services for each department</p>
    <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#svcModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-1"></i> Add Service
    </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:10px;">
    <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead><tr><th>#</th><th>Name</th><th>Code</th><th>Department</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
            <?php foreach ($services as $i => $svc): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= esc($svc['name']) ?></strong></td>
                    <td><span class="badge bg-secondary"><?= esc($svc['code']) ?></span></td>
                    <td><?= esc($svc['department_name']) ?></td>
                    <td><?= $svc['is_active'] ? '<span class="badge-status badge-served">Active</span>' : '<span class="badge-status badge-cancelled">Inactive</span>' ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-glass me-1" onclick="editSvc(<?= $svc['id'] ?>)"><i class="bi bi-pencil"></i></button>
                        <form action="<?= base_url('services/delete/' . $svc['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete?')">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($services)): ?><tr><td colspan="6" class="text-center text-muted py-4">No services found</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="svcModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="svcModalTitle">Add Service</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="svcForm" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="svc-dept" class="form-select form-select-dark" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" id="svc-name" class="form-control form-control-dark" required></div>
                    <div class="mb-3"><label class="form-label">Code</label><input type="text" name="code" id="svc-code" class="form-control form-control-dark" maxlength="10" required></div>
                    <div class="mb-3"><label class="form-label">Description</label><textarea name="description" id="svc-desc" class="form-control form-control-dark" rows="2"></textarea></div>
                    <div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="svc-active" checked><label class="form-check-label" for="svc-active">Active</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-glass" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function resetForm() { $('#svcModalTitle').text('Add Service'); $('#svcForm').attr('action', '<?= base_url('services/store') ?>'); $('#svc-name,#svc-code,#svc-desc').val(''); $('#svc-dept').val(''); $('#svc-active').prop('checked',true); }
function editSvc(id) { $.get('<?= base_url('services/edit') ?>/'+id, function(d) { $('#svcModalTitle').text('Edit Service'); $('#svcForm').attr('action','<?= base_url('services/update') ?>/'+id); $('#svc-name').val(d.name); $('#svc-code').val(d.code); $('#svc-dept').val(d.department_id); $('#svc-desc').val(d.description); $('#svc-active').prop('checked',d.is_active==1); new bootstrap.Modal('#svcModal').show(); }); }
</script>
<?= $this->endSection() ?>
