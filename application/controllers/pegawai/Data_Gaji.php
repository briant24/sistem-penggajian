<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_Gaji extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '2') {
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
        $data['title'] = "Data Gaji";
        $nik = $this->session->userdata('nik');
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

        // Cek jenis gaji pegawai
        $this->db->select('data_jabatan.jenis_gaji');
        $this->db->from('data_pegawai');
        $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
        $this->db->where('data_pegawai.nik', $nik);
        $data['is_borongan'] = ($this->db->get()->row()->jenis_gaji == 'Borongan');

        if ($data['is_borongan']) {
            // Gaji borongan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.tarif_borongan, target_mingguan.id, target_mingguan.target_mingguan, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke, data_kehadiran.alpha');
            $this->db->from('data_pegawai');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->join('target_mingguan', 'target_mingguan.nik_pegawai = data_pegawai.nik', 'left');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik AND data_kehadiran.bulan = CONCAT(target_mingguan.bulan_target, target_mingguan.tahun_target)', 'left');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->order_by('target_mingguan.tahun_target DESC, target_mingguan.bulan_target DESC, target_mingguan.mingguke DESC');
            $data['gaji'] = $this->db->get()->result();
        } else {
            // Gaji bulanan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan, data_kehadiran.id_kehadiran');
            $this->db->from('data_pegawai');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->order_by('data_kehadiran.bulan DESC');
            $data['gaji'] = $this->db->get()->result();
        }

        $this->load->view('template_pegawai/header', $data);
        $this->load->view('template_pegawai/sidebar');
        $this->load->view('pegawai/data_gaji', $data);
        $this->load->view('template_pegawai/footer');
    }

    public function cetak_slip($id) {
        $data['title'] = 'Cetak Slip Gaji';
        $nik = $this->session->userdata('nik');
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();

        // Cek jenis gaji
        $this->db->select('data_jabatan.jenis_gaji');
        $this->db->from('data_pegawai');
        $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
        $this->db->where('data_pegawai.nik', $nik);
        $data['is_borongan'] = ($this->db->get()->row()->jenis_gaji == 'Borongan');

        if ($data['is_borongan']) {
            // Slip borongan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.nama_jabatan, data_jabatan.tarif_borongan, target_mingguan.target_mingguan, target_mingguan.bulan_target, target_mingguan.tahun_target, target_mingguan.mingguke, data_kehadiran.alpha');
            $this->db->from('data_pegawai');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->join('target_mingguan', 'target_mingguan.nik_pegawai = data_pegawai.nik', 'left');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik AND data_kehadiran.bulan = CONCAT(LPAD(target_mingguan.bulan_target, 2, "0"), target_mingguan.tahun_target)', 'left');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->where('target_mingguan.id', $id);
            $data['print_slip'] = $this->db->get()->row();

            // Debug: Cek data
            // echo '<pre>'; var_dump($data['print_slip']); die();

            if (!$data['print_slip']) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Data slip gaji tidak ditemukan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>');
                redirect('pegawai/data_gaji');
            }

            $this->load->view('pegawai/cetak_slip_gaji_borongan', $data);
        } else {
            // Slip bulanan
            $this->db->select('data_pegawai.nik, data_pegawai.nama_pegawai, data_jabatan.nama_jabatan, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, data_kehadiran.alpha, data_kehadiran.bulan');
            $this->db->from('data_pegawai');
            $this->db->join('data_kehadiran', 'data_kehadiran.nik = data_pegawai.nik');
            $this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
            $this->db->where('data_pegawai.nik', $nik);
            $this->db->where('data_kehadiran.id_kehadiran', $id);
            $data['print_slip'] = $this->db->get()->row();

            // Debug: Cek data
            // echo '<pre>'; var_dump($data['print_slip']); die();

            if (!$data['print_slip']) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Data slip gaji tidak ditemukan!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                    </div>');
                redirect('pegawai/data_gaji');
            }

            $this->load->view('pegawai/cetak_slip_gaji', $data);
        }
    }
}