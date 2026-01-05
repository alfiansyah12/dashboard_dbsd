<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tugas_model extends CI_Model
{

    private $table = 'tugas';

    public function getAll()
    {
        $this->db->select('tugas.*, departemen.nama_departemen');
        $this->db->from($this->table);
        $this->db->join('departemen', 'departemen.id = tugas.departemen_id');
        return $this->db->get()->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function getByDepartemen($departemen_id)
    {
        $this->db->select('tugas.*, departemen.nama_departemen');
        $this->db->from('tugas');
        $this->db->join('departemen', 'departemen.id = tugas.departemen_id');
        $this->db->where('tugas.departemen_id', $departemen_id);
        return $this->db->get()->result();
    }
}
