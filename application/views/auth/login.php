<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="BTN Employee Portal Login">
  <meta name="author" content="">
  <title>Login - Employee Portal</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      /* Background Gradient Biru BTN */
      background: linear-gradient(135deg, #004776 0%, #002845 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      max-width: 450px;
      width: 100%;
    }

    .login-header {
      background-color: #fff;
      padding: 30px 20px 10px;
      text-align: center;
    }

    .logo-img {
      /* Pastikan ukuran logo pas */
      max-width: 120px;
      height: auto;
      margin-bottom: 15px;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .login-title {
      color: #004776;
      font-weight: 700;
      font-size: 1.5rem;
    }

    .login-body {
      padding: 30px;
      background-color: #fff;
    }

    /* Styling Inputan dengan Ikon */
    .input-group-text {
      background-color: #f8f9fa;
      border-right: none;
      color: #004776;
    }

    .form-floating>.form-control {
      border-left: none;
      padding-left: 0.5rem;
    }

    .form-floating>.form-control:focus {
      box-shadow: none;
      border-color: #004776;
    }

    .form-floating>label {
      padding-left: 0.5rem;
      color: #6c757d;
    }

    /* Saat input fokus, ubah warna border dan ikon */
    .input-group:focus-within .input-group-text {
      border-color: #004776;
      background-color: #eef6fc;
    }

    /* Tombol Login */
    .btn-login {
      /* Gradien Emas/Kuning BTN */
      background: linear-gradient(to right, #ffdb58, #ffc107);
      border: none;
      color: #004776;
      font-weight: 700;
      padding: 12px;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 219, 88, 0.4);
      color: #002845;
    }

    /* Alert Styling */
    .alert {
      border-radius: 10px;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="card login-card animate__animated animate__fadeInUp">
          <div class="login-header">
            <img src="<?= base_url('assets/img/btn.png') ?>" alt="Logo BTN" class="logo-img">
            <h4 class="login-title">Employee Portal</h4>
            <p class="text-muted small mb-0">Silakan login untuk melanjutkan</p>
          </div>
          <div class="login-body">

            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            <form class="user" method="post" action="<?= base_url('index.php/auth/proses_login') ?>">

              <div class="mb-4">
                <div class="input-group input-group-lg">
                  <span class="input-group-text rounded-start-4 ps-3">
                    <i class="fas fa-user text-primary"></i>
                  </span>
                  <div class="form-floating flex-grow-1">
                    <input type="text" class="form-control rounded-end-4" id="username" name="username" placeholder="Username" required autofocus>
                    <label for="username">Username / NIP</label>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <div class="input-group input-group-lg">
                  <span class="input-group-text rounded-start-4 ps-3">
                    <i class="fas fa-lock text-primary"></i>
                  </span>
                  <div class="form-floating flex-grow-1">
                    <input type="password" class="form-control rounded-end-4" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-login btn-lg w-100 mt-3">
                MASUK <i class="fas fa-arrow-right ms-2"></i>
              </button>

            </form>
          </div>
          <div class="card-footer text-center bg-white py-3 border-0">
            <small class="text-muted">&copy; <?= date('Y'); ?> BTN Employee Portal</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>