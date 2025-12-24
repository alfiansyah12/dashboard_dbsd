<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login — BTN Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #003c8f, #0b4db3);
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto;
    }

    .login-card {
      width: 100%;
      max-width: 380px;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
      border: none;
      overflow: hidden;
    }

    .login-header {
      background: #ffffff;
      padding: 22px 20px 16px;
      text-align: center;
      border-bottom: 3px solid #f5b400;
    }

    .login-header h1 {
      font-size: 18px;
      font-weight: 800;
      margin: 0;
      color: #003c8f;
      letter-spacing: 0.4px;
    }

    .login-header small {
      color: #64748b;
      font-size: 12px;
    }

    .login-body {
      padding: 22px 22px 24px;
      background: #ffffff;
    }

    .form-label {
      font-weight: 600;
      font-size: 13px;
      color: #0f172a;
    }

    .form-control {
      border-radius: 10px;
      padding: 10px 12px;
    }

    .form-control:focus {
      border-color: #0b4db3;
      box-shadow: 0 0 0 0.15rem rgba(11, 77, 179, 0.18);
    }

    .btn-login {
      background: #0b4db3;
      border: none;
      font-weight: 800;
      border-radius: 12px;
      padding: 10px;
    }

    .btn-login:hover {
      background: #083e92;
    }

    .login-footer {
      margin-top: 14px;
      font-size: 11px;
      color: #64748b;
      text-align: center;
    }
  </style>
</head>

<body>

<div class="card login-card">

  <div class="login-header">
    <h1>BTN Dashboard</h1>
    <small>Admin & Internal Access</small>
  </div>

  <div class="login-body">

    <!-- ALERT ERROR (AMAN, GENERIK) -->
    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger py-2 small">
        <?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8'); ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('index.php/auth/login'); ?>" autocomplete="off">

      <!-- CSRF TOKEN (WAJIB kalau CSRF ON) -->
      <input type="hidden"
             name="<?= $this->security->get_csrf_token_name(); ?>"
             value="<?= $this->security->get_csrf_hash(); ?>">

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email"
               name="email"
               class="form-control"
               placeholder="email@btn.co.id"
               required
               autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password"
               name="password"
               class="form-control"
               placeholder="••••••••"
               required>
      </div>

      <button type="submit" class="btn btn-login w-100">
        Login
      </button>

    </form>

    <div class="login-footer">
      © <?= date('Y'); ?> Bank Tabungan Negara<br>
      Authorized Internal System
    </div>

  </div>

</div>

</body>
</html>
