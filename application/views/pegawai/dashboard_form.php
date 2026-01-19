<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="h4 text-gray-800 mb-0">Input Aktivitas Harian</h3>
    <a href="<?= base_url('index.php/pegawai/dashboard_list') ?>" class="btn btn-sm btn-outline-secondary shadow-sm">
      <i class="fas fa-arrow-left fa-sm"></i> Kembali
    </a>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('success') ?>
      <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light">
          <h6 class="m-0 font-weight-bold text-primary">Detail Penugasan</h6>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <small class="text-muted d-block">Nama Tugas:</small>
            <span class="font-weight-bold"><?= htmlspecialchars($row->nama_tugas) ?></span>
          </div>
          <div class="mb-3">
            <small class="text-muted d-block">Tanggal Ambil:</small>
            <span><?= date('d M Y', strtotime($row->tanggal_ambil)) ?></span>
          </div>
          <div class="mb-3">
            <small class="text-muted d-block">Status Saat Ini:</small>
            <span class="badge badge-<?= $row->status == 'done' ? 'success' : ($row->status == 'terminated' ? 'danger' : 'info') ?>">
              <?= strtoupper($row->status) ?>
            </span>
          </div>
          <hr>
          <?php
          $target = (int)($row->target_nilai ?? 0);
          $current = (int)($input->progress_nilai ?? 0);
          $percent = ($target > 0) ? round(($current / $target) * 100, 2) : 0;
          if ($percent > 100) $percent = 100;
          ?>
          <div class="mt-4">
            <label class="font-weight-bold small">Capaian Progress: <?= $percent ?>%</label>
            <div class="progress mb-2" style="height: 20px;">
              <div class="progress-bar progress-bar-striped progress-bar-animated <?= ($percent >= 100) ? 'bg-success' : 'bg-primary' ?>"
                role="progressbar" style="width: <?= $percent ?>%;">
              </div>
            </div>
            <small class="text-muted">Terdata: <?= $current ?> dari target <?= $target ?></small>
          </div>
          <?php if (!empty($row->deadline_tanggal)): ?>
            <div class="mt-3 p-2 bg-light border-left-danger small">
              <i class="fas fa-calendar-alt text-danger"></i> <b>Deadline:</b> <?= date('d M Y', strtotime($row->deadline_tanggal)) ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Form Laporan Aktivitas</h6>
        </div>
        <div class="card-body">
          <?php
          $pt_id = isset($row->id) ? (int)$row->id : (isset($row->pegawai_tugas_id) ? (int)$row->pegawai_tugas_id : 0);

          $isLocked = ($row->status === 'terminated' || $row->status === 'done');
          $disabledAttr = $isLocked ? 'disabled' : '';
          ?>

          <form method="post" action="<?= base_url('index.php/pegawai/dashboard_store') ?>">
            <input type="hidden" name="pegawai_tugas_id" value="<?= $pt_id ?>">

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Update Progress (Angka)</label>
                  <input type="number" name="progress_nilai" class="form-control"
                    value="<?= $current ?>" <?= $disabledAttr ?> placeholder="Contoh: 5">
                  <small class="text-muted">Masukkan jumlah pencapaian terbaru.</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Status Tugas</label>
                  <select name="status" class="form-control" required <?= $disabledAttr ?>>
                    <?php
                    $statuses = ['on going', 'done', 'terminated'];
                    $cur = $row->status ?? 'on going';
                    foreach ($statuses as $s) {
                      $sel = ($cur == $s) ? 'selected' : '';
                      echo "<option value=\"" . htmlspecialchars($s) . "\" $sel>" . ucwords($s) . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="font-weight-bold">Goals</label>
              <textarea name="goals" class="form-control" rows="2" placeholder="Apa tujuan pengerjaan tugas ini?"
                <?= $disabledAttr ?>><?= $goals->goals ?? '' ?></textarea>
            </div>

            <div class="form-group">
              <label class="font-weight-bold">Aktivitas Hari Ini</label>
              <textarea name="activity" class="form-control" rows="4" required placeholder="Detail pekerjaan..."
                <?= $disabledAttr ?>><?= $input->activity ?? '' ?></textarea>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Pending Matters</label>
                  <textarea name="pending_matters" class="form-control" rows="3"
                    placeholder="Contoh: Menunggu konfirmasi dari pihak IT terkait akses server..."
                    <?= $disabledAttr ?>><?= $input->pending_matters ?? '' ?></textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Clear the Path</label>
                  <textarea name="close_the_path" class="form-control" rows="3"
                    placeholder="Contoh: Melakukan koordinasi ulang dengan tim operasional untuk mempercepat proses..."
                    <?= $disabledAttr ?>><?= $input->close_the_path ?? '' ?></textarea>
                </div>
              </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
              <?php if (!$isLocked): ?>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                  <i class="fas fa-save fa-sm"></i> Simpan Laporan
                </button>
              <?php else: ?>
                <div class="alert alert-warning py-2 px-3 mb-0 small">
                  <i class="fas fa-lock me-1"></i> Laporan sudah dikunci karena status tugas <b><?= strtoupper($row->status) ?></b>.
                </div>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>