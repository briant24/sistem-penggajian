<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Penggajian extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda Belum Login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('login');
        }
    }
    
    public function index() {
        $data['title'] = "Data Gaji Pegawai Bulanan";
        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $bulantahun = $bulan . $tahun;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $bulantahun = $bulan . $tahun;
        }
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['gaji'] = $this->db->query("
            SELECT DISTINCT data_pegawai.nik, data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan, 
                data_jabatan.gaji_pokok, data_jabatan.tj_transport, 
                data_jabatan.uang_makan, data_kehadiran.alpha
            FROM data_pegawai
            INNER JOIN (
                SELECT nik, bulan, MAX(alpha) as alpha
                FROM data_kehadiran
                WHERE bulan = '$bulantahun'
                GROUP BY nik, bulan
            ) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            WHERE data_jabatan.jenis_gaji != 'Borongan'
            GROUP BY data_pegawai.nik
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/data_gaji', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_gaji() {
        $data['title'] = "Cetak Data Gaji Pegawai Bulanan";
        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $bulantahun = $bulan . $tahun;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $bulantahun = $bulan . $tahun;
        }
        
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['cetak_gaji'] = $this->db->query("
            SELECT DISTINCT data_pegawai.nik, data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan, 
                data_jabatan.gaji_pokok, data_jabatan.tj_transport, 
                data_jabatan.uang_makan, data_kehadiran.alpha
            FROM data_pegawai
            INNER JOIN (
                SELECT nik, bulan, MAX(alpha) as alpha
                FROM data_kehadiran
                WHERE bulan = '$bulantahun'
                GROUP BY nik, bulan
            ) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            WHERE data_jabatan.jenis_gaji != 'Borongan'
            GROUP BY data_pegawai.nik
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();
        
        $this->load->view('admin/gaji/cetak_gaji', $data);
    }
}
?>