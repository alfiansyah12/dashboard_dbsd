<?php
$this->load->view('admin/layout/header', ['title' => 'Dashboard Admin']);
?>

<h3>Dashboard Admin</h3>
<p>Welcome, <b><?= $this->session->userdata('nama'); ?></b></p>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5>Total User</h5>
                <h3><?= $total_user ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5>Total Division</h5>
                <h3><?= $total_divisi ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5>Total Task</h5>
                <h3><?= $total_user ?></h3>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('admin/layout/footer');
?>
