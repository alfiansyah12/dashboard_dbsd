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
      <table class="table table-bordered table-sm mb-0">
        <thead class="thead-light">
          <tr>
            <th style="width:18%;">Task</th>
            <th style="width:10%;">Date</th>
            <th style="width:10%;">Status</th>
            <th>Activity</th>
            <th>Pending</th>
            <th>Clear the Path</th>
            <th>Progress</th>
            <th style="width:12%;">Last Update (WIB)</th>
            <th style="width:10%;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="9" class="text-center text-muted">No Data Yet</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($rows as $r): ?>
            <?php
            // PINDAHKAN LOGIKA PERHITUNGAN KE SINI
            $target  = (int)($r->target_nilai ?? 0);
            $current = (int)($r->progress_nilai ?? 0);
            $percent = ($target > 0) ? round(($current / $target) * 100, 0) : 0;
            if ($percent > 100) $percent = 100;

            $activity = !empty($r->activity) ? $r->activity : '-';
            $pending_display = !empty($r->pending_matters)
              ? htmlspecialchars($r->pending_matters)
              : '<i class="text-muted small">No pending matters</i>';

            $path_display = !empty($r->close_the_path)
              ? htmlspecialchars($r->close_the_path)
              : '<i class="text-muted small">No path to clear</i>';
            $lastUpdate = '-';
            $srcTz = new DateTimeZone('Asia/Jakarta'); // WIB

            if (!empty($r->updated_at)) {
              $dt = new DateTime($r->updated_at, $srcTz);
              $lastUpdate = $dt->format('d-m-Y H:i');
            } elseif (!empty($r->created_at)) {
              $dt = new DateTime($r->created_at, $srcTz);
              $lastUpdate = $dt->format('d-m-Y H:i');
            }
            ?>

            <tr>
              <td><?= htmlspecialchars($r->nama_tugas) ?></td>
              <td><?= date('d/m/Y', strtotime($r->tanggal_ambil)) ?></td>
              <td>
                <span class="badge badge-<?= $r->status == 'done' ? 'success' : 'info' ?>">
                  <?= htmlspecialchars($r->status) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($activity) ?></td>

              <td class="small"><?= $pending_display ?></td>
              <td class="small"><?= $path_display ?></td>

              <td>
                <div class="progress" style="height: 15px; min-width: 100px;">
                  <div class="progress-bar <?= ($percent >= 100) ? 'bg-success' : 'bg-info' ?>"
                    style="width: <?= $percent ?>%;">
                  </div>
                </div>
                <small class="d-block text-center mt-1"><?= $current ?>/<?= $target ?> (<?= $percent ?>%)</small>
              </td>
              <td><?= $lastUpdate ?> WIB</td>
              <td>
                <a class="btn btn-sm btn-primary" href="<?= base_url('index.php/pegawai/dashboard/' . $r->pegawai_tugas_id) ?>">
                  Input / Edit
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
      <div class="col-md-6 text-md-right">
        <div class="d-inline-flex align-items-center">
          <label class="small fw-bold text-muted mr-2 mb-0">Tampilkan Grafik:</label>
          <select id="chartFeatureSelect" class="form-control form-control-sm" style="width: 200px;">
            <option value="fbi" selected>Fee Base Income (FBI)</option>
            <option value="voa">Volume of Agent (VoA)</option>
            <option value="trans">Transaksi</option>
          </select>
        </div>
      </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-white font-weight-bold">
        <i class="fas fa-chart-line mr-2 text-primary"></i> Grafik Performa KPI <span id="chartTitle">FBI</span>
      </div>
      <div class="card-body">
        <div style="height: 300px; width: 100%;"><canvas id="mainChart"></canvas></div>
      </div>
    </div>

    <div class="card shadow-sm mb-4 border-0">
      <div class="card-header bg-light d-flex align-items-center justify-content-between">
        <div class="font-weight-bold">
          <i class="fas fa-table mr-2 text-primary"></i>Riwayat Target & Realisasi KPI
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0 text-center small">
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
                  'VoA'       => ['t' => ($t_row->target_voa ?? 0),       'r' => $t->real_voa]
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
                      <td rowspan="3" class="font-weight-bold bg-white align-middle"><?= date('d M Y', strtotime($t->periode)) ?></td>
                    <?php endif; ?>
                    <td class="text-left pl-3 font-weight-bold bg-light"><?= $label ?></td>
                    <td class="text-right pr-3"><?= number_format($target, 0, ',', '.') ?></td>
                    <td class="text-right pr-3"><?= number_format($real, 0, ',', '.') ?></td>
                    <td><span class="badge badge-<?= ($prog >= 100) ? 'success' : ($prog >= 80 ? 'warning' : 'danger') ?>"><?= $prog ?>%</span></td>
                    <td class="font-weight-bold <?= ($gap >= 0) ? 'text-success' : 'text-danger' ?>"><?= ($gap >= 0 ? '+' : '') . number_format($gap, 0, ',', '.') ?></td>
                    <?php if ($first): ?>
                      <td rowspan="3" class="text-wrap small text-muted align-middle"><?= htmlspecialchars($t->catatan ?? '-') ?></td>
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