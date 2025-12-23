<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="p-4">
<?php date_default_timezone_set('Asia/Jakarta'); ?>

<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Monitoring Activity Employee</h3>

    <a href="<?= base_url('index.php/auth/logout') ?>"
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin ingin logout?')">
       Logout
    </a>
  </div>

<!-- =========================
     CHART MONITORING
     ========================= -->
<div id="chart" class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">📊 Chart Monitoring</h4>
  </div>

  <div class="row">
  <div class="row chart-wrap">
  <div class="col-md-6 mb-3">
    <div class="card chart-card">
      <div class="card-header">Superior Assessment</div>
      <div class="card-body">
        <canvas id="pieReview" class="chart-canvas"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-6 mb-3">
    <div class="card chart-card">
      <div class="card-header">Official Duty Task Status</div>
      <div class="card-body">
        <canvas id="barStatus" class="chart-canvas"></canvas>
      </div>
    </div>
  </div>
</div>
</div>

<style>
  /* area chart lebih hemat space */
  .chart-wrap{ margin-bottom: 16px; }

  /* card chart fix tinggi */
  .chart-card{
    height: 350px;              /* <-- kecilin di sini */
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    overflow: hidden;
  }
  .chart-card .card-header{
    padding: .4rem .75rem;
    font-weight: 600;
    background: #fff;
  }
  .chart-card .card-body{
    padding: .5rem;
    height: calc(260px - 44px); /* header kira2 44px */
  }

  /* canvas wajib ikut tinggi card */
  .chart-canvas{
    width: 100% !important;
    height: 100% !important;
    display: block;
  }
</style>



  <!-- FILTER DIVISI -->
  <form class="form-inline mb-3" method="get" action="<?= base_url('index.php/atasan') ?>">
    <label class="mr-2">Filter Division:</label>
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

  <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>
  <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <!-- =========================
       TABEL MONITORING
       ========================= -->
  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>Employee</th>
        <th>Division</th>
        <th>Task</th>
        <th>Date</th>
        <th>Status Employee</th>
        <th>Activity</th>
        <th>Pending</th>
        <th>Clear The Path</th>
        <th>Assessment</th>
        <th>Last Update (WIB)</th>
      </tr>
    </thead>
    <tbody>

      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="10" class="text-center text-muted">
            Belum ada aktivitas pegawai
          </td>
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

        <!-- ASSESSMENT FORM -->
        <td>
          <?php $originalReview = $r->review_status ?? ''; ?>
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
                     data-original="<?= htmlspecialchars($originalReview) ?>"
                     <?= ($r->review_status == 'done' ? 'checked' : '') ?>
                     <?= ($r->status === 'terminated' ? 'disabled' : '') ?>
                     required>
              <label class="form-check-label">Done</label>
            </div>

            <div class="form-check">
              <input class="form-check-input review-radio"
                     type="radio"
                     name="review_status"
                     value="not_yet"
                     data-original="<?= htmlspecialchars($originalReview) ?>"
                     <?= ($r->review_status == 'not_yet' ? 'checked' : '') ?>
                     <?= ($r->status === 'terminated' ? 'disabled' : '') ?>>
              <label class="form-check-label">Not yet</label>
            </div>

            <button type="submit"
                    class="btn btn-sm btn-success mt-1 review-save"
                    <?= ($r->status === 'terminated' ? 'disabled' : '') ?>
                    disabled>
              Save
            </button>
          </form>

          <?php if ($r->status !== 'terminated'): ?>
            <a href="<?= base_url('index.php/atasan/terminate/'.$r->pegawai_tugas_id) ?>"
               class="btn btn-sm btn-outline-danger mt-1"
               onclick="return confirm('Yakin ingin terminate tugas ini?')">
               Terminate
            </a>
          <?php else: ?>
            <span class="badge badge-danger mt-1">TERMINATED</span>
          <?php endif; ?>
        </td>

        <!-- LAST UPDATE PEGAWAI -->
        <td>
          <?php if (!empty($r->last_update)): ?>
            <?= date('d-m-Y H:i', strtotime($r->last_update)) ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>

    </tbody>
  </table>

  <!-- =========================
       GOALS TABLE (ATASAN)
       ========================= -->
  <hr class="mt-4 mb-4">
  <div id="goals" class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="mb-0">Goals (Employee)</h4>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-sm">
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
          <tr><td colspan="6" class="text-center text-muted">Belum ada goals.</td></tr>
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

  <!-- =========================
       TARGET & REALISASI
       ========================= -->
  <hr class="mt-4 mb-4">
  <div id="target" class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="mb-0">Target & Realisasi</h4>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      <form method="post" action="<?= base_url('index.php/atasan/target_store') ?>" class="form-row">
        <input type="hidden" name="current_divisi_id" value="<?= htmlspecialchars((string)$filter_divisi_id) ?>">

        <div class="col-md-3 mb-2">
          <label class="small mb-1">Division</label>
          <select name="divisi_id" class="form-control form-control-sm">
            <option value="">All Division</option>
            <?php foreach($divisi as $d): ?>
              <option value="<?= $d->id ?>" <?= ((string)$filter_divisi_id === (string)$d->id ? 'selected' : '') ?>>
                <?= $d->nama_divisi ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-2 mb-2">
          <label class="small mb-1">Periode</label>
          <input type="month" name="periode" class="form-control form-control-sm" required>
        </div>

        <div class="col-md-2 mb-2">
          <label class="small mb-1">Target</label>
          <input type="number" name="target" class="form-control form-control-sm" min="0" required>
        </div>

        <div class="col-md-2 mb-2">
          <label class="small mb-1">Realisasi</label>
          <input type="number" name="realisasi" class="form-control form-control-sm" min="0" required>
        </div>

        <div class="col-md-3 mb-2">
          <label class="small mb-1">Note</label>
          <input type="text" name="catatan" class="form-control form-control-sm" placeholder="optional">
        </div>

        <div class="col-12">
          <button class="btn btn-sm btn-primary">Save Target</button>
        </div>
      </form>
    </div>
  </div>

  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>Periode</th>
        <th>Division</th>
        <th>Target</th>
        <th>Realisasi</th>
        <th>Progress (%)</th>
        <th>Gap</th>
        <th>Note</th>
        <th>Updated (WIB)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($targets)): ?>
        <tr><td colspan="8" class="text-center text-muted">Belum ada data target.</td></tr>
      <?php endif; ?>

      <?php if (!empty($targets)): foreach($targets as $t): ?>
        <?php
          $target = (int)$t->target;
          $realisasi = (int)$t->realisasi;
          $progress = ($target > 0) ? round(($realisasi / $target) * 100, 2) : null;
          $gap = $realisasi - $target;
        ?>
        <tr>
          <td><?= date('Y-m', strtotime($t->periode)) ?></td>
          <td><?= $t->nama_divisi ?? 'All Division' ?></td>
          <td><?= $target ?></td>
          <td><?= $realisasi ?></td>
          <td><?= ($progress !== null) ? $progress.'%' : '-' ?></td>
          <td><?= $gap ?></td>
          <td><?= $t->catatan ?? '-' ?></td>
          <td><?= !empty($t->updated_at) ? date('d-m-Y H:i', strtotime($t->updated_at)) : '-' ?></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>

<script>
/* ====== UX tombol Save assessment ====== */
document.querySelectorAll('.review-form').forEach(form => {
  const btn = form.querySelector('.review-save');
  const radios = form.querySelectorAll('.review-radio');

  const getSelected = () => {
    const checked = form.querySelector('.review-radio:checked');
    return checked ? checked.value : '';
  };
  const original = getSelected();

  const updateBtn = () => {
    // kalau terminated, biarkan disabled
    if (btn.hasAttribute('disabled') && btn.disabled && form.closest('td').innerText.includes('TERMINATED')) return;
    btn.disabled = (getSelected() === original);
  };

  radios.forEach(r => r.addEventListener('change', updateBtn));
  updateBtn();
});

/* ====== Chart ====== */
const reviewLabels = <?= $reviewLabels ?? '[]' ?>;
const reviewValues = <?= $reviewValues ?? '[]' ?>;

new Chart(document.getElementById('pieReview'), {
  type: 'pie',
  data: { labels: reviewLabels, datasets: [{ data: reviewValues }] }
});

const statusLabels = <?= $statusLabels ?? '[]' ?>;
const statusValues = <?= $statusValues ?? '[]' ?>;

new Chart(document.getElementById('barStatus'), {
  type: 'bar',
  data: {
    labels: statusLabels,
    datasets: [{ label: 'Jumlah', data: statusValues }]
  },
  options: { scales: { y: { beginAtZero: true, precision: 0 } } }
});
</script>

</body>
</html>
