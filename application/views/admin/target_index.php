<?php
// application/views/admin/target_index.php
$is_edit_target = $is_edit_target ?? false;
$edit_target    = $edit_target ?? null;
$is_edit_real   = $is_edit_real ?? false;
$edit_real      = $edit_real ?? null;

// Hitung akumulasi Target dari tabel kpi_targets
$sumTarget = 0;
foreach (($targets_kpi ?? []) as $tk) {
  $sumTarget += (float)($tk->target_voa ?? 0) + (float)($tk->target_fbi ?? 0) + (float)($tk->target_transaksi ?? 0);
}

// Hitung akumulasi Realisasi dari tabel kpi_realizations
$sumRealisasi = 0;
$sumFBI = 0;
$sumVoA = 0;
foreach (($realizations ?? []) as $rl) {
  $sumRealisasi += (float)($rl->real_voa ?? 0) + (float)($rl->real_fbi ?? 0) + (float)($rl->real_transaksi ?? 0);
  $sumFBI += (float)($rl->real_fbi ?? 0);
  $sumVoA += (float)($rl->real_voa ?? 0);
}

$avgProgress = ($sumTarget > 0) ? round(($sumRealisasi / $sumTarget) * 100, 2) : 0;

$is_edit = !empty($edit);
$action_url = $is_edit
  ? base_url('index.php/admin/target_update/' . $edit->id)
  : base_url('index.php/admin/target_store');

$periode_val = $is_edit ? date('Y-m', strtotime($edit->periode)) : '';
?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <div>
    <h3 class="mb-1">Target & Realisasi</h3>
  </div>

  <div class="d-flex gap-2">
    <a href="<?= base_url('index.php/admin') ?>" class="btn btn-sm btn-outline-primary">
      <i class="fa-solid fa-house me-1"></i> Dashboard
    </a>
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

<div class="row g-3 mb-4 align-items-center">
  <div class="col-md-6">
    <div class="btn-group shadow-sm">
      <a href="?mode=day" class="btn btn-sm <?= ($current_mode ?? 'day') == 'day' ? 'btn-primary' : 'btn-outline-primary' ?>">Harian</a>
      <a href="?mode=week" class="btn btn-sm <?= ($current_mode ?? 'day') == 'week' ? 'btn-primary' : 'btn-outline-primary' ?>">Mingguan</a>
      <a href="?mode=month" class="btn btn-sm <?= ($current_mode ?? 'day') == 'month' ? 'btn-primary' : 'btn-outline-primary' ?>">Bulanan</a>
      <a href="?mode=year" class="btn btn-sm <?= ($current_mode ?? 'day') == 'year' ? 'btn-primary' : 'btn-outline-primary' ?>">Tahunan</a>
    </div>
  </div>
  <div class="col-md-6 text-md-end">
    <div class="d-inline-flex align-items-center gap-2">
      <label class="small fw-bold text-muted">Tampilkan Grafik:</label>
      <select id="chartFeatureSelect" class="form-select form-select-sm" style="width: 200px;">
        <option value="fbi" selected>Fee Base Income (FBI)</option>
        <option value="voa">Volume of Agent (VoA)</option>
        <option value="trans">Transaksi</option>
      </select>
    </div>
  </div>
</div>

<div class="card shadow-sm border-0 mb-4">
  <div class="card-header bg-white fw-bold"><i class="fa-solid fa-chart-line me-2 text-primary"></i> Grafik Performa <span id="chartTitle">FBI</span></div>
  <div class="card-body">
    <div style="height: 350px; width: 100%;"><canvas id="mainChart"></canvas></div>
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
      <?php if ($is_edit): ?>
        <span class="badge text-bg-warning">Mode Edit</span>
        <a class="btn btn-sm btn-outline-secondary" href="<?= base_url('index.php/admin/target') ?>">
          <i class="fa-solid fa-xmark me-1"></i>Cancel
        </a>
      <?php else: ?>
        <span class="badge text-bg-light">Input</span>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header bg-primary text-white">
      <h6 class="mb-0">Form Input Target KPI</h6>
    </div>
    <div class="card-body">
      <form method="post" action="<?= base_url('index.php/admin/save_target') ?>" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Periode Target</label>
          <input type="date" name="periode" class="form-control form-control-sm"
            value="<?= $is_edit_target ? $edit_target->periode : date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Target VOA</label>
          <input type="number" name="target_voa" class="form-control form-control-sm" value="<?= $is_edit_target ? $edit_target->target_voa : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Target FBI</label>
          <input type="number" name="target_fbi" class="form-control form-control-sm" value="<?= $is_edit_target ? $edit_target->target_fbi : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Target Trans</label>
          <input type="number" name="target_transaksi" class="form-control form-control-sm" value="<?= $is_edit_target ? $edit_target->target_transaksi : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Tgl Target Final</label>
          <input type="date" name="tgl_target_final" class="form-control form-control-sm" value="<?= $is_edit_target ? $edit_target->tgl_target_final : '' ?>">
        </div>
        <div class="col-12 mt-2">
          <button type="submit" class="btn btn-sm btn-primary">Simpan Target</button>
        </div>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-header bg-success text-white">
      <h6 class="mb-0">Form Input Realisasi Harian</h6>
    </div>
    <div class="card-body">
      <form method="post" action="<?= base_url('index.php/admin/save_realization') ?>" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Tanggal Realisasi</label>
          <input type="date" name="periode" class="form-control form-control-sm"
            value="<?= $is_edit_real ? $edit_real->periode : date('Y-m-d') ?>" required>
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Real VOA</label>
          <input type="number" name="real_voa" class="form-control form-control-sm" value="<?= $is_edit_real ? $edit_real->real_voa : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Real FBI</label>
          <input type="number" name="real_fbi" class="form-control form-control-sm" value="<?= $is_edit_real ? $edit_real->real_fbi : '' ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label small text-muted mb-1">Real Trans</label>
          <input type="number" name="real_transaksi" class="form-control form-control-sm" value="<?= $is_edit_real ? $edit_real->real_transaksi : '' ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label small text-muted mb-1">Note / Catatan</label>
          <textarea name="catatan" class="form-control form-control-sm" rows="1"><?= $is_edit_real ? htmlspecialchars($edit_real->catatan ?? '', ENT_QUOTES, 'UTF-8') : '' ?></textarea>
        </div>
        <div class="col-12 mt-2">
          <button type="submit" class="btn btn-sm btn-success">Simpan Realisasi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<<div class="row">
  <div class="col-md-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fa-solid fa-bullseye me-2"></i>Daftar Target Bulanan</h6>
      </div>
      <div class="table-responsive" style="max-height: 400px;">
        <table class="table table-sm table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Periode</th>
              <th>Rincian Target (VoA / FBI / Trans)</th>
              <th class="text-end">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($targets_kpi)): ?>
              <tr>
                <td colspan="3" class="text-center py-3 text-muted small">Belum ada target diinput.</td>
              </tr>
            <?php endif; ?>
            <?php foreach ($targets_kpi as $tk): ?>
              <tr>
                <td class="fw-bold text-nowrap"><?= date('M Y', strtotime($tk->periode)) ?></td>
                <td>
                  <div class="d-flex flex-column small">
                    <span><b class="text-success">VoA:</b> <?= number_format($tk->target_voa, 0, ',', '.') ?></span>
                    <span><b class="text-warning">FBI:</b> <?= number_format($tk->target_fbi, 0, ',', '.') ?></span>
                    <span><b class="text-primary">Trans:</b> <?= number_format($tk->target_transaksi, 0, ',', '.') ?></span>
                  </div>
                </td>
                <td class="text-end">
                  <div class="btn-group">
                    <a href="<?= base_url('index.php/admin/target?edit_target_id=' . (int)$tk->id) ?>" class="btn btn-xs btn-outline-warning">
                      <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="<?= base_url('index.php/admin/delete_target/' . (int)$tk->id) ?>"
                      class="btn btn-xs btn-outline-danger" onclick="return confirm('Hapus Target Bulanan ini?')">
                      <i class="fa-solid fa-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-7 mb-3">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-success text-white">
        <h6 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Riwayat Realisasi Harian</h6>
      </div>
      <div class="table-responsive" style="max-height: 400px;">
        <table class="table table-sm table-hover align-middle mb-0">
          <thead class="table-light">
            <tr class="text-nowrap small text-center">
              <th>Tanggal</th>
              <th>Realisasi VoA</th>
              <th>Realisasi FBI</th>
              <th>Realisasi Trans</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($realizations)): ?>
              <tr>
                <td colspan="5" class="text-center py-3 text-muted small">Belum ada realisasi diinput.</td>
              </tr>
            <?php endif; ?>
            <?php foreach ($realizations as $rl): ?>
              <tr class="text-center small">
                <td class="fw-semibold"><?= date('d/m/y', strtotime($rl->periode)) ?></td>
                <td class="text-end pe-3"><?= number_format($rl->real_voa, 0, ',', '.') ?></td>
                <td class="text-end pe-3"><?= number_format($rl->real_fbi, 0, ',', '.') ?></td>
                <td class="text-end pe-3"><?= number_format($rl->real_transaksi, 0, ',', '.') ?></td>
                <td class="text-start text-muted"><?= htmlspecialchars($rl->catatan ?? '-') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </div>

  <!-- Table -->
  <div class="card shadow-sm mb-4 border-0 table-pro">
    <div class="card-header d-flex align-items-center justify-content-between">
      <div class="fw-semibold">
        <i class="fa-solid fa-table me-2 text-primary"></i>Riwayat Target & Realisasi
      </div>
      <span class="badge text-bg-primary">Total Periode: <?= isset($targets) ? count($targets) : 0 ?></span>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle mb-0 text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th style="width: 140px;">Tanggal</th>
            <th style="width: 120px;">Kategori</th>
            <th>Target</th>
            <th>Realisasi</th>
            <th>Progress</th>
            <th>Gap Total</th>
            <th>Tgl Target Final</th>
            <th>Note</th>
            <th style="width: 100px;">Action</th>
          </tr>
        </thead>

        <tbody>
          <?php if (empty($targets)): ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-4">Belum ada data realisasi.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($targets as $t): ?>
              <?php
              $m = date('Y-m', strtotime($t->periode));
              // Mencari data target bulanan yang sesuai
              $t_row = $this->db->get_where('kpi_targets', ['DATE_FORMAT(periode, "%Y-%m") =' => $m])->row();

              $kategori = [
                'Transaksi' => ['t' => ($t_row->target_transaksi ?? 0), 'r' => $t->real_transaksi],
                'FBI'       => ['t' => ($t_row->target_fbi ?? 0),       'r' => $t->real_fbi],
                'VoA'       => ['t' => ($t_row->target_voa ?? 0),       'r' => $t->real_voa]
              ];

              $first = true;
              foreach ($kategori as $label => $val):
                $target = (float)($val['t'] ?? 0);
                $real   = (float)($val['r'] ?? 0);
                $prog   = ($target > 0) ? round(($real / $target) * 100, 2) : 0;
                $gap    = $real - $target;
                $progClass = ($prog >= 100) ? 'success' : ($prog >= 80 ? 'warn' : 'danger');
              ?>
                <tr class="text-nowrap">
                  <?php if ($first): ?>
                    <td rowspan="3" class="fw-bold bg-white">
                      <?= date('d M Y', strtotime($t->periode)) ?>
                      <div class="small fw-normal text-muted mt-1">
                        <i class="fa-regular fa-clock me-1"></i>
                        <?= isset($t->created_at) ? date('H:i', strtotime($t->created_at)) : '-' ?> WIB
                      </div>
                    </td>
                  <?php endif; ?>

                  <td class="text-start ps-3 fw-semibold bg-light"><?= $label ?></td>
                  <td class="text-end pe-3"><?= number_format($target, 0, ',', '.') ?></td>
                  <td class="text-end pe-3"><?= number_format($real, 0, ',', '.') ?></td>
                  <td><span class="badge-soft <?= $progClass ?>"><?= $prog ?>%</span></td>
                  <td class="fw-bold <?= ($gap >= 0) ? 'text-success' : 'text-danger' ?>">
                    <?= ($gap >= 0 ? '+' : '') . number_format($gap, 0, ',', '.') ?>
                  </td>

                  <?php if ($first): ?>
                    <td rowspan="3">
                      <?= (!empty($t_row) && isset($t_row->tgl_target_final)) ? date('d-m-Y', strtotime($t_row->tgl_target_final)) : '-' ?>
                    </td>
                    <td rowspan="3" class="text-wrap small text-muted">
                      <?= htmlspecialchars($t->catatan ?? '-') ?> </td>
                    <td rowspan="3">
                      <div class="d-flex flex-column gap-1">
                        <?php if ($t_row): ?>
                          <a href="<?= base_url('index.php/admin/target?edit_id=' . (int)$t_row->id) ?>" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen"></i> Edit Target
                          </a>
                        <?php endif; ?>

                        <a href="<?= base_url('index.php/admin/delete_realization/' . (int)$t->id) ?>"
                          class="btn btn-sm btn-danger"
                          onclick="return confirm('Hapus realisasi tanggal <?= date('d/m/Y', strtotime($t->periode)) ?>?')">
                          <i class="fa-solid fa-trash"></i> Delete Real
                        </a>
                      </div>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php $first = false;
              endforeach; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    </table>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const labels = <?= $chart_labels ?? '[]' ?>;
      const allData = {
        fbi: <?= $c_fbi ?? '{"t":[],"r":[]}' ?>,
        voa: <?= $c_voa ?? '{"t":[],"r":[]}' ?>,
        trans: <?= $c_trans ?? '{"t":[],"r":[]}' ?>
      };

      if (!labels.length) return;

      const featureInfo = {
        fbi: {
          lbl: 'FBI',
          col: '#ffc107'
        },
        voa: {
          lbl: 'VoA',
          col: '#198754'
        },
        trans: {
          lbl: 'Transaksi',
          col: '#0d6efd'
        }
      };
      const ctx = document.getElementById('mainChart').getContext('2d');
      let myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: 'Target',
              data: allData.fbi.t,
              borderColor: '#ccc',
              fill: false,
              tension: 0.3
            },
            {
              label: 'Realisasi FBI',
              data: allData.fbi.r,
              backgroundColor: 'rgba(255, 193, 7, 0.2)',
              borderColor: '#ffc107',
              fill: true,
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });

      document.getElementById('chartFeatureSelect').addEventListener('change', function() {
        const f = this.value;
        document.getElementById('chartTitle').innerText = featureInfo[f].lbl;
        myChart.data.datasets[0].data = allData[f].t;
        myChart.data.datasets[1].data = allData[f].r;
        myChart.data.datasets[1].label = 'Realisasi ' + featureInfo[f].lbl;
        myChart.data.datasets[1].borderColor = featureInfo[f].col;
        myChart.update();
      });

      document.getElementById('tableFeatureSelect').addEventListener('change', function() {
        const val = this.value;
        document.querySelectorAll('#targetTable tbody tr').forEach(tr => {
          tr.style.display = (val === 'all' || tr.dataset.feature === val) ? '' : 'none';
        });
      });
    });
  </script>