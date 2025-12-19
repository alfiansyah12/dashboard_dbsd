<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tugas_model extends CI_Model {

    private $table = 'tugas';

    public function getAll()
    {
        $this->db->select('tugas.*, divisi.nama_divisi');
        $this->db->from($this->table);
        $this->db->join('divisi', 'divisi.id = tugas.divisi_id');
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

    public function getByDivisi($divisi_id)
{
    $this->db->select('tugas.*, divisi.nama_divisi');
    $this->db->from('tugas');
    $this->db->join('divisi', 'divisi.id = tugas.divisi_id');
    $this->db->where('tugas.divisi_id', $divisi_id);
    return $this->db->get()->result();
}


}
