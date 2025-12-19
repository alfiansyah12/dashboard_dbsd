<div class="container-fluid">
<div class="row">

    <!-- SIDEBAR -->
    <nav class="col-md-2 bg-dark sidebar text-white p-3">
        <h5 class="text-center">ADMIN</h5>
        <hr>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= base_url('index.php/admin'); ?>" class="nav-link text-white">Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('index.php/admin/user'); ?>" class="nav-link text-white">Kelola User</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('index.php/admin/divisi'); ?>" class="nav-link text-white">Kelola Divisi</a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('index.php/admin/tugas'); ?>" class="nav-link text-white">Kelola Tugas</a>
            </li>
            <li class="nav-item mt-3">
                <a href="<?= base_url('index.php/auth/logout'); ?>" class="nav-link text-danger">Logout</a>
            </li>
        </ul>
    </nav>

    <!-- CONTENT -->
    <main class="col-md-10 p-4">
