<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_Gaji_Borongan extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata('hak_akses') != '1'){
			$this->session->set_flashdata('pesan','<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Anda Belum Login!</strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
				</div>');
				redirect('login');
		}
	}

	public function index() 
	{	
		$data['title'] = "Laporan Gaji Pegawai Borongan";
		$this->load->view('template_admin/header', $data);
		$this->load->view('template_admin/sidebar');
		$this->load->view('admin/gaji/laporan_gaji_borongan', $data);
		$this->load->view('template_admin/footer');
	}

	public function cetak_laporan_gaji(){
		$data['title'] = "Cetak Laporan Gaji Pegawai Borongan";
		if((isset($_POST['bulan']) && $_POST['bulan'] != '') && (isset($_POST['tahun']) && $_POST['tahun'] != '') && (isset($_POST['minggu']) && $_POST['minggu'] != '')){
			$bulan = $_POST['bulan'];
			$tahun = $_POST['tahun'];
			$minggu = $_POST['minggu'];
			$bulantahun = $bulan . $tahun;
		} else {
			$bulan = date('m');
			$tahun = date('Y');
			$minggu = 1;
			$bulantahun = $bulan . $tahun;
		}

		$data['bulan'] = $bulan;
		$data['tahun'] = $tahun;
		$data['minggu'] = $minggu;
		$data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
		$data['cetak_gaji'] = $this->db->query("
			SELECT DISTINCT data_pegawai.nik, data_pegawai.nama_pegawai,
				data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan,
				data_jabatan.tarif_borongan, target_mingguan.target_mingguan,
				data_kehadiran.alpha
			FROM data_pegawai
			INNER JOIN (
				SELECT nik, bulan, MAX(alpha) as alpha
				FROM data_kehadiran
				WHERE bulan = '$bulantahun'
				GROUP BY nik, bulan
			) data_kehadiran ON data_kehadiran.nik = data_pegawai.nik
			INNER JOIN data_jabatan ON data_jabatan.nama_jabatan = data_pegawai.jabatan
			LEFT JOIN (
				SELECT nik_pegawai, bulan_target, tahun_target, mingguke, MAX(target_mingguan) as target_mingguan
				FROM target_mingguan
				WHERE bulan_target = '$bulan' AND tahun_target = '$tahun' AND mingguke = '$minggu'
				GROUP BY nik_pegawai, bulan_target, tahun_target, mingguke
			) target_mingguan ON target_mingguan.nik_pegawai = data_pegawai.nik
			WHERE data_jabatan.jenis_gaji = 'Borongan'
			ORDER BY data_pegawai.nama_pegawai ASC
		")->result();

		$this->load->view('admin/gaji/cetak_gaji_borongan', $data);
	}
}