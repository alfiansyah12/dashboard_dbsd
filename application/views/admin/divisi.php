<div class="container-fluid">
    <h3>Manage Division</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="post" action="<?= base_url('index.php/admin/divisi_store') ?>">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="nama_divisi" class="form-control" placeholder="Nama Divisi" required>
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
                <th>Name Division</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach($divisi as $d): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $d->nama_divisi ?></td>
                <td>
                    <a href="<?= base_url('index.php/admin/divisi_delete/'.$d->id) ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus divisi?')">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
