<?php
// application/views/admin/user.php
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Manage User</h3>
    <div class="text-muted small">Kelola user, role, dan pembagian divisi.</div>
  </div>
</div>

<?php if($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<!-- FORM ADD USER -->
<div class="card shadow-sm border-0 mb-3">
  <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-user-plus me-2 text-primary"></i>Tambah User
    </div>
    <span class="badge text-bg-light">Admin Panel</span>
  </div>

  <div class="card-body">
    <form method="post" action="<?= base_url('index.php/admin/user_store') ?>" class="row g-2 align-items-end">

      <div class="col-md-3">
        <label class="form-label small text-muted mb-1">Name</label>
        <input type="text" name="nama" class="form-control" placeholder="Nama user" required>
      </div>

      <div class="col-md-3">
        <label class="form-label small text-muted mb-1">Email</label>
        <input type="email" name="email" class="form-control" placeholder="email@domain.com" required>
      </div>

      <div class="col-md-3">
        <label class="form-label small text-muted mb-1">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Role</label>
        <select name="role" class="form-select" required>
          <option value="">Pilih Role</option>
          <option value="pegawai">Pegawai</option>
          <option value="atasan">Atasan</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div class="col-md-1 d-grid">
        <button class="btn btn-primary">
          <i class="fa-solid fa-plus me-1"></i>Add
        </button>
      </div>
    </form>
  </div>
</div>

<!-- TABLE USER -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-users me-2 text-primary"></i>Daftar User
    </div>
    <span class="badge text-bg-primary">
      Total: <?= isset($users) ? count($users) : 0 ?>
    </span>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr class="text-nowrap">
          <th style="width:60px;">No</th>
          <th>Name</th>
          <th>Email</th>
          <th style="width:120px;">Role</th>
          <th style="width:360px;">Division</th>
          <th style="width:240px;" class="text-end">Action</th>
        </tr>
      </thead>

      <tbody>
      <?php if(empty($users)): ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">No data</td>
        </tr>
      <?php endif; ?>

      <?php $no=1; foreach($users as $u): ?>
        <?php
          $role = strtolower((string)$u->role);
          $roleBadge = 'text-bg-secondary';
          if ($role === 'admin')   $roleBadge = 'text-bg-dark';
          if ($role === 'atasan')  $roleBadge = 'text-bg-info';
          if ($role === 'pegawai') $roleBadge = 'text-bg-success';
        ?>

        <tr class="text-nowrap">
          <td><?= $no++ ?></td>

          <td class="fw-semibold">
            <i class="fa-solid fa-user me-2 text-muted"></i>
            <?= htmlspecialchars($u->nama) ?>
          </td>

          <td class="text-muted"><?= htmlspecialchars($u->email) ?></td>

          <td>
            <span class="badge <?= $roleBadge ?>">
              <?= ucfirst(htmlspecialchars($u->role)) ?>
            </span>
          </td>

          <!-- ASSIGN DIVISION -->
          <td class="text-nowrap">
            <?php if ($u->role === 'pegawai'): ?>
              <form method="post" action="<?= base_url('index.php/admin/assign_divisi') ?>" class="d-flex gap-2 align-items-center">
                <input type="hidden" name="user_id" value="<?= (int)$u->id ?>">

                <select name="divisi_id" class="form-select form-select-sm" style="max-width: 220px;">
                  <option value="">-- Choose Division --</option>
                  <?php foreach($divisi as $d): ?>
                    <option value="<?= (int)$d->id ?>" <?= ((int)$u->divisi_id === (int)$d->id ? 'selected' : '') ?>>
                      <?= htmlspecialchars($d->nama_divisi) ?>
                    </option>
                  <?php endforeach ?>
                </select>

                <button type="submit" class="btn btn-sm btn-outline-primary">
                  <i class="fa-solid fa-floppy-disk me-1"></i>Save
                </button>
              </form>
            <?php else: ?>
              <span class="text-muted">-</span>
            <?php endif ?>
          </td>

          <!-- ACTION -->
          <td class="text-end">
            <div class="d-inline-flex gap-2">
              <?php if (in_array($u->role, ['pegawai','atasan'], true)): ?>
                <button type="button"
                        class="btn btn-sm btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#resetModal<?= (int)$u->id ?>">
                  <i class="fa-solid fa-key me-1"></i>Reset
                </button>
              <?php endif; ?>

              <?php if ($u->role !== 'admin'): ?>
                <a href="<?= base_url('index.php/admin/user_delete/'.(int)$u->id) ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this user?')">
                  <i class="fa-solid fa-trash me-1"></i>Delete
                </a>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </div>

            <!-- MODAL RESET PASSWORD (BTN Style) -->
            <?php if (in_array($u->role, ['pegawai','atasan'], true)): ?>
  <div class="modal fade modal-reset" id="resetModal<?= (int)$u->id ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <form method="post" action="<?= base_url('index.php/admin/reset_password/'.(int)$u->id) ?>">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fa-solid fa-key me-2"></i> Reset Password
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="reset-userbox">
              <div class="left">
                <div class="avatar"><?= strtoupper(substr((string)$u->nama, 0, 1)) ?></div>
                <div class="meta">
                  <div class="name"><?= htmlspecialchars($u->nama) ?></div>
                  <div class="role">Role: <?= htmlspecialchars($u->role) ?></div>
                </div>
              </div>
              <span class="badge text-bg-primary">ID: <?= (int)$u->id ?></span>
            </div>

            <div class="reset-form">
              <label class="form-label">New Password</label>
              <input type="password"
                     name="new_password"
                     class="form-control"
                     minlength="6"
                     required
                     placeholder="Minimal 6 karakter">
              <div class="reset-hint">
                Gunakan kombinasi huruf besar, kecil, angka, dan simbol untuk keamanan.
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-reset"
                    onclick="return confirm('Reset password for this user?')">
              <i class="fa-solid fa-rotate me-1"></i> Reset
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>
<?php endif; ?>


          </td>
        </tr>

      <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function togglePw(id, btn){
  const el = document.getElementById(id);
  const icon = btn.querySelector('i');
  if(!el) return;

  if(el.type === 'password'){
    el.type = 'text';
    icon.className = 'fa-regular fa-eye-slash';
  } else {
    el.type = 'password';
    icon.className = 'fa-regular fa-eye';
  }
}
</script>
