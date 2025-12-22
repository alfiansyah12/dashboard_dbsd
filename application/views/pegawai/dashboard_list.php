<?php date_default_timezone_set('Asia/Jakarta'); ?>

<div id="top"></div>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Employee Dashboard</h3>
</div>

<?php if($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
  </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
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
            <th style="width:12%;">Last Update (WIB)</th>
            <th style="width:10%;">Action</th>
          </tr>
        </thead>
        <tbody>

          <?php if(empty($rows)): ?>
            <tr>
              <td colspan="8" class="text-center text-muted">No Data Yet</td>
            </tr>
          <?php endif; ?>

          <?php foreach($rows as $r): ?>
            <?php
              // safe text
              $activity = !empty($r->activity) ? $r->activity : '-';
              $pending  = !empty($r->pending_matters) ? $r->pending_matters : '-';
              $ctp      = !empty($r->close_the_path) ? $r->close_the_path : '-';

              // last update: butuh field dari controller (di.updated_at/di.created_at)
              // kalau controller belum kirim, akan tampil '-'
              $lastUpdate = '-';
              if (!empty($r->updated_at)) {
                $lastUpdate = date('d-m-Y H:i', strtotime($r->updated_at));
              } elseif (!empty($r->created_at)) {
                $lastUpdate = date('d-m-Y H:i', strtotime($r->created_at));
              }
            ?>

            <tr>
              <td><?= htmlspecialchars($r->nama_tugas) ?></td>
              <td><?= htmlspecialchars($r->tanggal_ambil) ?></td>
              <td><?= htmlspecialchars($r->status) ?></td>
              <td><?= htmlspecialchars($activity) ?></td>
              <td><?= htmlspecialchars($pending) ?></td>
              <td><?= htmlspecialchars($ctp) ?></td>
              <td><?= $lastUpdate ?></td>
              <td>
                <a class="btn btn-sm btn-primary"
                   href="<?= base_url('index.php/pegawai/dashboard/'.$r->pegawai_tugas_id) ?>">
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
