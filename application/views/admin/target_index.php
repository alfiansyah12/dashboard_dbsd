<?php
// application/views/admin/target_index.php

$sumTarget = 0; $sumRealisasi = 0; $sumFee = 0; $sumVol = 0;
foreach(($targets ?? []) as $t){
  $sumTarget    += (int)$t->target;
  $sumRealisasi += (int)$t->realisasi;
  $sumFee       += (int)($t->fee_base_income ?? 0);
  $sumVol       += (int)($t->volume_of_agent ?? 0);
}
$avgProgress = ($sumTarget > 0) ? round(($sumRealisasi / $sumTarget) * 100, 2) : 0;

$is_edit = !empty($edit);
$action_url = $is_edit
  ? base_url('index.php/admin/target_update/'.$edit->id)
  : base_url('index.php/admin/target_store');

$periode_val = $is_edit ? date('Y-m', strtotime($edit->periode)) : '';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Target & Realisasi</h3>
    <div class="text-muted small">Ringkasan KPI, grafik per periode, dan input data.</div>
  </div>

  <div class="d-flex gap-2">
    <a href="<?= base_url('index.php/admin') ?>" class="btn btn-sm btn-outline-primary">
      <i class="fa-solid fa-house me-1"></i> Dashboard
    </a>
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

<!-- KPI -->
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card kpi-card">
      <div class="card-body">
        <div>
          <div class="kpi-label">Total Target</div>
          <div class="kpi-value"><?= (int)$sumTarget ?></div>
          <div class="kpi-mini">Akumulasi target</div>
        </div>
        <div class="kpi-icon-soft"><i class="fa-solid fa-bullseye"></i></div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card kpi-card kpi-success-soft">
      <div class="card-body">
        <div>
          <div class="kpi-label">Total Realisasi</div>
          <div class="kpi-value"><?= (int)$sumRealisasi ?></div>
          <div class="kpi-mini">Akumulasi realisasi</div>
        </div>
        <div class="kpi-icon-soft"><i class="fa-solid fa-circle-check"></i></div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card kpi-card">
      <div class="card-body">
        <div>
          <div class="kpi-label">Avg Progress</div>
          <div class="kpi-value"><?= (float)$avgProgress ?>%</div>
          <div class="kpi-mini">Rata-rata progres</div>
        </div>
        <div class="kpi-icon-soft"><i class="fa-solid fa-chart-line"></i></div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card kpi-card kpi-warning-soft">
      <div class="card-body">
        <div>
          <div class="kpi-label">Fee / Volume</div>
          <div class="kpi-value" style="font-size:18px;"><?= (int)$sumFee ?> / <?= (int)$sumVol ?></div>
          <div class="kpi-mini">Total fee & volume</div>
        </div>
        <div class="kpi-icon-soft"><i class="fa-solid fa-coins"></i></div>
      </div>
    </div>
  </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-3">
  <div class="col-lg-6">
    <div class="card chart-card">
      <div class="card-header">
        <i class="fa-solid fa-chart-column me-2 text-primary"></i>
        Target vs Realisasi (per periode)
      </div>
      <div class="card-body">
        <div class="chart-box">
          <canvas id="trCompare"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card chart-card">
      <div class="card-header">
        <i class="fa-solid fa-chart-area me-2 text-primary"></i>
        Fee Base Income & Volume of Agent (per periode)
      </div>
      <div class="card-body">
        <div class="chart-box">
          <canvas id="feeVolCompare"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Form -->
<div class="card shadow-sm border-0 mb-3">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>
      <?= $is_edit ? 'Edit Data' : 'Add Data' ?>
    </div>

    <div class="d-flex gap-2 align-items-center">
      <?php if($is_edit): ?>
        <span class="badge text-bg-warning">Mode Edit</span>
        <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('index.php/admin/target') ?>">
          <i class="fa-solid fa-xmark me-1"></i>Cancel
        </a>
      <?php else: ?>
        <span class="badge text-bg-light">Input</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="card-body">
    <form method="post" action="<?= $action_url ?>" class="row g-2 align-items-end">
      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Periode</label>
        <input type="month" name="periode" class="form-control form-control-sm"
               value="<?= htmlspecialchars($periode_val) ?>" required>
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Target</label>
        <input type="number" name="target" class="form-control form-control-sm" min="0" required
               value="<?= $is_edit ? (int)$edit->target : '' ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Realisasi</label>
        <input type="number" name="realisasi" class="form-control form-control-sm" min="0" required
               value="<?= $is_edit ? (int)$edit->realisasi : '' ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Fee Base Income</label>
        <input type="number" name="fee_base_income" class="form-control form-control-sm" min="0" required
               value="<?= $is_edit ? (int)$edit->fee_base_income : '' ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Volume of Agent</label>
        <input type="number" name="volume_of_agent" class="form-control form-control-sm" min="0" required
               value="<?= $is_edit ? (int)$edit->volume_of_agent : '' ?>">
      </div>

      <div class="col-md-2">
        <label class="form-label small text-muted mb-1">Note</label>
        <input type="text" name="catatan" class="form-control form-control-sm"
               value="<?= $is_edit ? htmlspecialchars($edit->catatan ?? '') : '' ?>">
      </div>

      <div class="col-12 d-flex gap-2">
        <button class="btn btn-sm <?= $is_edit ? 'btn-primary' : 'btn-success' ?>">
          <i class="fa-solid <?= $is_edit ? 'fa-floppy-disk' : 'fa-plus' ?> me-1"></i>
          <?= $is_edit ? 'Update' : 'Save' ?>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Table -->
<div class="card shadow-sm border-0 table-pro">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div class="fw-semibold">
      <i class="fa-solid fa-table me-2 text-primary"></i>Data Target & Realisasi
    </div>
    <span class="badge text-bg-primary">Total: <?= isset($targets) ? count($targets) : 0 ?></span>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr class="text-nowrap">
          <th>Periode</th>
          <th>Target</th>
          <th>Realisasi</th>
          <th>Fee Base Income</th>
          <th>Volume of Agent</th>
          <th>Progress</th>
          <th>Gap</th>
          <th>Note</th>
          <th>Updated (WIB)</th>
          <th class="text-end" style="width:190px;">Action</th>
        </tr>
      </thead>

      <tbody>
        <?php if (empty($targets)): ?>
          <tr>
            <td colspan="10" class="text-center text-muted py-4">Belum ada data.</td>
          </tr>
        <?php endif; ?>

        <?php foreach($targets as $t): ?>
          <?php
            $target = (int)$t->target;
            $realisasi = (int)$t->realisasi;
            $progress = ($target > 0) ? round(($realisasi / $target) * 100, 2) : null;
            $gap = $realisasi - $target;

            // progress badge (soft)
            $progClass = 'warn';
            if ($progress === null) $progClass = 'danger';
            else if ($progress >= 100) $progClass = 'success';
            else if ($progress >= 80) $progClass = 'warn';

            // gap badge (soft)
            $gapClass = ($gap >= 0) ? 'success' : 'danger';
          ?>
          <tr class="text-nowrap">
            <td class="fw-semibold"><?= date('Y-m', strtotime($t->periode)) ?></td>
            <td><?= $target ?></td>
            <td><?= $realisasi ?></td>
            <td><?= (int)($t->fee_base_income ?? 0) ?></td>
            <td><?= (int)($t->volume_of_agent ?? 0) ?></td>

            <td>
              <?php if($progress === null): ?>
                <span class="badge-soft danger">-</span>
              <?php else: ?>
                <span class="badge-soft <?= $progClass ?>"><?= $progress ?>%</span>
              <?php endif; ?>
            </td>

            <td>
              <?php if ($gap >= 0): ?>
                <span class="badge-soft <?= $gapClass ?>">+<?= $gap ?></span>
              <?php else: ?>
                <span class="badge-soft <?= $gapClass ?>"><?= $gap ?></span>
              <?php endif; ?>
            </td>

            <td class="text-wrap text-muted">
              <?= !empty($t->catatan) ? htmlspecialchars($t->catatan) : '-' ?>
            </td>

            <td><?= !empty($t->updated_at) ? date('d-m-Y H:i', strtotime($t->updated_at)) : '-' ?></td>

            <td class="text-end">
              <div class="btn-group" role="group">
                <a class="btn btn-sm btn-warning"
                   href="<?= base_url('index.php/admin/target?edit_id='.(int)$t->id) ?>">
                  <i class="fa-solid fa-pen me-1"></i>Edit
                </a>
                <a class="btn btn-sm btn-danger"
                   href="<?= base_url('index.php/admin/target_delete/'.(int)$t->id) ?>"
                   onclick="return confirm('Yakin ingin menghapus periode <?= date('Y-m', strtotime($t->periode)) ?>?')">
                  <i class="fa-solid fa-trash me-1"></i>Delete
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const labels = <?= $chart_labels ?? '[]' ?>;

  const targetData    = <?= $chart_target ?? '[]' ?>;
  const realisasiData = <?= $chart_realisasi ?? '[]' ?>;

  const feeData = <?= $chart_fee ?? '[]' ?>;
  const volData = <?= $chart_vol ?? '[]' ?>;

  if (!labels.length) return;

  new Chart(document.getElementById('trCompare'), {
    type: 'bar',
    data: {
      labels,
      datasets: [
        { label: 'Target', data: targetData },
        { label: 'Realisasi', data: realisasiData }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  new Chart(document.getElementById('feeVolCompare'), {
    type: 'line',
    data: {
      labels,
      datasets: [
        { label: 'Fee Base Income', data: feeData, tension: 0.25 },
        { label: 'Volume of Agent', data: volData, tension: 0.25 }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: 'top' } },
      interaction: { mode: 'index', intersect: false },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
});
</script>
