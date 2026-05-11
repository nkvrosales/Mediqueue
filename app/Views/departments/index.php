<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage hospital departments</p>
    <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#deptModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-1"></i> Add Department
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
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Code</th><th>Color</th><th>Description</th><th>Status</th><th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($departments as $i => $dept): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= esc($dept['name']) ?></strong></td>
                    <td><span class="badge bg-secondary"><?= esc($dept['code']) ?></span></td>
                    <td><span style="display:inline-block;width:24px;height:24px;border-radius:6px;background:<?= esc($dept['color']) ?>"></span></td>
                    <td class="text-muted" style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($dept['description']) ?></td>
                    <td><?= $dept['is_active'] ? '<span class="badge-status badge-served">Active</span>' : '<span class="badge-status badge-cancelled">Inactive</span>' ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-glass me-1" onclick="editDept(<?= $dept['id'] ?>)" title="Edit"><i class="bi bi-pencil"></i></button>
                        <form action="<?= base_url('departments/delete/' . $dept['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this department?')">
                            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($departments)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No departments found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="deptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deptModalTitle">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deptForm" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="dept-name" class="form-control form-control-dark" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" id="dept-code" class="form-control form-control-dark" maxlength="10" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Color</label>
                            <input type="color" name="color" id="dept-color" class="form-control form-control-dark form-control-color" value="#6366f1" style="height:42px">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="dept-desc" class="form-control form-control-dark" rows="2"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="dept-active" checked>
                        <label class="form-check-label" for="dept-active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-glass" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function resetForm() {
    $('#deptModalTitle').text('Add Department');
    $('#deptForm').attr('action', '<?= base_url('departments/store') ?>');
    $('#dept-name, #dept-code, #dept-desc').val('');
    $('#dept-color').val('#6366f1');
    $('#dept-active').prop('checked', true);
}
function editDept(id) {
    $.get('<?= base_url('departments/edit') ?>/' + id, function(d) {
        $('#deptModalTitle').text('Edit Department');
        $('#deptForm').attr('action', '<?= base_url('departments/update') ?>/' + id);
        $('#dept-name').val(d.name);
        $('#dept-code').val(d.code);
        $('#dept-color').val(d.color);
        $('#dept-desc').val(d.description);
        $('#dept-active').prop('checked', d.is_active == 1);
        new bootstrap.Modal('#deptModal').show();
    });
}
</script>
<?= $this->endSection() ?>
