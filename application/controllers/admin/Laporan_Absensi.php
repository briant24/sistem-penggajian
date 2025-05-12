<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Absensi extends CI_Controller {

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
        $data['title'] = "Laporan Absensi Pegawai";
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/absensi/laporan_absensi', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_laporan_absensi() {
        $data['title'] = "Cetak Laporan Absensi Pegawai";
        // Ambil bulan dan tahun dari GET, default ke bulan/tahun saat ini
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $bulantahun = $bulan . $tahun;

        // Simpan bulan dan tahun untuk view
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        // Query untuk mengambil data absensi
        $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan, data_kehadiran.bulan, data_kehadiran.hadir, data_kehadiran.sakit, data_kehadiran.alpha');
        $this->db->from('data_kehadiran');
        $this->db->join('data_pegawai', 'data_kehadiran.nik = data_pegawai.nik');
        $this->db->join('data_jabatan', 'data_pegawai.jabatan = data_jabatan.nama_jabatan');
        $this->db->where('data_kehadiran.bulan', $bulantahun);
        $this->db->order_by('data_pegawai.nama_pegawai', 'ASC');
        $data['lap_kehadiran'] = $this->db->get()->result();

        // Load view laporan
        $this->load->view('admin/absensi/cetak_absensi', $data);
    }
}