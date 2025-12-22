<div class="container-fluid">
  <div class="row">

    <div class="col-md-2 sidebar p-0">
      <div class="p-3 font-weight-bold">EMPLOYEE</div>

      <?php
        $seg1 = $this->uri->segment(1);
        $seg2 = $this->uri->segment(2);

        $isDashboard = ($seg1 === 'pegawai' && $seg2 === 'dashboard');
        $isPilihTugas = ($seg1 === 'pegawai' && $seg2 === 'pilih_tugas');
      ?>

      <a class="<?= ($isDashboard ? 'active' : '') ?>"
         href="<?= base_url('index.php/pegawai/dashboard') ?>">
        Dashboard
      </a>

      <!-- ⛔ HANYA TAMPIL JIKA TIDAK ADA TUGAS AKTIF -->
      <?php if (empty($has_active_task)): ?>
        <a class="<?= ($isPilihTugas ? 'active' : '') ?>"
           href="<?= base_url('index.php/pegawai/pilih_tugas') ?>">
          Choose Your Task
        </a>
      <?php endif; ?>

      <a href="<?= base_url('index.php/auth/logout') ?>"
         onclick="return confirm('Yakin ingin logout?')">
        Logout
      </a>
    </div>

    <div class="col-md-10 pt-4">
