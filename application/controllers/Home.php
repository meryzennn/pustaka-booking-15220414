<?php
class Home extends CI_Controller 
{ 
    function __construct() 
    { 
        parent::__construct(); 
    } 
    
    public function index() 
    { 
        $data = [ 
            'judul' => "Katalog Buku", 
            'buku'  => $this->ModelBuku->getBuku()->result(), 
        ];

        // Cek jika sudah login atau belum
        if ($this->session->userdata('email')) { 
            $user = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array(); 
            $data['user'] = $user['nama']; 
        } else { 
            $data['user'] = 'Pengunjung'; 
        }

        // Load tampilan
        $this->load->view('templates/templates-user/header', $data); 
        $this->load->view('buku/daftarbuku', $data); 
        $this->load->view('templates/templates-user/footer', $data); 
    }
}


