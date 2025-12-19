<div class="container-fluid">
    <h3>Manage Task</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="post" action="<?= base_url('index.php/admin/tugas_store') ?>">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="nama_tugas" class="form-control" placeholder="Nama Tugas" required>
                    </div>
                    <div class="col-md-3">
                        <select name="divisi_id" class="form-control" required>
                            <option value="">Choose Division</option>
                            <?php foreach($divisi as $d): ?>
                                <option value="<?= $d->id ?>"><?= $d->nama_divisi ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi">
                    </div>
                    <div class="col-md-2">
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
                <th>Name Task</th>
                <th>Division</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($tugas as $t): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $t->nama_tugas ?></td>
                <td><?= $t->nama_divisi ?></td>
                <td><?= $t->deskripsi ?></td>
                <td>
                    <a href="<?= base_url('index.php/admin/tugas_delete/'.$t->id) ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus tugas?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
