<?php
// application/views/admin/tugas.php
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Manage Task</h3>
    <div class="text-muted small">Tambah dan kelola tugas berdasarkan divisi.</div>
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

<div class="card shadow-sm border-0 mb-3">
  <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-tasks me-2 text-primary"></i>Tambah Task
    </div>
    <span class="badge text-bg-light">Task</span>
  </div>

  <div class="card-body">
    <form method="post" action="<?= base_url('index.php/admin/tugas_store') ?>" class="row g-2 align-items-end">

      <div class="col-md-4 col-lg-3">
        <label class="form-label small text-muted mb-1">Name Task</label>
        <input type="text" name="nama_tugas" class="form-control" placeholder="Contoh: Input Laporan" required>
      </div>

      <div class="col-md-4 col-lg-3">
        <label class="form-label small text-muted mb-1">Division</label>
        <select name="divisi_id" class="form-select" required>
          <option value="">Choose Division</option>
          <?php foreach($divisi as $d): ?>
            <option value="<?= (int)$d->id ?>"><?= htmlspecialchars($d->nama_divisi) ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="col-md-4 col-lg-4">
        <label class="form-label small text-muted mb-1">Description</label>
        <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat (opsional)">
      </div>

      <div class="col-md-12 col-lg-2 d-grid">
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
      <i class="fa-solid fa-list-check me-2 text-primary"></i>Daftar Task
    </div>
    <span class="badge text-bg-primary">
      Total: <?= isset($tugas) ? count($tugas) : 0 ?>
    </span>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr class="text-nowrap">
          <th style="width:70px;">No</th>
          <th>Name Task</th>
          <th style="width:220px;">Division</th>
          <th>Description</th>
          <th style="width:160px;" class="text-end">Action</th>
        </tr>
      </thead>

      <tbody>
        <?php if(empty($tugas)): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No data</td>
          </tr>
        <?php endif; ?>

        <?php $no=1; foreach($tugas as $t): ?>
          <?php
            $desc = $t->deskripsi ?? '';
            $desc = trim((string)$desc);
          ?>
          <tr class="text-nowrap">
            <td><?= $no++ ?></td>

            <td class="fw-semibold">
              <?= htmlspecialchars($t->nama_tugas) ?>
            </td>

            <td>
              <span class="badge text-bg-light border">
                <?= htmlspecialchars($t->nama_divisi) ?>
              </span>
            </td>

            <td class="text-muted text-wrap">
              <?= $desc !== '' ? htmlspecialchars($desc) : '-' ?>
            </td>

            <td class="text-end">
              <a href="<?= base_url('index.php/admin/tugas_delete/'.(int)$t->id) ?>"
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('Hapus tugas ini?')">
                <i class="fa-solid fa-trash me-1"></i>Delete
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</div>
