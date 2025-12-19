<!doctype html>
<html>
<head>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
  <h3>Put Your Activity</h3>

<?php if($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
  </div>
<?php endif; ?>

<div class="alert alert-info">
  <b>Task:</b> <?= $row->nama_tugas ?> <br>
  <b>Date:</b> <?= $row->tanggal_ambil ?> <br>
  <b>Status:</b> <?= $row->status ?>
</div>

<div class="card">
  <div class="card-body">
    <form method="post" action="<?= base_url('index.php/pegawai/dashboard_store') ?>">
      <input type="hidden" name="pegawai_tugas_id" value="<?= $row->id ?>">

      <div class="form-group">
        <label>Activity</label>
        <textarea name="activity" class="form-control" required><?= $input->activity ?? '' ?></textarea>
      </div>

      <div class="form-group">
        <label>Pending matters</label>
        <textarea name="pending_matters" class="form-control"><?= $input->pending_matters ?? '' ?></textarea>
      </div>

      <div class="form-group">
        <label>Close the path</label>
        <textarea name="close_the_path" class="form-control"><?= $input->close_the_path ?? '' ?></textarea>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <?php
            $statuses = ['on going','done','terminated'];
            $cur = $row->status;
            foreach($statuses as $s){
              $sel = ($cur == $s) ? 'selected' : '';
              echo "<option value=\"$s\" $sel>$s</option>";
            }
          ?>
        </select>
      </div>

      <button class="btn btn-primary">Save</button>
      <a href="<?= base_url('index.php/pegawai/dashboard') ?>" class="btn btn-secondary">Back</a>
    </form>
  </div>
</div>



</div>
</body>
</html>
