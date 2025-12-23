<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $title ?? 'Pegawai' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- penting untuk mobile -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


  <style>
    body { background:#f7f7f7; }

    /* warna/hover link */
    .sidebar { min-height:100vh; background:#1f2937; color:#fff; }
    .sidebar a { color:#cbd5e1; display:block; padding:10px 14px; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#111827; color:#fff; border-radius:4px; }

    /* ===== MOBILE: sidebar jadi drawer (tidak ikut grid col) ===== */
    @media (max-width: 767.98px){
      /* override col-md-2 di mobile */
      #sidebarMenu{
        position: fixed;
        top: 56px;                 /* tinggi navbar mobile */
        left: 0;
        width: 260px;              /* lebar sidebar mobile */
        max-width: 85%;
        height: calc(100vh - 56px);
        z-index: 1050;
        overflow-y: auto;
        transform: translateX(-110%);
        transition: transform .2s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,.35);
      }
      #sidebarMenu.show{
        transform: translateX(0);
      }

      /* konten full width di mobile */
      main{
        width: 100% !important;
        margin-left: 0 !important;
      }

      /* ====== Chart Monitoring Size Control ====== */
.chart-card {
  height: 320px;           /* batas tinggi card */
}

.chart-card canvas {
  max-height: 240px !important; /* paksa canvas kecil */
}

    }
  </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark d-md-none">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <span class="navbar-brand mb-0 h6">Employee</span>
</nav>
