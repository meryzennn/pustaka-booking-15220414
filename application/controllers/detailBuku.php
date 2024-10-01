<?php
if ($buku->stok < 1) {
    echo '<i class="btn btn-outline-primary fas fw fa-shopping-cart"> Booking&nbsp;&nbsp;0</i>';
} else {
    echo '<a class="btn btn-outline-primary fas fw fa-shopping-cart" href="' . base_url('booking/tambahBooking/' . $buku->id) . '"> Booking</a>';
}
?>

<a class="btn btn-outline-warning fas fw fa-search" href="<?= base_url('home/detailBuku/' . $buku->id); ?>"> Detail</a>

public function detailBuku() 
    {
    // Ambil ID dari segment URL
    $id = $this->uri->segment(3);

    // Ambil detail buku berdasarkan ID
    $buku = $this->ModelBuku->joinKategoriBuku(['buku.id' => $id])->result();

    // Set data default
    $data['user'] = "Pengunjung"; 
    $data['title'] = "Detail Buku"; 

    // Looping untuk mengisi detail buku ke dalam data array
    foreach ($buku as $fields) { 
        $data['judul'] = $fields->judul_buku; 
        $data['pengarang'] = $fields->pengarang; 
        $data['penerbit'] = $fields->penerbit; 
        $data['kategori'] = $fields->kategori; 
        $data['tahun'] = $fields->tahun_terbit; 
        $data['isbn'] = $fields->isbn; 
        $data['gambar'] = $fields->image; 
        $data['dipinjam'] = $fields->dipinjam; 
        $data['dibooking'] = $fields->dibooking; 
        $data['stok'] = $fields->stok; 
        $data['id'] = $id; 
    }

    // Load views dengan data yang sudah disiapkan
    $this->load->view('templates/templates-user/header', $data); 
    $this->load->view('buku/detail-buku', $data); 
    $this->load->view('templates/templates-user/footer'); 
    }