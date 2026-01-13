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
            $lastUpdate = '-';
            if (!empty($r->updated_at)) {
              $lastUpdate = date('d-m-Y H:i', strtotime($r->updated_at));
            } elseif (!empty($r->created_at)) {
              $lastUpdate = date('d-m-Y H:i', strtotime($r->created_at));
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
              <td><?= htmlspecialchars($r->pending_matters ?? '-') ?></td>
              <td><?= htmlspecialchars($r->close_the_path ?? '-') ?></td>
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

  </div>
</div>