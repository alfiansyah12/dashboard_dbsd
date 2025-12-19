<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';

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

    public function getByEmail($email)
    {
        return $this->db->get_where($this->table, ['email'=>$email])->row();
    }
    public function getById($id)
{
    return $this->db->get_where('users', ['id'=>$id])->row();
}
public function getAllWithDivisi()
{
    $this->db->select('users.*, divisi.nama_divisi');
    $this->db->from('users');
    $this->db->join('divisi', 'divisi.id = users.divisi_id', 'left');
    return $this->db->get()->result();
}

}
