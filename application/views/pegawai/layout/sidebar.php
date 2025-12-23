<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    <nav class="col-md-2 d-md-block sidebar collapse" id="sidebarMenu">
      <div class="pt-3 px-2">

        <div class="text-white text-center font-weight-bold mb-3">
          EMPLOYEE
        </div>

        <ul class="nav flex-column">

          <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'dashboard' ? 'active' : '') ?>"
               href="<?= base_url('index.php/pegawai/dashboard') ?>">
              Dashboard
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?= ($this->uri->segment(2) == 'pilih_tugas' ? 'active' : '') ?>"
               href="<?= base_url('index.php/pegawai/pilih_tugas') ?>">
              Choose Task
            </a>
          </li>

          <li class="nav-item mt-3">
            <a class="nav-link text-danger"
               href="<?= base_url('index.php/auth/logout') ?>"
               onclick="return confirm('Yakin ingin logout?')">
              Logout
            </a>
          </li>

        </ul>
      </div>
    </nav>

    <!-- CONTENT -->
    <main class="col-md-10 ml-sm-auto px-4 pt-4">
