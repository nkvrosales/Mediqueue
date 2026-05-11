<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage staff and admin accounts</p>
    <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetForm()">
        <i class="bi bi-plus-lg me-1"></i> Add User
    </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show" style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);border-radius:10px;">
    <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:10px;">
    <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-dark-custom mb-0">
            <thead><tr><th>#</th><th>Full Name</th><th>Username</th><th>Email</th><th>Role</th><th>Department</th><th>Status</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
            <?php foreach ($users as $i => $u): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= esc($u['full_name']) ?></strong></td>
                    <td><?= esc($u['username']) ?></td>
                    <td class="text-muted"><?= esc($u['email']) ?></td>
                    <td><span class="badge-status <?= $u['role'] === 'admin' ? 'badge-serving' : 'badge-normal' ?>"><?= ucfirst($u['role']) ?></span></td>
                    <td><?= esc($u['department_name'] ?? 'All') ?></td>
                    <td><?= $u['is_active'] ? '<span class="badge-status badge-served">Active</span>' : '<span class="badge-status badge-cancelled">Inactive</span>' ?></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-glass me-1" onclick="editUser(<?= $u['id'] ?>)"><i class="bi bi-pencil"></i></button>
                        <?php if ($u['id'] != session()->get('user_id')): ?>
                        <form action="<?= base_url('users/delete/' . $u['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this user?')">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="userModalTitle">Add User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="userForm" method="post">
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-6"><label class="form-label">Full Name</label><input type="text" name="full_name" id="u-name" class="form-control form-control-dark" required></div>
                        <div class="col-6"><label class="form-label">Username</label><input type="text" name="username" id="u-user" class="form-control form-control-dark" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" id="u-email" class="form-control form-control-dark" required></div>
                    <div class="mb-3"><label class="form-label">Password <small class="text-muted" id="pw-hint">(required)</small></label><input type="password" name="password" id="u-pass" class="form-control form-control-dark"></div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Role</label>
                            <select name="role" id="u-role" class="form-select form-select-dark" required>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Department</label>
                            <select name="department_id" id="u-dept" class="form-select form-select-dark">
                                <option value="">All / None</option>
                                <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= esc($d['name']) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="u-active" checked><label class="form-check-label" for="u-active">Active</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-glass" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-gradient-primary">Save</button></div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
function resetForm() { $('#userModalTitle').text('Add User'); $('#userForm').attr('action','<?= base_url('users/store') ?>'); $('#u-name,#u-user,#u-email,#u-pass').val(''); $('#u-role').val('staff'); $('#u-dept').val(''); $('#u-active').prop('checked',true); $('#u-pass').attr('required',true); $('#pw-hint').text('(required)'); }
function editUser(id) { $.get('<?= base_url('users/edit') ?>/'+id, function(d) { $('#userModalTitle').text('Edit User'); $('#userForm').attr('action','<?= base_url('users/update') ?>/'+id); $('#u-name').val(d.full_name); $('#u-user').val(d.username); $('#u-email').val(d.email); $('#u-pass').val('').removeAttr('required'); $('#pw-hint').text('(leave blank to keep current)'); $('#u-role').val(d.role); $('#u-dept').val(d.department_id||''); $('#u-active').prop('checked',d.is_active==1); new bootstrap.Modal('#userModal').show(); }); }
</script>
<?= $this->endSection() ?>
