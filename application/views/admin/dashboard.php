<?php
// application/views/admin/dashboard.php
// Catatan: jangan panggil header/footer di sini (sudah dipanggil dari controller layout)
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Dashboard Admin</h3>
    <div class="text-muted small">
      Welcome, <b><?= htmlspecialchars($this->session->userdata('nama')); ?></b>
    </div>
  </div>

  <div class="d-flex gap-2">
    <a href="<?= base_url('index.php/admin/user') ?>" class="btn btn-sm btn-outline-primary">
      <i class="fa-solid fa-users me-1"></i> Manage User
    </a>
    <a href="<?= base_url('index.php/admin/target') ?>" class="btn btn-sm btn-primary">
      <i class="fa-solid fa-chart-line me-1"></i> Target
    </a>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm kpi kpi-primary">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <div class="opacity-75 small">Total User</div>
          <div class="display-6 fw-bold mb-0"><?= (int)($total_user ?? 0) ?></div>
          <div class="opacity-75 small mt-1">Pengguna terdaftar</div>
        </div>
        <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card border-0 shadow-sm kpi kpi-success">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <div class="opacity-75 small">Total Departement</div>
          <div class="display-6 fw-bold mb-0"><?= (int)($total_departemen ?? 0) ?></div>
          <div class="opacity-75 small mt-1">Departemen aktif</div>
        </div>
        <div class="kpi-icon"><i class="fa-solid fa-sitemap"></i></div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card border-0 shadow-sm kpi kpi-warning">
      <div class="card-body d-flex align-items-center justify-content-between">
        <div>
          <div class="opacity-75 small">Total Task</div>
          <div class="display-6 fw-bold mb-0"><?= (int)($total_tugas ?? 0) ?></div>
          <div class="opacity-75 small mt-1">Tugas tersimpan</div>
        </div>
        <div class="kpi-icon"><i class="fa-solid fa-list-check"></i></div>
      </div>
    </div>
  </div>
</div>

<hr class="my-4">

<div class="row g-3">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-0 fw-semibold">
        <i class="fa-solid fa-bolt me-2 text-primary"></i>Quick Actions
      </div>
      <div class="card-body quick-actions">
        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-outline-primary" href="<?= base_url('index.php/admin/user') ?>">
            <i class="fa-solid fa-user-gear me-1"></i> Kelola User
          </a>
          <a class="btn btn-outline-primary" href="<?= base_url('index.php/admin/departemen') ?>">
            <i class="fa-solid fa-sitemap me-1"></i> Kelola Departement
          </a>
          <a class="btn btn-outline-primary" href="<?= base_url('index.php/admin/tugas') ?>">
            <i class="fa-solid fa-tasks me-1"></i> Kelola Task
          </a>
          <a class="btn btn-primary" href="<?= base_url('index.php/admin/target') ?>">
            <i class="fa-solid fa-chart-line me-1"></i> Target & Realisasi
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-0 fw-semibold">
        <i class="fa-solid fa-circle-info me-2 text-primary"></i>Status
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <div class="text-muted">Tanggal</div>
          <div class="fw-semibold"><?= date('d M Y') ?></div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-2">
          <div class="text-muted">Role</div>
          <div class="badge text-bg-dark"><?= htmlspecialchars((string)$this->session->userdata('role')) ?></div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-2">
          <div class="text-muted">Session</div>
          <div class="badge text-bg-success">Active</div>
        </div>
      </div>
    </div>
  </div>
</div>