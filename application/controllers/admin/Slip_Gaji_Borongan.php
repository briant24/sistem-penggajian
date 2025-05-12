<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slip_Gaji_Borongan extends CI_Controller {

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
        $data['title'] = "Slip Gaji Pegawai Borongan";
        $data['pegawai'] = $this->db->query("
            SELECT data_pegawai.nik, data_pegawai.nama_pegawai
            FROM data_pegawai
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            WHERE data_jabatan.jenis_gaji = 'Borongan'
            ORDER BY data_pegawai.nama_pegawai ASC
        ")->result();
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/slip_gaji_borongan', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_slip_gaji() {
        $data['title'] = "Cetak Slip Gaji Pegawai Borongan";
        $nik = $this->input->post('nik');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $minggu = $this->input->post('minggu');
        $bulantahun = $bulan . $tahun;

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['minggu'] = $minggu;
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['slip_gaji'] = $this->db->query("
            SELECT data_pegawai.nik, data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan,
                data_jabatan.tarif_borongan, target_mingguan.target_mingguan,
                data_kehadiran.alpha
            FROM data_pegawai
            INNER JOIN (
                SELECT nik, bulan, MAX(alpha) as alpha
                FROM data_kehadiran
                WHERE bulan = '$bulantahun' AND nik = '$nik'
                GROUP BY nik, bulan
            ) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
            INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
            LEFT JOIN (
                SELECT nik_pegawai, bulan_target, tahun_target, mingguke, MAX(target_mingguan) as target_mingguan
                FROM target_mingguan
                WHERE bulan_target = '$bulan' AND tahun_target = '$tahun' AND mingguke = '$minggu' AND nik_pegawai = '$nik'
                GROUP BY nik_pegawai, bulan_target, tahun_target, mingguke
            ) target_mingguan ON target_mingguan.nik_pegawai = data_pegawai.nik
            WHERE data_jabatan.jenis_gaji = 'Borongan' AND data_pegawai.nik = '$nik'
        ")->row();

        if (!$data['slip_gaji']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Data slip gaji tidak ditemukan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                </div>');
            redirect('admin/slip_gaji_borongan');
        }

        $this->load->view('admin/gaji/cetak_slip_gaji_borongan', $data);
    }
}