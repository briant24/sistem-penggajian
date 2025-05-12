<?php
class Data_Penggajian_Borongan extends CI_Controller {

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
        $data['title'] = "Data Gaji Pegawai Borongan";
        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $minggu = isset($_GET['minggu']) ? $_GET['minggu'] : 1;
            $bulantahun = $bulan . $tahun;
        } else {
            $bulan = date('m');
            $tahun = date('Y');
            $minggu = 1;
            $bulantahun = $bulan . $tahun;
        }
        $data['potongan'] = $this->ModelPenggajian->get_data('potongan_gaji')->result();
        $data['gaji'] = $this->db->query("
            SELECT DISTINCT data_pegawai.nik, data_pegawai.nama_pegawai,
                data_pegawai.jenis_kelamin, data_jabatan.nama_jabatan, 
                data_jabatan.gaji_pokok, data_jabatan.tj_transport, 
                data_jabatan.uang_makan, data_jabatan.jenis_gaji, 
                data_jabatan.tarif_borongan, data_kehadiran.alpha,
                target_mingguan.target_mingguan
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
        $data['target'] = $this->db->query("SELECT * FROM target_mingguan 
            WHERE bulan_target = '$bulan' AND tahun_target = '$tahun' AND mingguke = '$minggu'")->result();
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['minggu'] = $minggu;
        $this->load->view('template_admin/header', $data);
        $this->load->view('template_admin/sidebar');
        $this->load->view('admin/gaji/data_gaji_borongan', $data);
        $this->load->view('template_admin/footer');
    }

    public function cetak_gaji() {
        $data['title'] = "Cetak Daftar Gaji Pegawai Borongan";
        if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
            $bulan = $_GET['bulan'];
            $tahun = $_GET['tahun'];
            $minggu = isset($_GET['minggu']) ? $_GET['minggu'] : 1;
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

    public function simpan_target() {
        $nik = $this->input->post('nik');
        $minggu = $this->input->post('minggu');
        $bulan = $this->input->post('bulan');
        $tahun = $this->input->post('tahun');
        $target = $this->input->post('target_mingguan');
        
        $data = array(
            'nik_pegawai' => $nik,
            'mingguke' => $minggu,
            'bulan_target' => $bulan,
            'tahun_target' => $tahun,
            'target_mingguan' => $target
        );

        $this->ModelPenggajian->insert_data($data, 'target_mingguan');
        
        redirect('admin/data_penggajian_borongan?bulan=' . $bulan . '&tahun=' . $tahun . '&minggu=' . $minggu);
    }
}