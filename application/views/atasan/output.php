<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Admin CSS kamu -->
  <link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <style>
    /* tambahan kecil khusus halaman ini */
    .section-title {
      font-weight: 900;
    }

    .chart-box {
      height: 220px;
      position: relative;
    }

    .chart-box canvas {
      width: 100% !important;
      height: 100% !important;
      display: block;
    }

    .kpi .label {
      font-size: 12px;
      color: var(--btn-muted);
      font-weight: 900;
    }

    .kpi .value {
      font-size: 18px;
      font-weight: 900;
    }
  </style>
</head>

<body class="app-body">
  <?php date_default_timezone_set('Asia/Jakarta'); ?>

  <div class="app-wrap">
    <main class="app-content">

      <!-- HEADER -->
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
          <div class="fs-5 fw-bold">Monitoring Activity Employee</div>
          <div class="small text-muted">Pantau task, assessment, goals & performa target vs realisasi.</div>
        </div>

        <div class="d-flex gap-2">
          <!-- tombol ke chart monitoring -->
          <a href="<?= base_url('index.php/atasan/chart' . ($filter_departemen_id ? '?departemen_id=' . $filter_departemen_id : '')) ?>"
            class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pie-chart"></i> Chart Monitoring
          </a>

          <a href="<?= base_url('index.php/auth/logout') ?>"
            class="btn btn-sm btn-danger"
            onclick="return confirm('Yakin ingin logout?')">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </div>
      </div>

      <!-- FILTER -->
      <div class="card mb-3">
        <div class="card-body py-2">
          <form class="d-flex flex-wrap align-items-center gap-2" method="get" action="<?= base_url('index.php/atasan') ?>">
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

            <?php if ($filter_departemen_id !== null && $filter_departemen_id !== ''): ?>
              <a class="btn btn-sm btn-outline-primary" href="<?= base_url('index.php/atasan') ?>">
                Reset
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>

      <hr class="mt-4 mb-3">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0" id="target">📈 Perbandingan Target & Realisasi</h5>
        <span class="small text-muted">Periode berdasarkan data Laporan Bulanan</span>
      </div>

      <!-- KPI -->
      <div class="row g-3 my-2">
        <div class="col-md-3">
          <div class="card kpi">
            <div class="card-body p-2">
              <div class="label">Total Target</div>
              <div class="value"><?= rupiah($sumTarget ?? 0) ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card kpi">
            <div class="card-body p-2">
              <div class="label">Total Realisasi</div>
              <div class="value"><?= rupiah($sumRealisasi ?? 0) ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card kpi">
            <div class="card-body p-2">
              <div class="label">Avg Progress</div>
              <div class="value"><?= ($avgProgress ?? 0) ?>%</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card kpi">
            <div class="card-body p-2">
              <div class="label">Total Fee / Total Volume</div>
              <div class="value" style="font-size:14px;"><?= rupiah($sumFee ?? 0) ?> / <?= rupiah($sumVol ?? 0) ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- CHART TARGET -->
      <div class="row g-3 mb-3">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header">Target vs Realisasi</div>
            <div class="card-body">
              <div class="chart-box"><canvas id="trCompare"></canvas></div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header">Fee & Volume</div>
            <div class="card-body">
              <div class="chart-box"><canvas id="feeVolCompare"></canvas></div>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header bg-white">
          <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <button class="nav-link active fw-bold" id="transaksi-tab" data-bs-toggle="tab" data-bs-target="#transaksi-pane" type="button" role="tab">Transaksi</button>
            </li>
            <li class="nav-item">
              <button class="nav-link fw-bold" id="voa-tab" data-bs-toggle="tab" data-bs-target="#voa-pane" type="button" role="tab">VOA (Volume of Account)</button>
            </li>
            <li class="nav-item">
              <button class="nav-link fw-bold" id="fbi-tab" data-bs-toggle="tab" data-bs-target="#fbi-pane" type="button" role="tab">FBI (Fee Base Income)</button>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content">

            <div class="tab-pane fade show active" id="transaksi-pane" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-4">
                  <table class="table table-sm table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th>Bulan</th>
                        <th>Nilai Transaksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($targets as $t): ?>
                        <tr>
                          <td><?= date('M Y', strtotime($t->periode)) ?></td>
                          <td><?= rupiah($t->transaksi ?? 0) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-8">
                  <div class="chart-box"><canvas id="chartTransaksi"></canvas></div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="voa-pane" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-4">
                  <table class="table table-sm table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th>Bulan</th>
                        <th>Volume Account</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($targets as $t): ?>
                        <tr>
                          <td><?= date('M Y', strtotime($t->periode)) ?></td>
                          <td><?= rupiah($t->volume_of_account ?? 0) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-8">
                  <div class="chart-box"><canvas id="chartVOA"></canvas></div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="fbi-pane" role="tabpanel">
              <div class="row g-3">
                <div class="col-md-4">
                  <table class="table table-sm table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th>Bulan</th>
                        <th>Fee Base Income</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($targets as $t): ?>
                        <tr>
                          <td><?= date('M Y', strtotime($t->periode)) ?></td>
                          <td><?= rupiah($t->fee_base_income ?? 0) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <div class="col-md-8">
                  <div class="chart-box"><canvas id="chartFBI"></canvas></div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- FLASH -->
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success py-2"><?= $this->session->flashdata('success') ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger py-2"><?= $this->session->flashdata('error') ?></div>
      <?php endif; ?>

      <!-- =========================
         TABEL MONITORING
         ========================= -->
      <div class="card mb-3">
        <div class="card-header bg-white section-title d-flex align-items-center justify-content-between">
          <span>Monitoring Table</span>
          <span class="small text-muted">Assessment dapat diubah sebelum terminated.</span>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Employee</th>
                  <th>Departement</th>
                  <th>Task</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Activity</th>
                  <th>Pending</th>
                  <th>Clear Path</th>
                  <th>Assessment</th>
                  <th class="text-nowrap">Last Update (WIB)</th>
                </tr>
              </thead>
              <tbody>

                <?php if (empty($rows)): ?>
                  <tr>
                    <td colspan="10" class="text-center text-muted py-4">Belum ada aktivitas pegawai</td>
                  </tr>
                <?php endif; ?>

                <?php foreach ($rows as $r): ?>
                  <?php
                  $isTerminated = ($r->status === 'terminated');
                  $badge =
                    $r->status === 'done' ? 'bg-success' : ($r->status === 'on going' ? 'bg-primary' : ($r->status === 'terminated' ? 'bg-danger' : 'bg-secondary'));
                  ?>
                  <tr>
                    <td class="fw-bold"><?= htmlspecialchars($r->pegawai_nama) ?></td>
                    <td><?= htmlspecialchars($r->nama_departemen) ?></td>
                    <td><?= htmlspecialchars($r->nama_tugas) ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($r->tanggal_ambil) ?></td>

                    <td><span class="badge <?= $badge ?>"><?= htmlspecialchars($r->status) ?></span></td>

                    <td><?= !empty($r->activity) ? htmlspecialchars($r->activity) : '<span class="text-muted">-</span>' ?></td>
                    <td><?= !empty($r->pending_matters) ? htmlspecialchars($r->pending_matters) : '<span class="text-muted">-</span>' ?></td>
                    <td><?= !empty($r->close_the_path) ? htmlspecialchars($r->close_the_path) : '<span class="text-muted">-</span>' ?></td>

                    <!-- ASSESSMENT -->
                    <td style="min-width: 210px;">
                      <form method="post" action="<?= base_url('index.php/atasan/review_store') ?>" class="review-form">
                        <input type="hidden" name="pegawai_tugas_id" value="<?= (int)$r->pegawai_tugas_id ?>">
                        <input type="hidden" name="departemen_id" value="<?= htmlspecialchars((string)$filter_departemen_id) ?>">

                        <div class="d-flex gap-3">
                          <div class="form-check">
                            <input class="form-check-input review-radio" type="radio" name="review_status" value="done"
                              <?= ($r->review_status == 'done' ? 'checked' : '') ?>
                              <?= ($isTerminated ? 'disabled' : '') ?> required>
                            <label class="form-check-label">Done</label>
                          </div>

                          <div class="form-check">
                            <input class="form-check-input review-radio" type="radio" name="review_status" value="not_yet"
                              <?= ($r->review_status == 'not_yet' ? 'checked' : '') ?>
                              <?= ($isTerminated ? 'disabled' : '') ?>>
                            <label class="form-check-label">Not yet</label>
                          </div>
                        </div>

                        <div class="d-flex gap-2 mt-2">
                          <button type="submit"
                            class="btn btn-sm btn-primary review-save"
                            <?= ($isTerminated ? 'disabled' : '') ?>
                            disabled>
                            <i class="bi bi-save"></i> Save
                          </button>

                          <?php if (!$isTerminated): ?>
                            <a href="<?= base_url('index.php/atasan/terminate/' . $r->pegawai_tugas_id) . ($filter_departemen_id ? '?departemen_id=' . $filter_departemen_id : '') ?>"
                              class="btn btn-sm btn-outline-danger"
                              onclick="return confirm('Yakin ingin terminate tugas ini?')">
                              <i class="bi bi-x-circle"></i> Terminate
                            </a>
                          <?php else: ?>
                            <span class="badge bg-danger align-self-center">TERMINATED</span>
                          <?php endif; ?>
                        </div>
                      </form>
                    </td>

                    <td class="text-nowrap">
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
            <table class="table table-hover table-sm mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Employee</th>
                  <th>Departement</th>
                  <th>Task</th>
                  <th>Status</th>
                  <th>Goals</th>
                  <th class="text-nowrap">Last Update (WIB)</th>
                </tr>
              </thead>
              <tbody>
                <?php $goals_rows = $goals_rows ?? []; ?>
                <?php if (empty($goals_rows)): ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada goals.</td>
                  </tr>
                <?php endif; ?>

                <?php foreach ($goals_rows as $g): ?>
                  <?php $lu = !empty($g->updated_at) ? $g->updated_at : $g->created_at; ?>
                  <tr>
                    <td class="fw-bold"><?= htmlspecialchars($g->pegawai_nama) ?></td>
                    <td><?= htmlspecialchars($g->nama_departemen) ?></td>
                    <td><?= htmlspecialchars($g->nama_tugas) ?></td>
                    <td><?= htmlspecialchars($g->status) ?></td>
                    <td style="white-space:pre-wrap;"><?= htmlspecialchars($g->goals) ?></td>
                    <td class="text-nowrap"><?= $lu ? date('d-m-Y H:i', strtotime($lu)) : '-' ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <?php
      function rupiah($angka)
      {
        return number_format((int)$angka, 0, ',', '.');
      }
      ?>



      <!-- TABLE TARGET -->
      <div class="card mb-4">
        <div class="card-header bg-white section-title">Target & Realisasi</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Periode</th>
                  <th>Target</th>
                  <th>Realisasi</th>
                  <th>Transaksi</th>
                  <th>Fee Base Income</th>
                  <th>Volume of Account</th>
                  <th>Progress (%)</th>
                  <th>Gap</th>
                  <th>Note</th>
                  <th class="text-nowrap">Updated (WIB)</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($targets)): ?>
                  <tr>
                    <td colspan="9" class="text-center text-muted py-4">Belum ada data target.</td>
                  </tr>
                <?php endif; ?>

                <?php if (!empty($targets)): foreach ($targets as $t): ?>
                    <?php
                    $target    = (int)($t->target ?? 0);
                    $realisasi = (int)($t->realisasi ?? 0);
                    $transaksi = (int)($t->transaksi ?? 0);
                    $feeBase   = (int)($t->fee_base_income ?? 0);
                    $volume    = (int)($t->volume_of_account ?? 0);

                    $progress = ($target > 0) ? round(($realisasi / $target) * 100, 2) : null;
                    $gap      = $realisasi - $target;

                    $progBadge = ($progress !== null && $progress >= 100) ? 'bg-success' : 'bg-warning';
                    ?>
                    <tr>
                      <td class="text-nowrap"><?= date('Y-m', strtotime($t->periode)) ?></td>
                      <td><?= rupiah($target) ?></td>
                      <td><?= rupiah($realisasi) ?></td>
                      <td><?= rupiah($transaksi) ?></td>
                      <td><?= rupiah($feeBase) ?></td>
                      <td><?= rupiah($volume) ?></td>

                      <td>
                        <?php if ($progress !== null): ?>
                          <span class="badge <?= $progBadge ?>"><?= $progress ?>%</span>
                          <?php else: ?>-
                        <?php endif; ?>
                      </td>

                      <td>
                        <?php if ($gap < 0): ?>
                          <span class="text-danger fw-bold"><?= rupiah($gap) ?></span>
                        <?php else: ?>
                          <span class="text-success fw-bold"><?= rupiah($gap) ?></span>
                        <?php endif; ?>
                      </td>

                      <td><?= $t->catatan ?: '<span class="text-muted">-</span>' ?></td>
                      <td class="text-nowrap"><?= !empty($t->updated_at) ? date('d-m-Y H:i', strtotime($t->updated_at)) : '-' ?></td>
                    </tr>
                <?php endforeach;
                endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /* UX tombol Save assessment: aktif kalau ada perubahan */
    document.querySelectorAll('.review-form').forEach(form => {
      const btn = form.querySelector('.review-save');
      if (!btn) return;

      const getSelected = () => (form.querySelector('.review-radio:checked')?.value || '');
      const original = getSelected();
      const updateBtn = () => {
        btn.disabled = (getSelected() === original);
      };

      form.querySelectorAll('.review-radio').forEach(r => r.addEventListener('change', updateBtn));
      updateBtn();
    });

    /* ==========================================
       KONFIGURASI CHART (CHART.JS)
       ========================================== */

    // 1. Persiapan Data dari PHP
    const labels = <?= $chart_labels ?? '[]' ?>;
    const targetData = <?= $chart_target ?? '[]' ?>;
    const realisasiData = <?= $chart_realisasi ?? '[]' ?>;
    const feeData = <?= $chart_fee ?? '[]' ?>;
    const volData = <?= $chart_vol ?? '[]' ?>;
    const transData = <?= $chart_transaksi ?? '[]' ?>;

    // 2. Opsi Standar untuk Semua Chart
    const commonOptions = {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            boxWidth: 10,
            boxHeight: 10
          }
        },
        tooltip: {
          intersect: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0
          }
        }
      }
    };

    // --- CHART OVERVIEW (Yang sebelumnya hilang) ---

    // A. Target vs Realisasi (Bar Chart)
    new Chart(document.getElementById('trCompare'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
            label: 'Target',
            data: targetData,
            backgroundColor: '#0d6efd',
            borderWidth: 1
          },
          {
            label: 'Realisasi',
            data: realisasiData,
            backgroundColor: '#6c757d',
            borderWidth: 1
          }
        ]
      },
      options: commonOptions
    });

    // B. Fee & Volume Overview (Line Chart)
    new Chart(document.getElementById('feeVolCompare'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
            label: 'Fee Base Income',
            data: feeData,
            borderColor: '#ffc107',
            tension: 0.25,
            borderWidth: 2
          },
          {
            label: 'Volume of Account',
            data: volData,
            borderColor: '#198754',
            tension: 0.25,
            borderWidth: 2
          }
        ]
      },
      options: commonOptions
    });


    // --- CHART DETAIL (Di dalam TAB) ---

    // C. Detail Transaksi
    new Chart(document.getElementById('chartTransaksi'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Total Transaksi',
          data: transData,
          borderColor: '#0d6efd',
          backgroundColor: 'rgba(13, 110, 253, 0.1)',
          fill: true,
          tension: 0.3
        }]
      },
      options: commonOptions
    });

    // D. Detail VOA
    new Chart(document.getElementById('chartVOA'), {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Volume of Account',
          data: volData,
          backgroundColor: '#198754'
        }]
      },
      options: commonOptions
    });

    // E. Detail FBI
    new Chart(document.getElementById('chartFBI'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Fee Base Income',
          data: feeData,
          borderColor: '#ffc107',
          backgroundColor: 'rgba(255, 193, 7, 0.1)',
          fill: true,
          tension: 0.3
        }]
      },
      options: commonOptions
    });

    // Tambahkan ini di bagian paling bawah script Anda
    var tabEl = document.querySelectorAll('button[data-bs-toggle="tab"]')
    tabEl.forEach(function(el) {
      el.addEventListener('shown.bs.tab', function(event) {
        // Memicu resize global agar Chart.js menghitung ulang dimensi kanvas
        window.dispatchEvent(new Event('resize'));
      })
    });

    // Tambahkan ini di paling bawah bagian <script> pada output.php
    document.addEventListener('DOMContentLoaded', function() {
      var tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
      tabEls.forEach(function(el) {
        el.addEventListener('shown.bs.tab', function(event) {
          // Memicu event resize agar Chart.js menghitung ulang lebar canvas
          window.dispatchEvent(new Event('resize'));
        });
      });
    });
  </script>

</body>

</html>