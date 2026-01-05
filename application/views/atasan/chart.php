<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    .chart-box {
      height: 260px;
      position: relative;
    }

    .chart-box canvas {
      width: 100% !important;
      height: 100% !important;
      display: block;
    }
  </style>
</head>

<body class="app-body">

  <div class="app-wrap">
    <main class="app-content">

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
          <div class="fw-bold fs-5">Chart Monitoring</div>
          <div class="small text-muted">Ringkasan assessment & status tugas.</div>
        </div>
        <a href="<?= base_url('index.php/atasan') ?>" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-arrow-left"></i> Back
        </a>
      </div>

      <!-- FILTER -->
      <div class="card mb-3">
        <div class="card-body py-2">
          <form class="d-flex flex-wrap align-items-center gap-2" method="get"
            action="<?= base_url('index.php/atasan/chart') ?>">
            <div class="fw-bold small text-muted">Filter Departement</div>

            <select name="departemen_id" class="form-select form-select-sm" style="min-width:220px;">
              <option value="">All Departement</option>
              <?php foreach ($departemen as $d): ?>
                <option value="<?= $d->id ?>" <?= ((string)$filter_departemen_id === (string)$d->id ? 'selected' : '') ?>>
                  <?= htmlspecialchars($d->nama_departemen) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <button class="btn btn-sm btn-primary">
              <i class="bi bi-search"></i> Apply
            </button>
          </form>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Superior Assessment</span>
              <span class="small text-muted">Done vs Not yet</span>
            </div>
            <div class="card-body">
              <div class="chart-box"><canvas id="pieReview"></canvas></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Official Duty Status</span>
              <span class="small text-muted">Ongoing / Done / Terminated</span>
            </div>
            <div class="card-body">
              <div class="chart-box"><canvas id="barStatus"></canvas></div>
            </div>
          </div>
        </div>
      </div>

    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const commonOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            boxWidth: 10,
            boxHeight: 10
          }
        }
      }
    };

    const reviewLabels = <?= $reviewLabels ?? '[]' ?>;
    const reviewValues = <?= $reviewValues ?? '[]' ?>;
    new Chart(document.getElementById('pieReview'), {
      type: 'doughnut',
      data: {
        labels: reviewLabels,
        datasets: [{
          data: reviewValues,
          borderWidth: 1
        }]
      },
      options: {
        ...commonOptions,
        cutout: '62%'
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
          data: statusValues,
          borderWidth: 1
        }]
      },
      options: {
        ...commonOptions,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0
            }
          }
        }
      }
    });
  </script>

</body>

</html>