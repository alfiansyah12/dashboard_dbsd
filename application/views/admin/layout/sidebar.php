<aside class="app-sidebar d-none d-lg-block">
  <div class="sidebar-brand">
    <div class="sidebar-title">ADMIN PANEL</div>
    <div class="sidebar-subtitle">BTN Dashboard</div>
  </div>

  <ul class="nav flex-column sidebar-nav">

    <li class="nav-item">
      <a href="<?= base_url('index.php/admin'); ?>"
        class="nav-link <?= ($this->uri->segment(2) == '' ? 'active' : '') ?>">
        <span class="left">
          <i class="fa-solid fa-gauge-high"></i> Dashboard
        </span>
      </a>
    </li>

    <li class="nav-item">
      <a href="<?= base_url('index.php/admin/user'); ?>"
        class="nav-link <?= ($this->uri->segment(2) == 'user' ? 'active' : '') ?>">
        <span class="left">
          <i class="fa-solid fa-users"></i> Manage User
        </span>

        <!-- badge dinamis: kalau controller kirim $total_user -->
        <?php if (isset($total_user)): ?>
          <span class="badge sidebar-badge"><?= (int)$total_user ?></span>
        <?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a href="<?= base_url('index.php/admin/departemen'); ?>"
        class="nav-link <?= ($this->uri->segment(2) == 'departemen' ? 'active' : '') ?>">
        <span class="left">
          <i class="fa-solid fa-sitemap"></i> Departement
        </span>

        <?php if (isset($total_departemen)): ?>
          <span class="badge sidebar-badge"><?= (int)$total_departemen ?></span>
        <?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a href="<?= base_url('index.php/admin/tugas'); ?>"
        class="nav-link <?= ($this->uri->segment(2) == 'tugas' ? 'active' : '') ?>">
        <span class="left">
          <i class="fa-solid fa-list-check"></i> Task
        </span>

        <?php if (isset($total_tugas)): ?>
          <span class="badge sidebar-badge"><?= (int)$total_tugas ?></span>
        <?php endif; ?>
      </a>
    </li>

    <li class="nav-item">
      <a href="<?= base_url('index.php/admin/target'); ?>"
        class="nav-link <?= ($this->uri->segment(2) == 'target' ? 'active' : '') ?>">
        <span class="left">
          <i class="fa-solid fa-chart-line"></i> Target & Realisasi
        </span>
      </a>
    </li>

    <div class="sidebar-divider"></div>

    <li class="nav-item">
      <a href="<?= base_url('index.php/auth/logout'); ?>"
        class="nav-link logout"
        onclick="return confirm('Yakin ingin logout?')">
        <span class="left-logout">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </span>
      </a>
    </li>

  </ul>
</aside>

<!-- MOBILE SIDEBAR (OFFCANVAS) -->
<div class="offcanvas offcanvas-start app-sidebar-offcanvas d-lg-none"
  tabindex="-1"
  id="sidebarOffcanvas"
  aria-labelledby="sidebarOffcanvasLabel">
  <div class="offcanvas-header">
    <div>
      <div class="sidebar-title" id="sidebarOffcanvasLabel">ADMIN PANEL</div>
      <div class="sidebar-subtitle">BTN Dashboard</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body p-0">
    <div class="app-sidebar p-3" style="width:100%; min-height:auto;">
      <ul class="nav flex-column sidebar-nav">
        <li class="nav-item">
          <a href="<?= base_url('index.php/admin'); ?>"
            class="nav-link <?= ($this->uri->segment(2) == '' ? 'active' : '') ?>">
            <span class="left">
              <i class="fa-solid fa-gauge-high"></i> Dashboard
            </span>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('index.php/admin/user'); ?>"
            class="nav-link <?= ($this->uri->segment(2) == 'user' ? 'active' : '') ?>">
            <span class="left">
              <i class="fa-solid fa-users"></i> Manage User
            </span>

            <!-- badge dinamis: kalau controller kirim $total_user -->
            <?php if (isset($total_user)): ?>
              <span class="badge sidebar-badge"><?= (int)$total_user ?></span>
            <?php endif; ?>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('index.php/admin/departemen'); ?>"
            class="nav-link <?= ($this->uri->segment(2) == 'departemen' ? 'active' : '') ?>">
            <span class="left">
              <i class="fa-solid fa-sitemap"></i> Departement
            </span>

            <?php if (isset($total_departemen)): ?>
              <span class="badge sidebar-badge"><?= (int)$total_departemen ?></span>
            <?php endif; ?>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('index.php/admin/tugas'); ?>"
            class="nav-link <?= ($this->uri->segment(2) == 'tugas' ? 'active' : '') ?>">
            <span class="left">
              <i class="fa-solid fa-list-check"></i> Task
            </span>

            <?php if (isset($total_tugas)): ?>
              <span class="badge sidebar-badge"><?= (int)$total_tugas ?></span>
            <?php endif; ?>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('index.php/admin/target'); ?>"
            class="nav-link <?= ($this->uri->segment(2) == 'target' ? 'active' : '') ?>">
            <span class="left">
              <i class="fa-solid fa-chart-line"></i> Target & Realisasi
            </span>
          </a>
        </li>

        <div class="sidebar-divider"></div>

        <li class="nav-item">
          <a href="<?= base_url('index.php/auth/logout'); ?>"
            class="nav-link logout"
            onclick="return confirm('Yakin ingin logout?')">
            <span class="left">
              <i class="fa-solid fa-right-from-bracket"></i> Logout
            </span>
          </a>

      </ul>
    </div>
  </div>
</div>