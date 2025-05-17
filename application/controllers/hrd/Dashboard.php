<?php

class Dashboard extends CI_Controller {

    public function __construct(){
        parent::__construct();

        // Ganti cek hak akses sesuai yang kamu pakai di database untuk HRD, misal '2'
        if($this->session->userdata('hak_akses') != '2'){
            $this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Tidak Punya Akses HRD!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>');
                redirect('auth/login'); // atau halaman block jika ada
        }
    }

    public function index() 
    {
        // Contoh data yang ingin ditampilkan untuk HRD
        $payroll = $this->db->query("SELECT * FROM data_kehadiran WHERE total_gaji > 0");

        $data['title'] = "Dashboard HRD";
        $data['total_payroll'] = $payroll->num_rows();

        // Bisa tambah data lain sesuai kebutuhan HRD

        $this->load->view('template_hrd/header', $data);
        $this->load->view('template_hrd/sidebar');
        $this->load->view('hrd/dashboard', $data); // pastikan view ini GMNAAAAAAAA
        $this->load->view('template_hrd/footer');
    }
}

?>
