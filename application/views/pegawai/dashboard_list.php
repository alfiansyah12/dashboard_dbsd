<style>
  th {
    text-align: center;
  }
</style>

<?php date_default_timezone_set('Asia/Jakarta'); ?>

<div id="top"></div>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Employee Dashboard</h3>
</div>

<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="thead-modern">
          <tr>
            <th style="width:18%;">Task</th>
            <th style="width:10%;">Date</th>
            <th style="width:10%;">Status</th>
            <th>Activity</th>
            <th>Pending</th>
            <th>Clear the Path</th>
            <th>Progress</th>
            <th style="width:12%;">Last Update</th>
            <th style="width:8%;" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-4">No Data Yet</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($rows as $r): ?>
            <?php
            // Logika Perhitungan Progress
            $target  = (int)($r->target_nilai ?? 0);
            $current = (int)($r->progress_nilai ?? 0);
            $percent = ($target > 0) ? round(($current / $target) * 100, 0) : 0;
            if ($percent > 100) $percent = 100;

            // Tampilan Teks Kosong
            $activity = !empty($r->activity) ? htmlspecialchars($r->activity) : '-';
            $pending_display = !empty($r->pending_matters)
              ? htmlspecialchars($r->pending_matters)
              : '<i class="text-muted small">None</i>';

            $path_display = !empty($r->close_the_path)
              ? htmlspecialchars($r->close_the_path)
              : '<i class="text-muted small">None</i>';

            // Format Waktu
            $lastUpdate = '-';
            if (!empty($r->updated_at) || !empty($r->created_at)) {
              $raw_date = !empty($r->updated_at) ? $r->updated_at : $r->created_at;
              $dt = new DateTime($raw_date, new DateTimeZone('Asia/Jakarta'));
              $lastUpdate = $dt->format('d/m/Y H:i');
            }
            ?>

            <tr>
              <td class="fw-bold text-dark"><?= htmlspecialchars($r->nama_tugas) ?></td>
              <td><?= date('d/m/Y', strtotime($r->tanggal_ambil)) ?></td>
              <td>
                <?php
                // Perbaikan Badge untuk Bootstrap 5 (Gunakan bg-*)
                $status_color = 'bg-info'; // Default: ON GOING
                if ($r->status == 'done') {
                  $status_color = 'bg-success';
                } elseif ($r->status == 'terminated') {
                  $status_color = 'bg-danger';
                }
                ?>
                <span class="badge <?= $status_color ?> text-uppercase">
                  <?= htmlspecialchars($r->status) ?>
                </span>
              </td>
              <td class="small"><?= $activity ?></td>
              <td class="small"><?= $pending_display ?></td>
              <td class="small"><?= $path_display ?></td>
              <td>
                <div class="progress shadow-sm" style="height: 10px; min-width: 100px; background-color: #e9ecef;">
                  <div class="progress-bar <?= ($percent >= 100) ? 'bg-success' : 'bg-primary' ?>"
                    role="progressbar"
                    style="width: <?= $percent ?>%;"
                    aria-valuenow="<?= $percent ?>"
                    aria-valuemin="0"
                    aria-valuemax="100">
                  </div>
                </div>
                <small class="d-block text-center mt-1 fw-bold text-dark">
                  <?= $current ?>/<?= $target ?> (<?= $percent ?>%)
                </small>
              </td>
              <td class="small text-muted"><?= $lastUpdate ?> WIB</td>
              <td class="text-center">
                <a class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm"
                  href="<?= base_url('index.php/pegawai/dashboard/' . $r->pegawai_tugas_id) ?>">
                  <i class="fas fa-edit me-1"></i> Edit
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="row g-3 mb-4 align-items-center mt-4">
      <div class="col-md-6">
        <div class="btn-group shadow-sm">
          <a href="?mode=day" class="btn btn-sm <?= ($current_mode ?? 'day') == 'day' ? 'btn-primary' : 'btn-outline-primary' ?>">Harian</a>
          <a href="?mode=week" class="btn btn-sm <?= ($current_mode ?? 'day') == 'week' ? 'btn-primary' : 'btn-outline-primary' ?>">Mingguan</a>
          <a href="?mode=month" class="btn btn-sm <?= ($current_mode ?? 'day') == 'month' ? 'btn-primary' : 'btn-outline-primary' ?>">Bulanan</a>
          <a href="?mode=year" class="btn btn-sm <?= ($current_mode ?? 'day') == 'year' ? 'btn-primary' : 'btn-outline-primary' ?>">Tahunan</a>
        </div>
      </div>

      <div class="col-md-6 d-flex justify-content-md-end">
        <div class="d-inline-flex align-items-center">
          <label class="small fw-bold text-muted me-2 mb-0">Tampilkan Grafik:</label>
          <select id="chartFeatureSelect" class="form-control form-control-sm" style="width: 200px;">
            <option value="fbi" selected>Fee Base Income (FBI)</option>
            <option value="voa">Volume of Agent (VoA)</option>
            <option value="trans">Transaksi</option>
            <option value="agen">Agen</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm border-0 mb-4 overflow-hidden">
    <div class="card-header bg-white py-3">
      <h6 class="m-0 font-weight-bold text-primary">
        <i class="fas fa-chart-area me-2"></i> Grafik Performa KPI <span id="chartTitle" class="text-dark">FBI</span>
      </h6>
    </div>
    <div class="card-body">
      <div style="height: 350px;"><canvas id="mainChart"></canvas></div>
    </div>
  </div>

  <div class="card shadow-sm mb-4 border-0">
    <div class="card-header bg-light d-flex align-items-center justify-content-between">
      <div class="m-0 font-weight-bold ">
        <i class="fas fa-table me-2 text-primary"></i><span class="fw-bold">Riwayat Target & Realisasi KPI</span>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-sm align-middle mb-0 text-center table-riwayat">
        <thead class="thead-light">
          <tr>
            <th>Tanggal</th>
            <th>Kategori</th>
            <th>Target</th>
            <th>Realisasi</th>
            <th>Progress</th>
            <th>Gap Total</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($targets)): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">Belum ada data realisasi.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($targets as $t): ?>
              <?php
              $m = date('Y-m', strtotime($t->periode));
              $t_row = $this->db->get_where('kpi_targets', ['DATE_FORMAT(periode, "%Y-%m") =' => $m])->row();
              $kategori = [
                'Transaksi' => ['t' => ($t_row->target_transaksi ?? 0), 'r' => $t->real_transaksi],
                'FBI'       => ['t' => ($t_row->target_fbi ?? 0),       'r' => $t->real_fbi],
                'VoA'       => ['t' => ($t_row->target_voa ?? 0),       'r' => $t->real_voa],
                'Agen'      => ['t' => ($t_row->target_agen ?? 0),      'r' => ($t->real_agen ?? 0)]
              ];
              $first = true;
              foreach ($kategori as $label => $val):
                $target = (float)($val['t'] ?? 0);
                $real = (float)($val['r'] ?? 0);
                $prog = ($target > 0) ? round(($real / $target) * 100, 2) : 0;
                $gap = $real - $target;
              ?>
                <tr>
                  <?php if ($first): ?>
                    <td rowspan="4" class="font-weight-bold bg-white align-middle"><?= date('d M Y', strtotime($t->periode)) ?></td>
                  <?php endif; ?>
                  <td class="text-start pl-3 font-weight-bold bg-light"><?= $label ?></td>
                  <td class="text-numeric text-right pr-3"><?= number_format($target, 0, ',', '.') ?></td>
                  <td class="text-numeric text-right pr-3"><?= number_format($real, 0, ',', '.') ?></td>
                  <td><span class="badge rounded-pill bg-<?= ($prog >= 100) ? 'success' : ($prog >= 80 ? 'warning' : 'danger') ?>"><?= $prog ?>%</span></td>
                  <td class="text-numeric font-weight-bold <?= ($gap >= 0) ? 'text-success' : 'text-danger' ?>"><?= ($gap >= 0 ? '+' : '') . number_format($gap, 0, ',', '.') ?></td>
                  <?php if ($first): ?>
                    <td rowspan="4" class="text-wrap small text-muted align-middle"><?= htmlspecialchars($t->catatan ?? '-') ?></td>
                  <?php endif; ?>
                </tr>
              <?php $first = false;
              endforeach; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-between align-items-center">
    <div class="small text-muted">
      Menampilkan data riwayat KPI.
    </div>
    <nav aria-label="Page navigation">
      <?= $pagination_links ?? '' ?>
    </nav>
  </div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const labels = <?= $chart_labels ?? '[]' ?>;
    const allData = {
      fbi: <?= $c_fbi ?? '{"t":[],"r":[]}' ?>,
      voa: <?= $c_voa ?? '{"t":[],"r":[]}' ?>,
      trans: <?= $c_trans ?? '{"t":[],"r":[]}' ?>,
      agen: <?= $c_agen ?? '{"t":[],"r":[]}' ?>
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
      },
      agen: {
        lbl: 'Agen',
        col: '#bd04b4'
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
            borderColor: '#ef0a0a',
            backgroundColor: 'rgb(255, 253, 255)',
            fill: false,
            tension: 0.3
          },
          {
            label: 'Realisasi FBI',
            data: allData.fbi.r,
            backgroundColor: 'rgba(255, 193, 7, 0.1)',
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
  });
</script>