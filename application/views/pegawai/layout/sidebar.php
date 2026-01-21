      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
        <div class="position-sticky pt-3">
          <div class="sidebar-header">
            <img src="<?= base_url('assets/img/btn.png') ?>" alt="Logo" class="logo-img">
            <h5 class="mt-2 text-uppercase">Employee</h5>
            <hr class="mx-3 text-white-50">
          </div>

          <ul class="nav flex-column px-3">
            <li class="nav-item">
              <a class="nav-link <?= ($this->uri->segment(2) == 'dashboard') ? 'active' : '' ?>"
                href="<?= base_url('index.php/pegawai/dashboard') ?>">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($this->uri->segment(2) == 'pilih_tugas') ? 'active' : '' ?>"
                href="<?= base_url('index.php/pegawai/pilih_tugas') ?>">
                <i class="fas fa-tasks me-2"></i> Choose Task
              </a>
            </li>
            <li class="nav-item mt-4">
              <a class="nav-link text-danger logout-link"
                href="<?= base_url('index.php/auth/logout') ?>"
                onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
        </nav>