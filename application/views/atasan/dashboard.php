<!doctype html>
<html lang="id">

<style>
    .table td,
    .table th {
        font-size: 16px !important;
    }

    .badge {
        font-size: 14px !important;
        padding: 0.6rem 1rem;
        font-size: 1.5rem;
    }

    /* Memperbesar font untuk kolom Employee */
    .table td b {
        font-size: 20px;
        /* Atau sesuaikan ukuran font yang diinginkan */
    }

    /* Memperbesar font untuk kolom Task */
    .table td .small.fw-bold {
        font-size: 18px;
        /* Atau sesuaikan ukuran font yang diinginkan */
    }

    /* Memperbesar font dan padding pada badge */
    .text-end .badge {
        font-size: 18px;
        /* Ukuran font badge yang lebih besar */
        padding: 0.8rem 1.5rem;
        /* Padding lebih besar untuk memperbesar badge */
    }

    /* Memperbesar radius badge */
    .text-end .badge.rounded-pill {
        border-radius: 50px;
        /* Pembulatan yang lebih besar pada badge */
    }
</style>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        .progress {
            height: 10px;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>

<body class="bg-light p-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Executive Monitoring BTN</h3>
            <a href="<?= base_url('index.php/auth/logout') ?>" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <?php
        // Ambil data target bulanan terbaru (Januari 2026)
        $m = date('Y-m');
        $t_row = $this->db->get_where('kpi_targets', ['DATE_FORMAT(periode, "%Y-%m") =' => $m])->row();

        // Hitung Kumulatif Realisasi dari data yang ada
        $total_fbi_real   = 0;
        $total_trans_real = 0;
        $total_voa_real   = 0;
        $total_agen_real   = 0;


        foreach ($targets as $t) {
            $total_fbi_real   += (float)$t->real_fbi;
            $total_trans_real += (float)$t->real_transaksi;
            $total_voa_real   += (float)$t->real_voa;
            $total_agen_real   += (float)$t->real_agen;
        }

        // Data Target
        $target_fbi   = (float)($t_row->target_fbi ?? 0);
        $target_trans = (float)($t_row->target_transaksi ?? 0);
        $target_voa   = (float)($t_row->target_voa ?? 0);
        $target_agen  = (float)($t_row->target_agen ?? 0);

        // Hitung Persentase
        $prog_fbi   = ($target_fbi > 0) ? round(($total_fbi_real / $target_fbi) * 100, 1) : 0;
        $prog_trans = ($target_trans > 0) ? round(($total_trans_real / $target_trans) * 100, 1) : 0;
        $prog_voa   = ($target_voa > 0) ? round(($total_voa_real / $target_voa) * 100, 1) : 0;
        $prog_agen  = ($target_agen > 0) ? round(($total_agen_real / $target_agen) * 100, 1) : 0;

        if (!function_exists('rupiah')) {
            function rupiah($angka)
            {
                return number_format($angka, 0, ',', '.');
            }
        }
        ?>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-start border-info border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small fw-bold text-uppercase">Pertumbuhan Agen</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-secondary small">Target: <?= rupiah($target_agen) ?></div>
                                <div class="fs-4 fw-bold text-dark"><?= rupiah($total_agen_real) ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill bg-info"><?= $prog_agen ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-start border-warning border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small fw-bold text-uppercase">Fee Base Income (FBI)</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-secondary small">Target: <?= rupiah($target_fbi) ?></div>
                                <div class="fs-4 fw-bold text-dark"><?= rupiah($total_fbi_real) ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill bg-<?= $prog_fbi >= 100 ? 'success' : 'warning text-dark' ?>"><?= $prog_fbi ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-start border-primary border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small fw-bold text-uppercase">Total Transaksi</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-secondary small">Target: <?= rupiah($target_trans) ?></div>
                                <div class="fs-4 fw-bold text-dark"><?= rupiah($total_trans_real) ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill bg-<?= $prog_trans >= 100 ? 'success' : 'primary' ?>"><?= $prog_trans ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-start border-success border-4 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted small fw-bold text-uppercase">Volume of Agent (VoA)</h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-secondary small">Target: <?= rupiah($target_voa) ?></div>
                                <div class="fs-4 fw-bold text-dark"><?= rupiah($total_voa_real) ?></div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill bg-<?= $prog_voa >= 100 ? 'success' : 'info' ?>"><?= $prog_voa ?>%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-white">Penilaian Atasan</div>
                    <div class="card-body" style="height:200px;"><canvas id="pieReview"></canvas></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-white">Status Tugas Pegawai</div>
                    <div class="card-body" style="height:200px;"><canvas id="barStatus"></canvas></div>
                </div>
            </div>
        </div>

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
                        <option value="agen">Agen</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="bi bi-graph-up me-2 text-primary"></i> Grafik Performa <span id="chartTitle">FBI</span>
            </div>
            <div class="card-body">
                <div style="height: 350px; width: 100%;"><canvas id="mainChart"></canvas></div>
            </div>
        </div>

        <div class="card mb-4 monitoring">
            <div class="card-header bg-primary text-white fw-bold">Monitoring Aktivitas & Progres Pegawai</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>Task</th>
                            <th>Progress Bar</th>
                            <th>Latest Activity</th>
                            <th>Assessment</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r):
                            $target = (int)($r->target_nilai ?? 0);
                            $current = (int)($r->progress_nilai ?? 0);
                            $percent = ($target > 0) ? round(($current / $target) * 100, 0) : 0;
                        ?>
                            <tr>
                                <form method="post" action="<?= base_url('index.php/atasan/review_store') ?>">
                                    <input type="hidden" name="pegawai_tugas_id" value="<?= $r->pegawai_tugas_id ?>">

                                    <td><b><?= $r->pegawai_nama ?></b><br><small class="text-muted" style="font-size: 18px;"><?= $r->nama_departemen ?></small></td>
                                    <td>
                                        <div class="small fw-bold"><?= $r->nama_tugas ?></div>
                                        <span class="badge bg-<?= $r->status == 'done' ? 'success' : 'primary' ?>"><?= $r->status ?></span>
                                    </td>
                                    <td>
                                        <div class="progress mb-1">
                                            <div class="progress-bar <?= $percent >= 100 ? 'bg-success' : '' ?>" style="width:<?= $percent ?>%"></div>
                                        </div>
                                        <small><?= $current ?>/<?= $target ?> (<?= $percent ?>%)</small>
                                    </td>

                                    <td>
                                        <div class="mb-1">
                                            <label class="x-small fw-bold text-muted">Pending Matters:</label>
                                            <textarea name="pending_matters" class="form-control form-control-sm" rows="2" placeholder="Input hambatan..."><?= htmlspecialchars($r->pending_matters ?? '') ?></textarea>
                                        </div>
                                        <div>
                                            <label class="x-small fw-bold text-muted">Clear the Path (Action):</label>
                                            <textarea name="close_the_path" class="form-control form-control-sm" rows="2" placeholder="Input solusi/tindakan..."><?= htmlspecialchars($r->close_the_path ?? '') ?></textarea>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="mb-2">
                                            <label class="x-small fw-bold text-muted">Assessment:</label>
                                            <select name="review_status" class="form-select form-select-sm">
                                                <option value="not_yet" <?= $r->review_status == 'not_yet' ? 'selected' : '' ?>>Not Yet</option>
                                                <option value="done" <?= $r->review_status == 'done' ? 'selected' : '' ?>>Done</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-sm btn-primary w-100"><i class="bi bi-save me-1"></i> Save Update</button>
                                    </td>

                                    <td>
                                        <div class="small text-muted mb-1">Update terakhir:</div>
                                        <div class="fw-bold small"><?= date('d/m/y H:i', strtotime($r->last_update)) ?> WIB</div>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                <div class="fw-bold">
                    <i class="bi bi-table me-2"></i>Riwayat Target & Realisasi KPI
                </div>
                <span class="badge bg-primary">Total Data: <?= isset($targets) ? count($targets) : 0 ?></span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 text-center small">
                    <thead class="table-light">
                        <tr class="text-nowrap">
                            <th style="width: 150px;">Tanggal</th>
                            <th style="width: 130px;">Kategori</th>
                            <th>Target</th>
                            <th>Realisasi</th>
                            <th>Progress</th>
                            <th>Gap Total</th>
                            <th>Tgl Target Final</th>
                            <th>Note</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($targets)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data realisasi.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($targets as $t): ?>
                                <?php
                                $m = date('Y-m', strtotime($t->periode));
                                // Mencari data target bulanan yang sesuai berdasarkan bulan
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
                                    $real   = (float)($val['r'] ?? 0);
                                    $prog   = ($target > 0) ? round(($real / $target) * 100, 2) : 0;
                                    $gap    = $real - $target;

                                    // Styling badge progress
                                    $progClass = ($prog >= 100) ? 'bg-success' : ($prog >= 80 ? 'bg-warning' : 'bg-danger');
                                ?>
                                    <tr class="text-nowrap">
                                        <?php if ($first): ?>
                                            <td rowspan="4" class="fw-bold bg-white align-middle">
                                                <?= date('d M Y', strtotime($t->periode)) ?>
                                                <div class="small fw-normal text-muted mt-1">
                                                    <i class="bi bi-clock me-1"></i>
                                                    <?= isset($t->created_at) ? date('H:i', strtotime($t->created_at)) : '-' ?> WIB
                                                </div>
                                            </td>
                                        <?php endif; ?>

                                        <td class="text-start ps-3 fw-semibold bg-light"><?= $label ?></td>
                                        <td class="text-end pe-3"><?= number_format($target, 0, ',', '.') ?></td>
                                        <td class="text-end pe-3"><?= number_format($real, 0, ',', '.') ?></td>
                                        <td><span class="badge <?= $progClass ?>"><?= $prog ?>%</span></td>
                                        <td class="fw-bold <?= ($gap >= 0) ? 'text-success' : 'text-danger' ?>">
                                            <?= ($gap >= 0 ? '+' : '') . number_format($gap, 0, ',', '.') ?>
                                        </td>

                                        <?php if ($first): ?>
                                            <td rowspan="4" class="align-middle">
                                                <?= (!empty($t_row) && isset($t_row->tgl_target_final)) ? date('d-m-Y', strtotime($t_row->tgl_target_final)) : '-' ?>
                                            </td>
                                            <td rowspan="4" class="text-wrap small text-muted align-middle" style="max-width: 200px;">
                                                <?= htmlspecialchars($t->catatan ?? '-') ?>
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

            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Menampilkan data riwayat KPI terbaru.
                </div>
                <nav>
                    <?= $pagination_links ?? '' ?>
                </nav>
            </div>
        </div>
    </div>

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
                    col: '#6f42c1'
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
                            borderColor: '#ff0000',
                            backgroundColor: 'rgb(235, 220, 228)',
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

            // Listener Dropdown Fitur (FBI, VOA, Trans)
            document.getElementById('chartFeatureSelect').addEventListener('change', function() {
                const f = this.value;
                document.getElementById('chartTitle').innerText = featureInfo[f].lbl;
                myChart.data.datasets[0].data = allData[f].t;
                myChart.data.datasets[1].data = allData[f].r;
                myChart.data.datasets[1].label = 'Realisasi ' + featureInfo[f].lbl;
                myChart.data.datasets[1].borderColor = featureInfo[f].col;
                myChart.data.datasets[1].backgroundColor = hexToRgba(featureInfo[f].col, 0.2);
                myChart.update();
            });

            function hexToRgba(hex, alpha) {
                const r = parseInt(hex.slice(1, 3), 16),
                    g = parseInt(hex.slice(3, 5), 16),
                    b = parseInt(hex.slice(5, 7), 16);
                return `rgba(${r}, ${g}, ${b}, ${alpha})`;
            }

            // Grafik Ringkasan Status Penilaian Atasan
            new Chart(document.getElementById('pieReview'), {
                type: 'doughnut',
                data: {
                    labels: ['Done', 'Not Yet'],
                    datasets: [{
                        data: [
                            // Menggunakan fungsi anonim standar untuk kompatibilitas
                            <?= count(array_filter($rows, function ($i) {
                                return $i->review_status == 'done';
                            })) ?>,
                            <?= count(array_filter($rows, function ($i) {
                                return $i->review_status != 'done';
                            })) ?>
                        ],
                        backgroundColor: ['#198754', '#ffc107']
                    }]
                },
                options: {
                    maintainAspectRatio: false
                }
            });

            // Grafik Status Tugas Pegawai
            new Chart(document.getElementById('barStatus'), {
                type: 'bar',
                data: {
                    labels: ['On Going', 'Done', 'Terminated'],
                    datasets: [{
                        label: 'Total',
                        data: [
                            <?= count(array_filter($rows, function ($i) {
                                return $i->status == 'on going';
                            })) ?>,
                            <?= count(array_filter($rows, function ($i) {
                                return $i->status == 'done';
                            })) ?>,
                            <?= count(array_filter($rows, function ($i) {
                                return $i->status == 'terminated';
                            })) ?>
                        ],
                        backgroundColor: '#0d6efd'
                    }]
                },
                options: {
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>