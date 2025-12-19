<h3>Employee Dashboard</h3>

<?php if($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $this->session->flashdata('success') ?>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">

    <table class="table table-bordered table-sm">
      <thead>
        <tr>
          <th>Task</th>
          <th>Date</th>
          <th>Status</th>
          <th>Activity</th>
          <th>Pending</th>
          <th>Close the Path</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($rows)): ?>
          <tr><td colspan="7" class="text-center text-muted">Not Data Yet</td></tr>
        <?php endif; ?>

        <?php foreach($rows as $r): ?>
          <tr>
            <td><?= $r->nama_tugas ?></td>
            <td><?= $r->tanggal_ambil ?></td>
            <td><?= $r->status ?></td>
            <td><?= $r->activity ? $r->activity : '-' ?></td>
            <td><?= $r->pending_matters ? $r->pending_matters : '-' ?></td>
            <td><?= $r->close_the_path ? $r->close_the_path : '-' ?></td>
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
