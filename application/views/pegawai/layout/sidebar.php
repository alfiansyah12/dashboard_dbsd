<div class="container-fluid">
  <div class="row">

    <div class="col-md-2 sidebar p-0">
      <div class="p-3 font-weight-bold">EMPLOYEE</div>

      <a class="<?= ($this->uri->segment(2) == 'dashboard' ? 'active' : '') ?>"
         href="<?= base_url('index.php/pegawai/dashboard') ?>">
        Dashboard
      </a>

      <a class="<?= ($this->uri->segment(2) == 'pilih_tugas' ? 'active' : '') ?>"
         href="<?= base_url('index.php/pegawai/pilih_tugas') ?>">
        Choose Your Task
      </a>

      <a href="<?= base_url('index.php/auth/logout') ?>"
         onclick="return confirm('Yakin ingin logout?')">
        Logout
      </a>
    </div>

    <div class="col-md-10 pt-4">
