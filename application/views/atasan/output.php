<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* ===== Page spacing ===== */
    body { background:#f7f7f7; }
    .section-title { font-weight:600; }

    /* ===== Chart: hemat space ===== */
    .chart-card{
      border: 1px solid #e5e7eb;
      border-radius: 6px;
      overflow: hidden;
      background:#fff;
    }
    .chart-card .card-header{
      padding: .4rem .75rem;
      font-weight: 600;
      background: #fff;
    }
    .chart-card .card-body{
      padding: .5rem .75rem;
    }
    .chart-box{
      height: 200px; /* ✅ kecilin chart disini */
      position: relative;
    }
    .chart-box canvas{
      width: 100% !important;
      height: 100% !important;
      display: block;
    }

    /* Table */
    td, th { vertical-align: top !important; }
  </style>
</head>

<body class="p-3">
<?php date_default_timezone_set('Asia/Jakarta'); ?>

<div class="container-fluid">

  <!-- HEADER -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Monitoring Activity Employee</h4>
    <a href="<?= base_url('index.php/auth/logout') ?>"
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin ingin logout?')">
       Logout
    </a>
  </div>

  <!-- =========================
       CHART MONITORING (di atas)
       ========================= -->
  <div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <div class="section-title">📊 Chart Monitoring</div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="card chart-card">
          <div class="card-header">Superior Assessment</div>
          <div class="card-body">
            <div class="chart-box">
              <canvas id="pieReview"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="card chart-card">
          <div class="card-header">Official Duty Task Status</div>
          <div class="card-body">
            <div class="chart-box">
              <canvas id="barStatus"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FILTER -->
  <div class="card mb-3">
    <div class="card-body py-2">
      <form class="form-inline" method="get" action="<?= base_url('index.php/atasan') ?>">
        <label class="mr-2 mb-0">Filter Division:</label>
        <select name="divisi_id" class="form-control form-control-sm mr-2">
          <option value="">All Division</option>
          <?php foreach($divisi as $d): ?>
            <option value="<?= $d->id ?>" <?= ((string)$filter_divisi_id === (string)$d->id ? 'selected' : '') ?>>
              <?= $d->nama_divisi ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-sm btn-primary">Apply</button>
      </form>
    </div>
  </div>

  <!-- FLASH -->
  <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success py-2"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>
  <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger py-2"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <!-- =========================
       TABEL MONITORING
       ========================= -->
  <div class="card mb-3">
    <div class="card-header bg-white section-title">Monitoring Table</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="thead-light">
            <tr>
              <th>Employee</th>
              <th>Division</th>
              <th>Task</th>
              <th>Date</th>
              <th>Status</th>
              <th>Activity</th>
              <th>Pending</th>
              <th>Clear Path</th>
              <th>Assessment</th>
              <th>Last Update (WIB)</th>
            </tr>
          </thead>
          <tbody>

            <?php if (empty($rows)): ?>
              <tr>
                <td colspan="10" class="text-center text-muted py-3">Belum ada aktivitas pegawai</td>
              </tr>
            <?php endif; ?>

            <?php foreach($rows as $r): ?>
            <tr>
              <td><?= htmlspecialchars($r->pegawai_nama) ?></td>
              <td><?= htmlspecialchars($r->nama_divisi) ?></td>
              <td><?= htmlspecialchars($r->nama_tugas) ?></td>
              <td><?= htmlspecialchars($r->tanggal_ambil) ?></td>
              <td><?= htmlspecialchars($r->status) ?></td>
              <td><?= !empty($r->activity) ? htmlspecialchars($r->activity) : '-' ?></td>
              <td><?= !empty($r->pending_matters) ? htmlspecialchars($r->pending_matters) : '-' ?></td>
              <td><?= !empty($r->close_the_path) ? htmlspecialchars($r->close_the_path) : '-' ?></td>

              <!-- ASSESSMENT -->
              <td style="min-width:140px;">
                <?php $isTerminated = ($r->status === 'terminated'); ?>
                <form method="post"
                      action="<?= base_url('index.php/atasan/review_store') ?>"
                      class="review-form">

                  <input type="hidden" name="pegawai_tugas_id" value="<?= (int)$r->pegawai_tugas_id ?>">
                  <input type="hidden" name="divisi_id" value="<?= htmlspecialchars((string)$filter_divisi_id) ?>">

                  <div class="form-check">
                    <input class="form-check-input review-radio"
                           type="radio"
                           name="review_status"
                           value="done"
                           <?= ($r->review_status == 'done' ? 'checked' : '') ?>
                           <?= ($isTerminated ? 'disabled' : '') ?>
                           required>
                    <label class="form-check-label">Done</label>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input review-radio"
                           type="radio"
                           name="review_status"
                           value="not_yet"
                           <?= ($r->review_status == 'not_yet' ? 'checked' : '') ?>
                           <?= ($isTerminated ? 'disabled' : '') ?>>
                    <label class="form-check-label">Not yet</label>
                  </div>

                  <button type="submit"
                          class="btn btn-sm btn-success mt-1 review-save"
                          <?= ($isTerminated ? 'disabled' : '') ?>
                          disabled>
                    Save
                  </button>
                </form>

                <?php if (!$isTerminated): ?>
                  <a href="<?= base_url('index.php/atasan/terminate/'.$r->pegawai_tugas_id) ?>"
                     class="btn btn-sm btn-outline-danger mt-1"
                     onclick="return confirm('Yakin ingin terminate tugas ini?')">
                     Terminate
                  </a>
                <?php else: ?>
                  <span class="badge badge-danger mt-1">TERMINATED</span>
                <?php endif; ?>
              </td>

              <td>
                <?= !empty($r->last_update) ? date('d-m-Y H:i', strtotime($r->last_update)) : '-' ?>
              </td>
            </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- =========================
       GOALS TABLE (READ ONLY)
       ========================= -->
  <div class="card mb-3">
    <div class="card-header bg-white section-title">Goals (Employee)</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="thead-light">
            <tr>
              <th>Employee</th>
              <th>Division</th>
              <th>Task</th>
              <th>Status</th>
              <th>Goals</th>
              <th>Last Update (WIB)</th>
            </tr>
          </thead>
          <tbody>
            <?php $goals_rows = $goals_rows ?? []; ?>
            <?php if (empty($goals_rows)): ?>
              <tr><td colspan="6" class="text-center text-muted py-3">Belum ada goals.</td></tr>
            <?php endif; ?>

            <?php foreach($goals_rows as $g): ?>
              <?php $lu = !empty($g->updated_at) ? $g->updated_at : $g->created_at; ?>
              <tr>
                <td><?= htmlspecialchars($g->pegawai_nama) ?></td>
                <td><?= htmlspecialchars($g->nama_divisi) ?></td>
                <td><?= htmlspecialchars($g->nama_tugas) ?></td>
                <td><?= htmlspecialchars($g->status) ?></td>
                <td style="white-space:pre-wrap;"><?= htmlspecialchars($g->goals) ?></td>
                <td><?= $lu ? date('d-m-Y H:i', strtotime($lu)) : '-' ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php
function rupiah($angka) {
  return number_format((int)$angka, 0, ',', '.');
}
?>

<hr class="mt-4 mb-3">
<h5 class="mb-2">📈 Perbandingan Target & Realisasi</h5>

<div class="row">
  <div class="col-md-6 mb-3">
    <div class="card">
      <div class="card-header py-2 font-weight-bold">Target vs Realisasi</div>
      <div class="card-body p-2">
        <div style="height:220px;">
          <canvas id="trCompare"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-3">
    <div class="card">
      <div class="card-header py-2 font-weight-bold">Fee & Volume</div>
      <div class="card-body p-2">
        <div style="height:220px;">
          <canvas id="feeVolCompare"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>


<?php
  // KPI ringkas
  $sumTarget = 0; $sumRealisasi = 0; $sumFee = 0; $sumVol = 0;
  foreach(($targets ?? []) as $t){
    $sumTarget += (int)$t->target;
    $sumRealisasi += (int)$t->realisasi;
    $sumFee += (int)($t->fee_base_income ?? 0);
    $sumVol += (int)($t->volume_of_agent ?? 0);
  }
  $avgProgress = ($sumTarget > 0) ? round(($sumRealisasi / $sumTarget) * 100, 2) : 0;
?>

<div class="row mb-3">
  <div class="col-md-3 mb-2">
    <div class="card">
      <div class="card-body p-2">
        <div class="small text-muted">Total Target</div>
        <div class="h5 mb-0"><?= $sumTarget ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-2">
    <div class="card">
      <div class="card-body p-2">
        <div class="small text-muted">Total Realisasi</div>
        <div class="h5 mb-0"><?= $sumRealisasi ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-2">
    <div class="card">
      <div class="card-body p-2">
        <div class="small text-muted">Avg Progress</div>
        <div class="h5 mb-0"><?= $avgProgress ?>%</div>
      </div>
    </div>
  </div>
  <div class="col-md-3 mb-2">
    <div class="card">
      <div class="card-body p-2">
        <div class="small text-muted">Total Fee / Total Volume</div>
        <div class="h6 mb-0"><?= $sumFee ?> / <?= $sumVol ?></div>
      </div>
    </div>
  </div>
</div>


<script>
const labels = <?= $chart_labels ?? '[]' ?>;

const targetData = <?= $chart_target ?? '[]' ?>;
const realisasiData = <?= $chart_realisasi ?? '[]' ?>;

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
    scales: { y: { beginAtZero: true, precision: 0 } }
  }
});

const feeData = <?= $chart_fee ?? '[]' ?>;
const volData = <?= $chart_vol ?? '[]' ?>;

new Chart(document.getElementById('feeVolCompare'), {
  type: 'line',
  data: {
    labels,
    datasets: [
      { label: 'Fee Base Income', data: feeData, tension: 0.2 },
      { label: 'Volume of Agent', data: volData, tension: 0.2 }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true, precision: 0 } }
  }
});
</script>

  <!-- =========================
     TARGET & REALISASI (READ ONLY)
     ========================= -->
<hr class="mt-4 mb-4">
<h5 class="mb-2">Target & Realisasi</h5>

<div class="table-responsive">
  <table class="table table-bordered table-sm">
    <thead class="thead-light">
      <tr>
        <th>Periode</th>
        <th>Target</th>
        <th>Realisasi</th>
        <th>Fee Base Income</th>
        <th>Volume of Agent</th>
        <th>Progress (%)</th>
        <th>Gap</th>
        <th>Note</th>
        <th>Updated (WIB)</th>
      </tr>
    </thead>
    <tbody>
<?php if (empty($targets)): ?>
  <tr>
    <td colspan="10" class="text-center text-muted">Belum ada data target.</td>
  </tr>
<?php endif; ?>

<?php if (!empty($targets)): foreach($targets as $t): ?>
  <?php
    $target    = (int)($t->target ?? 0);
    $realisasi = (int)($t->realisasi ?? 0);
    $feeBase   = (int)($t->fee_base_income ?? 0);
    $volume    = (int)($t->volume_of_agent ?? 0);

    $progress = ($target > 0) ? round(($realisasi / $target) * 100, 2) : null;
    $gap      = $realisasi - $target;
  ?>
  <tr>
  <td><?= date('Y-m', strtotime($t->periode)) ?></td>

  <td><?= rupiah($target) ?></td>
  <td><?= rupiah($realisasi) ?></td>
  <td><?= rupiah($feeBase) ?></td>
  <td><?= rupiah($volume) ?></td>

  <td>
    <?php if ($progress !== null): ?>
      <span class="badge badge-info"><?= $progress ?>%</span>
    <?php else: ?>-
    <?php endif; ?>
  </td>

  <td>
    <?php if ($gap < 0): ?>
      <span class="text-danger"><?= rupiah($gap) ?></span>
    <?php else: ?>
      <span class="text-success"><?= rupiah($gap) ?></span>
    <?php endif; ?>
  </td>

  <td><?= $t->catatan ?: '-' ?></td>
  <td><?= date('d-m-Y H:i', strtotime($t->updated_at)) ?></td>
</tr>

<?php endforeach; endif; ?>
</tbody>

  </table>
</div>

    </div>
  </div>

</div>

<script>
/* UX tombol Save assessment: aktif kalau berubah */
document.querySelectorAll('.review-form').forEach(form => {
  const btn = form.querySelector('.review-save');

  const getSelected = () => {
    const checked = form.querySelector('.review-radio:checked');
    return checked ? checked.value : '';
  };

  const original = getSelected();
  const updateBtn = () => { btn.disabled = (getSelected() === original); };

  form.querySelectorAll('.review-radio').forEach(r => r.addEventListener('change', updateBtn));
  updateBtn();
});

/* Chart.js (responsive + fixed height) */
const reviewLabels = <?= $reviewLabels ?? '[]' ?>;
const reviewValues = <?= $reviewValues ?? '[]' ?>;

new Chart(document.getElementById('pieReview'), {
  type: 'pie',
  data: { labels: reviewLabels, datasets: [{ data: reviewValues }] },
  options: { responsive: true, maintainAspectRatio: false }
});

const statusLabels = <?= $statusLabels ?? '[]' ?>;
const statusValues = <?= $statusValues ?? '[]' ?>;

new Chart(document.getElementById('barStatus'), {
  type: 'bar',
  data: { labels: statusLabels, datasets: [{ label: 'Jumlah', data: statusValues }] },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: { y: { beginAtZero: true, precision: 0 } }
  }
});
</script>

</body>
</html>
