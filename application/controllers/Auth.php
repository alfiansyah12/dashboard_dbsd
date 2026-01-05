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

  public function login()
  {
    // wajib POST
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

    // reset counter kalau sudah lewat 10 menit
    if ($lastTime && (time() - $lastTime) > 600) {
      $attempt = 0;
      $this->session->unset_userdata([$key, $lastKey]);
    }

    // block jika > 10 kali
    if ($attempt >= 10) {
      $this->session->set_flashdata('error', 'Terlalu banyak percobaan login. Coba lagi beberapa menit.');
      redirect('auth');
      return;
    }

    $email    = trim((string)$this->input->post('email', TRUE));
    $password = (string)$this->input->post('password', TRUE);

    if ($email === '' || $password === '') {
      $this->_bump_attempt($key, $lastKey);
      $this->session->set_flashdata('error', 'Email atau password salah.');
      redirect('auth');
      return;
    }

    // optional: normalize email
    $email = strtolower($email);

    $user = $this->User_model->getByEmail($email);

    // cek password hash
    if ($user && !empty($user->password) && password_verify($password, $user->password)) {

      // (opsional) upgrade hash kalau algoritma berubah
      if (password_needs_rehash($user->password, PASSWORD_BCRYPT)) {
        $newHash = password_hash($password, PASSWORD_BCRYPT);
        // buat function updatePasswordHash di model kalau belum ada
        // $this->User_model->updatePasswordHash($user->id, $newHash);
      }

      // regenerasi session (anti session fixation)
      $this->session->sess_regenerate(TRUE);

      $this->session->set_userdata([
        'logged_in' => TRUE,
        'user_id'   => (int)$user->id,
        'nama'      => (string)$user->nama,
        'role'      => (string)$user->role,
        'departemen_id' => (int)$user->departemen_id,
      ]);

      // reset rate limit jika sukses
      $this->session->unset_userdata([$key, $lastKey]);

      // optional audit
      // $this->_audit('LOGIN_SUCCESS', 'email='.$email);

      return $this->_redirect_by_role($user->role);
    } else {
      $this->_bump_attempt($key, $lastKey);
      // optional audit
      // $this->_audit('LOGIN_FAIL', 'email='.$email);
      $this->session->set_flashdata('error', 'Email atau password salah.');
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
