<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai_tugas_model extends CI_Model
{

    public function getTugasByUser($user_id)
    {
        $this->db->select('pegawai_tugas.*, tugas.nama_tugas');
        $this->db->from('pegawai_tugas');
        $this->db->join('tugas', 'tugas.id = pegawai_tugas.tugas_id');
        $this->db->where('pegawai_tugas.user_id', $user_id);
        return $this->db->get()->result();
    }

    public function insert($data)
    {
        return $this->db->insert('pegawai_tugas', $data);
    }

    public function getById($id)
    {
        $this->db->select('pegawai_tugas.*, tugas.nama_tugas, tugas.deskripsi, tugas.departemen_id');
        $this->db->from('pegawai_tugas');
        $this->db->join('tugas', 'tugas.id = pegawai_tugas.tugas_id');
        $this->db->where('pegawai_tugas.id', $id);
        return $this->db->get()->row();
    }

    public function getActiveByUser($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'on going');
        return $this->db->get('pegawai_tugas')->row();
    }

    public function updateStatus($pegawai_tugas_id, $status)
    {
        return $this->db->where('id', $pegawai_tugas_id)
            ->update('pegawai_tugas', ['status' => $status]);
    }
}
