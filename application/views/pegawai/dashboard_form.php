<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<?php date_default_timezone_set('Asia/Jakarta'); ?>

<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Put Your Activity</h3>
    <a href="<?= base_url('index.php/pegawai/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Back</a>
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

  <div class="alert alert-info">
    <b>Task:</b> <?= htmlspecialchars($row->nama_tugas) ?> <br>
    <b>Date:</b> <?= htmlspecialchars($row->tanggal_ambil) ?> <br>
    <b>Status:</b> <?= htmlspecialchars($row->status) ?>
  </div>

  <?php
    // ✅ pastikan ID benar, jangan pakai pegawai_tugas_id yang kadang tidak ada
    $pt_id = isset($row->id) ? (int)$row->id : (isset($row->pegawai_tugas_id) ? (int)$row->pegawai_tugas_id : 0);

    // UX: jika status sudah terminated, kunci input (opsional)
    $isTerminated = (isset($row->status) && $row->status === 'terminated');
    $disabledAttr = $isTerminated ? 'disabled' : '';
  ?>

  <?php if ($isTerminated): ?>
    <div class="alert alert-warning">
      Tugas ini sudah <b>TERMINATED</b>. Input hanya bisa dilihat.
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form method="post" action="<?= base_url('index.php/pegawai/dashboard_store') ?>">

        <input type="hidden" name="pegawai_tugas_id" value="<?= $pt_id ?>">

        <!-- ✅ GOALS (baru) -->
        <div class="form-group">
          <label>Goals</label>
          <textarea name="goals" class="form-control" rows="3" placeholder="Write your goals..."
            <?= $disabledAttr ?>><?= $goals->goals ?? '' ?></textarea>
          <small class="text-muted">Isi goals untuk tugas ini (optional).</small>
        </div>

        <div class="form-group">
          <label>Activity</label>
          <textarea name="activity" class="form-control" rows="4" required
            <?= $disabledAttr ?>><?= $input->activity ?? '' ?></textarea>
        </div>

        <div class="form-group">
          <label>Pending matters</label>
          <textarea name="pending_matters" class="form-control" rows="3"
            <?= $disabledAttr ?>><?= $input->pending_matters ?? '' ?></textarea>
        </div>

        <div class="form-group">
          <label>Clear the path</label>
          <textarea name="close_the_path" class="form-control" rows="3"
            <?= $disabledAttr ?>><?= $input->close_the_path ?? '' ?></textarea>
        </div>

        <div class="form-group">
          <label>Status</label>
          <select name="status" class="form-control" required <?= $disabledAttr ?>>
            <?php
              $statuses = ['on going','done','terminated'];
              $cur = $row->status ?? 'on going';
              foreach($statuses as $s){
                $sel = ($cur == $s) ? 'selected' : '';
                echo "<option value=\"".htmlspecialchars($s)."\" $sel>".htmlspecialchars($s)."</option>";
              }
            ?>
          </select>
        </div>

        <?php if (!$isTerminated): ?>
          <button class="btn btn-primary">Save</button>
        <?php endif; ?>

        <a href="<?= base_url('index.php/pegawai/dashboard') ?>" class="btn btn-secondary">
          Back
        </a>

      </form>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
