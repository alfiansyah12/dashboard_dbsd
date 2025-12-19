<div class="container-fluid">
    <h3>Kelola User</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="post" action="<?= base_url('index.php/admin/user_store') ?>">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                    </div>
                    <div class="col-md-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="col-md-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-control" required>
                            <option value="">Role</option>
                            <option value="pegawai">Employee</option>
                            <option value="atasan">Superrior</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Division</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
<?php $no=1; foreach($users as $u): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $u->nama ?></td>
    <td><?= $u->email ?></td>
    <td><?= ucfirst($u->role) ?></td>

    <!-- ASSIGN DIVISI -->
    <td>
  <?php if ($u->role == 'pegawai'): ?>
    <form method="post" action="<?= base_url('index.php/admin/assign_divisi') ?>" class="d-flex gap-2">
      <input type="hidden" name="user_id" value="<?= $u->id ?>">

      <select name="divisi_id" class="form-control form-control-sm" style="max-width:200px;">
        <option value="">-- Choose Division --</option>
        <?php foreach($divisi as $d): ?>
          <option value="<?= $d->id ?>" <?= ($u->divisi_id == $d->id ? 'selected' : '') ?>>
            <?= $d->nama_divisi ?>
          </option>
        <?php endforeach ?>
      </select>

      <button type="submit" class="btn btn-sm btn-primary">Save</button>
    </form>
  <?php else: ?>
    -
  <?php endif ?>
</td>


    <!-- AKSI -->
    <td>
  <?php if ($u->role != 'admin'): ?>
    <a href="<?= base_url('index.php/admin/user_delete/'.$u->id) ?>"
       class="btn btn-danger btn-sm"
       onclick="return confirm('Hapus user?')">
       Delete
    </a>
  <?php else: ?>
    -
  <?php endif; ?>
</td>

</tr>
<?php endforeach ?>
</tbody>
    </table>
</div>
