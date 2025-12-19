<!doctype html>
<html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="p-4">
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center">
    <h3>Chart Monitoring</h3>
    <a href="<?= base_url('index.php/atasan') ?>" class="btn btn-sm btn-outline-secondary">Back</a>
  </div>

  <!-- FILTER DIVISI -->
  <form class="form-inline mt-3" method="get" action="<?= base_url('index.php/atasan/chart') ?>">
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

  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Pie Chart - Superior Assessment</div>
        <div class="card-body">
          <canvas id="pieReview"></canvas>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Bar Chart - Official Duty Status</div>
        <div class="card-body">
          <canvas id="barStatus"></canvas>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
const reviewLabels = <?= $reviewLabels ?>;
const reviewValues = <?= $reviewValues ?>;

new Chart(document.getElementById('pieReview'), {
  type: 'pie',
  data: {
    labels: reviewLabels,
    datasets: [{ data: reviewValues }]
  }
});

const statusLabels = <?= $statusLabels ?>;
const statusValues = <?= $statusValues ?>;

new Chart(document.getElementById('barStatus'), {
  type: 'bar',
  data: {
    labels: statusLabels,
    datasets: [{
      label: 'Jumlah',     // <-- tambah ini biar tidak "undefined"
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
