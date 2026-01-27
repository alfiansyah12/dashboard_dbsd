<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('error') ?>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
  </div>
<?php endif; ?>

<h3>Choose Task Divison</h3>

<div class="card">
  <div class="card-body">
    <form method="post" action="<?= base_url('index.php/pegawai/ambil_tugas') ?>">
      <div class="form-group">
        <label style="margin-bottom: 5px; font-size: 18px;">Task</label>
        <select name="tugas_id" class="form-control" required>
          <option value="">-- Choose Task --</option>
          <?php foreach ($tugas as $t): ?>
            <option value="<?= $t->id ?>">
              <?= $t->nama_tugas ?> (<?= $t->nama_departemen ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button class="btn btn-primary" style="margin-top: 10px;">Take The Assignment</button>
      <a href="<?= base_url('index.php/pegawai/dashboard') ?>" class="btn btn-secondary" style="margin-top: 10px;">Back</a>
    </form>
  </div>
</div>