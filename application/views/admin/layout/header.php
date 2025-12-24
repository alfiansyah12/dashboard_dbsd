<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Dashboard Admin'; ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css'); ?>">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="app-body">
<header class="app-header sticky-top">
  <nav class="navbar navbar-expand-lg navbar-dark app-navbar">
    <div class="container-fluid px-3">
      <a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('index.php/admin'); ?>">
        <i class="fa-solid fa-shield-halved"></i>
        <span><?= $title ?? 'Dashboard Admin'; ?></span>
      </a>

    <button class="navbar-toggler" type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#sidebarOffcanvas"
        aria-controls="sidebarOffcanvas">
  <span class="navbar-toggler-icon"></span>
</button>


      <div class="collapse navbar-collapse" id="topNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
          <li class="nav-item">
            <span class="nav-link small text-white-50">
              <i class="fa-regular fa-calendar me-1"></i> <?= date('d M Y'); ?>
            </span>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="fa-regular fa-user me-2"></i> Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item text-danger" href="<?= base_url('index.php/auth/logout'); ?>"
                   onclick="return confirm('Yakin ingin logout?')">
                  <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                </a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </div>
  </nav>
</header>
