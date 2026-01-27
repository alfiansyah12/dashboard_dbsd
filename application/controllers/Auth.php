<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('User_model');
    $this->load->library(['session']);
    $this->load->helper(['url', 'security']);
    // optional:
    // $this->load->database();
  }

  public function index()
  {
    // kalau sudah login, arahkan
    if ($this->session->userdata('logged_in')) {
      return $this->_redirect_by_role($this->session->userdata('role'));
    }
    $this->load->view('auth/login');
  }

  public function proses_login()
  {
    // Wajib POST
    if (strtoupper($this->input->method()) !== 'POST') {
      show_error('Method Not Allowed', 405);
      return;
    }

    // --- Rate limit sederhana (per IP) ---
    $ip = $this->input->ip_address();
    $key = 'login_attempts_' . md5($ip);
    $attempt = (int) $this->session->userdata($key);
    $lastKey = $key . '_last';
    $lastTime = (int) $this->session->userdata($lastKey);

    if ($lastTime && (time() - $lastTime) > 600) {
      $attempt = 0;
      $this->session->unset_userdata([$key, $lastKey]);
    }

    if ($attempt >= 10) {
      $this->session->set_flashdata('error', 'Terlalu banyak percobaan login. Coba lagi nanti.');
      redirect('auth');
      return;
    }

    // PERUBAHAN: Ambil 'username' bukan 'email' sesuai name di login.php
    $username = trim((string)$this->input->post('username', TRUE));
    $password = (string)$this->input->post('password', TRUE);

    if ($username === '' || $password === '') {
      $this->_bump_attempt($key, $lastKey);
      $this->session->set_flashdata('error', 'Username dan password wajib diisi.');
      redirect('auth');
      return;
    }

    // PERUBAHAN: Gunakan model untuk mencari berdasarkan username/NIP
    // Pastikan di User_model ada fungsi getByUsername atau sesuaikan namanya
    $user = $this->User_model->getByUsername($username);

    if ($user && !empty($user->password) && password_verify($password, $user->password)) {

      $this->session->sess_regenerate(TRUE);

      $this->session->set_userdata([
        'logged_in' => TRUE,
        'user_id'   => (int)$user->id,
        'nama'      => (string)$user->nama,
        'role'      => (string)$user->role,
        'departemen_id' => (int)$user->departemen_id,
      ]);

      $this->session->unset_userdata([$key, $lastKey]);

      return $this->_redirect_by_role($user->role);
    } else {
      $this->_bump_attempt($key, $lastKey);
      $this->session->set_flashdata('error', 'Username atau password salah.');
      redirect('auth');
      return;
    }
  }

  public function logout()
  {
    // optional audit
    // $this->_audit('LOGOUT', 'user_id='.$this->session->userdata('user_id'));

    $this->session->sess_destroy();
    redirect('auth');
  }

  private function _redirect_by_role($role)
  {
    $role = strtolower((string)$role);
    if ($role === 'admin')  return redirect('admin');
    if ($role === 'atasan') return redirect('atasan');
    return redirect('pegawai');
  }

  private function _bump_attempt($key, $lastKey)
  {
    $attempt = (int) $this->session->userdata($key);
    $attempt++;
    $this->session->set_userdata($key, $attempt);
    $this->session->set_userdata($lastKey, time());
  }

  // optional: simpan audit log (butuh table + db)
  /*
  private function _audit($action, $detail='') {
    $this->db->insert('audit_logs', [
      'user_id' => (int)$this->session->userdata('user_id'),
      'action'  => $action,
      'detail'  => $detail,
      'ip'      => $this->input->ip_address(),
      'ua'      => $this->input->user_agent(),
      'created_at' => date('Y-m-d H:i:s'),
    ]);
  }
  */
}
