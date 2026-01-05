<h3>Choose Task Divison</h3>

<div class="card">
  <div class="card-body">
    <form method="post" action="<?= base_url('index.php/pegawai/ambil_tugas') ?>">
      <div class="form-group">
        <label>Task</label>
        <select name="tugas_id" class="form-control" required>
          <option value="">-- Choose Task --</option>
          <?php foreach ($tugas as $t): ?>
            <option value="<?= $t->id ?>">
              <?= $t->nama_tugas ?> (<?= $t->nama_departemen ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <button class="btn btn-primary">Take The Assignment</button>
      <a href="<?= base_url('index.php/pegawai/dashboard') ?>" class="btn btn-secondary">Back</a>
    </form>
  </div>
</div>