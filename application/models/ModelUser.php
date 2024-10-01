<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelUser extends CI_Model
{
    public function simpanData($data = null)
    {
        $this->db->insert('user', $data);
    }

    public function cekData($where = null)
    {
        return $this->db->get_where('user', $where);
    }

    public function getUserWhere($where = null)
    {
        return $this->db->get_where('user', $where);
    }

    public function cekUserAccess($where = null)
    {
        $this->db->select('*');
        $this->db->from('access_menu');
        $this->db->where($where);
        return $this->db->get();
    }

    public function getUserLimit()
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->limit(10, 0);
        return $this->db->get();
    }

    public function joinKategoriBuku($where) 
    {
    // Mengambil data dari tabel 'buku'
    $this->db->from('buku');
    
    // Melakukan join dengan tabel 'kategori'
    $this->db->join('kategori', 'kategori.id = buku.id_kategori');
    
    // Menambahkan kondisi where
    $this->db->where($where);
    
    // Mengambil dan mengembalikan hasil query
    return $this->db->get();
    }


}
