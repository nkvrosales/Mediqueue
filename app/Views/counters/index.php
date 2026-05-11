<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage service counters/windows</p>
    <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#ctrModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-1"></i> Add Counter
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
            <thead><tr><th>#</th><th>Name</th><th>Department</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
            <?php foreach ($counters as $i => $ctr): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= esc($ctr['name']) ?></strong></td>
                    <td><?= esc($ctr['department_name']) ?></td>
                    <td><?= $ctr['is_active'] ? '<span class="badge-status badge-served">Active</span>' : '<span class="badge-status badge-cancelled">Inactive</span>' ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-glass me-1" onclick="editCtr(<?= $ctr['id'] ?>)"><i class="bi bi-pencil"></i></button>
                        <form action="<?= base_url('counters/delete/' . $ctr['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete?')">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($counters)): ?><tr><td colspan="5" class="text-center text-muted py-4">No counters found</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="ctrModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="ctrModalTitle">Add Counter</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="ctrForm" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Department</label>
                        <select name="department_id" id="ctr-dept" class="form-select form-select-dark" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Counter Name</label><input type="text" name="name" id="ctr-name" class="form-control form-control-dark" required placeholder="e.g. Window 1"></div>
                    <div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="ctr-active" checked><label class="form-check-label" for="ctr-active">Active</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-glass" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function resetForm() { $('#ctrModalTitle').text('Add Counter'); $('#ctrForm').attr('action','<?= base_url('counters/store') ?>'); $('#ctr-name').val(''); $('#ctr-dept').val(''); $('#ctr-active').prop('checked',true); }
function editCtr(id) { $.get('<?= base_url('counters/edit') ?>/'+id, function(d) { $('#ctrModalTitle').text('Edit Counter'); $('#ctrForm').attr('action','<?= base_url('counters/update') ?>/'+id); $('#ctr-name').val(d.name); $('#ctr-dept').val(d.department_id); $('#ctr-active').prop('checked',d.is_active==1); new bootstrap.Modal('#ctrModal').show(); }); }
</script>
<?= $this->endSection() ?>
