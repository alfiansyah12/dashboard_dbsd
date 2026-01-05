<?php
// application/views/admin/departemen.php
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Manage Division</h3>
    <div class="text-muted small">Tambah dan kelola daftar departemen.</div>
  </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-3">
  <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-sitemap me-2 text-primary"></i>Tambah Division
    </div>
    <span class="badge text-bg-light">Division</span>
  </div>

  <div class="card-body">
    <form method="post" action="<?= base_url('index.php/admin/departemen_store') ?>" class="row g-2 align-items-end">
      <div class="col-md-6 col-lg-5">
        <label class="form-label small text-muted mb-1">Nama Divisi</label>
        <input type="text" name="nama_departemen" class="form-control" placeholder="Contoh: Marketing" required>
      </div>

      <div class="col-md-3 col-lg-2 d-grid">
        <button class="btn btn-primary">
          <i class="fa-solid fa-plus me-1"></i>Add
        </button>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm border-0">
  <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-list me-2 text-primary"></i>Daftar Division
    </div>
    <span class="badge text-bg-primary">
      Total: <?= isset($departemen) ? count($departemen) : 0 ?>
    </span>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr class="text-nowrap">
          <th style="width:70px;">No</th>
          <th>Name Division</th>
          <th style="width:170px;" class="text-end">Action</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($departemen)): ?>
          <tr>
            <td colspan="3" class="text-center text-muted py-4">No data</td>
          </tr>
        <?php endif; ?>

        <?php $no = 1;
        foreach ($departemen as $d): ?>
          <tr class="text-nowrap">
            <td><?= $no++ ?></td>
            <td class="fw-semibold"><?= htmlspecialchars($d->nama_departemen) ?></td>
            <td class="text-end">
              <a href="<?= base_url('index.php/admin/departemen_delete/' . (int)$d->id) ?>"
                class="btn btn-sm btn-danger"
                onclick="return confirm('Hapus departemen ini?')">
                <i class="fa-solid fa-trash me-1"></i>Delete
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>