<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slip_Gaji_Bulanan extends CI_Controller {

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
        $data['title'] = "Slip Gaji Pegawai Bulanan";
        $data['pegawai'] = $this->db->query("
            SELECT data_pegawai.nik, data_pegawai.nama_pegawai
            FROM data_pegawai
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            WHERE data_jabatan.jenis_gaji = 'Bulanan'
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/slip_gaji_bulanan', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_slip_gaji() {
        $data['title'] = "Cetak Slip Gaji Pegawai Bulanan";
        $nik = $this->input->post('nik');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $bulantahun = $bulan . $tahun;

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['slip_gaji'] = $this->db->query("
            SELECT data_pegawai.nik, data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan,
                data_jabatan.gaji_pokok, data_jabatan.tj_transport,
                data_jabatan.uang_makan, data_kehadiran.alpha
            FROM data_pegawai
            INNER JOIN (
                SELECT nik, bulan, MAX(alpha) as alpha
                FROM data_kehadiran
                WHERE bulan = '$bulantahun' AND nik = '$nik'
                GROUP BY nik, bulan
            ) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            WHERE data_jabatan.jenis_gaji = 'Bulanan' AND data_pegawai.nik = '$nik'
        ")->row();

        if (!$data['slip_gaji']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data slip gaji tidak ditemukan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('admin/slip_gaji_bulanan');
        }

        $this->load->view('admin/gaji/cetak_slip_gaji_bulanan', $data);
    }
}