<?php $this->load->view('admin/layout/header', ['title' => $title ?? 'Admin']); ?>

<div class="app-wrap">
  <?php
  // kirim badge angka ke sidebar kalau ada (biar dinamis)
  $this->load->view('admin/layout/sidebar', [
    'total_user'   => $total_user   ?? null,
    'total_departemen' => $total_departemen ?? null,
    'total_tugas'  => $total_tugas  ?? null,
  ]);
  ?>

  <main class="app-content">
    <div class="container-fluid px-3 app-container">
      <?php $this->load->view($content); ?>
    </div>
  </main>
</div>

<?php $this->load->view('admin/layout/footer'); ?>