<!doctype html>
<html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="p-4">

<div class="container-fluid">

 

  <div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Monitoring Activity Employee</h3>

  <a href="<?= base_url('index.php/auth/logout') ?>"
     class="btn btn-sm btn-danger"
     onclick="return confirm('Yakin ingin logout?')">
     Logout
  </a>
</div>


  <!-- FILTER DIVISI -->
  <form class="form-inline mb-3" method="get" action="<?= base_url('index.php/atasan') ?>">
    <label class="mr-2">Filter Division:</label>
    <select name="divisi_id" class="form-control form-control-sm mr-2">
      <option value="">All Division</option>
      <?php foreach($divisi as $d): ?>
        <option value="<?= $d->id ?>" <?= ($filter_divisi_id == $d->id ? 'selected' : '') ?>>
          <?= $d->nama_divisi ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button class="btn btn-sm btn-primary">Apply</button>
  </form>

  <!-- TABEL -->
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
        <th>Close The Path</th>
        <th>Penilaian</th>
      </tr>
    </thead>
    <tbody>

      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="9" class="text-center text-muted">
            Belum ada aktivitas pegawai
          </td>
        </tr>
      <?php endif; ?>

      <?php foreach($rows as $r): ?>
      <tr>
        <td><?= $r->pegawai_nama ?></td>
        <td><?= $r->nama_divisi ?></td>
        <td><?= $r->nama_tugas ?></td>
        <td><?= $r->tanggal_ambil ?></td>
        <td><?= $r->status ?></td>
        <td><?= $r->activity ?? '-' ?></td>
        <td><?= $r->pending_matters ?? '-' ?></td>
        <td><?= $r->close_the_path ?? '-' ?></td>
        <td>
          <form method="post" action="<?= base_url('index.php/atasan/review_store') ?>">
            <input type="hidden" name="pegawai_tugas_id" value="<?= $r->pegawai_tugas_id ?>">
            <!-- supaya setelah simpan tetap pakai filter yang sedang dipilih -->
            <input type="hidden" name="divisi_id" value="<?= htmlspecialchars((string)$filter_divisi_id) ?>">

            <div class="form-check">
              <input class="form-check-input" type="radio" name="review_status" value="done"
                <?= ($r->review_status == 'done' ? 'checked' : '') ?> required>
              <label class="form-check-label">Done</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="radio" name="review_status" value="not_yet"
                <?= ($r->review_status == 'not_yet' ? 'checked' : '') ?>>
              <label class="form-check-label">Not yet</label>
            </div>

            <button class="btn btn-sm btn-success mt-1">Save</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>

    </tbody>
  </table>

  <!-- CHART DI BAWAH -->
  <hr class="mt-4 mb-4">
  <div id="chart" class="d-flex justify-content-between align-items-center mb-2">
    <h4 class="mb-0">Chart Monitoring</h4>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <div class="card">
        <div class="card-header">Pie Chart - Superrior Assessment</div>
        <div class="card-body">
          <canvas id="pieReview"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6 mb-3">
      <div class="card">
        <div class="card-header">Bar Chart - Official Duty Task</div>
        <div class="card-body">
          <canvas id="barStatus"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const reviewLabels = <?= $reviewLabels ?? '[]' ?>;
const reviewValues = <?= $reviewValues ?? '[]' ?>;

new Chart(document.getElementById('pieReview'), {
  type: 'pie',
  data: {
    labels: reviewLabels,
    datasets: [{ data: reviewValues }]
  }
});

const statusLabels = <?= $statusLabels ?? '[]' ?>;
const statusValues = <?= $statusValues ?? '[]' ?>;

new Chart(document.getElementById('barStatus'), {
  type: 'bar',
  data: {
    labels: statusLabels,
    datasets: [{
      label: 'Jumlah',
      data: statusValues
    }]
  },
  options: {
    scales: { y: { beginAtZero: true, precision: 0 } }
  }
});
</script>

</body>
</html>
