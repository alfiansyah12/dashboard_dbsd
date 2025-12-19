<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Divisi_model extends CI_Model {

    private $table = 'divisi';

    public function getAll()
    {
        return $this->db->get($this->table)->result();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
