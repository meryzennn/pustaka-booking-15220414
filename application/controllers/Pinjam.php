<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pinjam extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();
        cek_user();
    }

    public function index()
    {
        // Method intentionally left empty
    }

    public function daftarBooking()
    {
        $data['judul'] = "Daftar Booking";
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['pinjam'] = $this->db->query("SELECT * FROM booking")->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('booking/daftar-booking', $data);
        $this->load->view('templates/footer');
    }

    public function bookingDetail()
    {
        $id_booking = $this->uri->segment(3);
        $data['judul'] = "Booking Detail";
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        
        // Fetch booking and user information
        $data['agt_booking'] = $this->db->query("
            SELECT * FROM booking b, user u 
            WHERE b.id_user = u.id 
            AND b.id_booking = '$id_booking'
        ")->result_array();
        
        // Fetch booking details and book information
        $data['detail'] = $this->db->query("
            SELECT id_buku, judul_buku, pengarang, penerbit, tahun_terbit 
            FROM booking_detail d, buku b 
            WHERE d.id_buku = b.id 
            AND d.id_booking = '$id_booking'
        ")->result_array();

        // Load views
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('booking/booking-detail', $data);
        $this->load->view('templates/footer');
    }

    public function pinjamAct()
    {
        $id_booking = $this->uri->segment(3);
        $lama = $this->input->post('lama', TRUE);
        
        // Get booking information
        $bo = $this->db->query("SELECT * FROM booking WHERE id_booking = '$id_booking'")->row();
        $tglsekarang = date('Y-m-d');
        
        // Generate automatic loan code
        $no_pinjam = $this->ModelBooking->kodeOtomatis('pinjam', 'no_pinjam');
        
        // Prepare booking data for the loan
        $databooking = [
            'no_pinjam' => $no_pinjam,
            'id_booking' => $id_booking,
            'tgl_pinjam' => $tglsekarang,
            'id_user' => $bo->id_user,
            'tgl_kembali' => date('Y-m-d', strtotime('+' . $lama . ' days', strtotime($tglsekarang))),
            'tgl_pengembalian' => '0000-00-00',
            'status' => 'Pinjam',
            'total_denda' => 0
        ];

        // Save the loan data
        $this->ModelPinjam->simpanPinjam($databooking);
        $this->ModelPinjam->simpanDetail($id_booking, $no_pinjam);

        // Update the fine for the loan details
        $denda = $this->input->post('denda', TRUE);
        $this->db->query("UPDATE detail_pinjam SET denda = '$denda'");

        // Delete booking data after the books are borrowed
        $this->ModelPinjam->deleteData('booking', ['id_booking' => $id_booking]);
        $this->ModelPinjam->deleteData('booking_detail', ['id_booking' => $id_booking]);

        // Update the book status in the database
        $this->db->query("
            UPDATE buku, detail_pinjam 
            SET buku.dipinjam = buku.dipinjam + 1, buku.dibooking = buku.dibooking - 1 
            WHERE buku.id = detail_pinjam.id_buku
        ");

        // Set a flash message for success
        $this->session->set_flashdata('pesan', 
            '<div class="alert alert-message alert-success" role="alert">
                Data Peminjaman Berhasil Disimpan
            </div>'
        );

        // Redirect to the pinjam page
        redirect(base_url() . 'pinjam');
    }

    public function ubahStatus()
    {
        $id_buku = $this->uri->segment(3);
        $no_pinjam = $this->uri->segment(4);
        $tgl = date('Y-m-d');
        $status = 'Kembali';

        // Update status menjadi 'Kembali' pada saat buku dikembalikan
        $this->db->query("UPDATE pinjam, detail_pinjam 
                        SET pinjam.status='$status', pinjam.tgl_pengembalian='$tgl' 
                        WHERE detail_pinjam.id_buku='$id_buku' 
                        AND pinjam.no_pinjam='$no_pinjam'");

        // Update stok dan dipinjam pada tabel buku
        $this->db->query("UPDATE buku, detail_pinjam 
                        SET buku.dipinjam = buku.dipinjam - 1, 
                            buku.stok = buku.stok + 1 
                        WHERE buku.id = detail_pinjam.id_buku");

        // Set flash data pesan untuk notifikasi
        $this->session->set_flashdata('pesan', '<div class="alert alert-message alert-success" role="alert">Buku berhasil dikembalikan.</div>');
        
        redirect(base_url('pinjam'));
    }


    // 1. Membuat Fungsi Index pada Controller Pinjam BELOM:":":"




}
