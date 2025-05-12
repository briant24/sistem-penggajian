<?php

class ModelPenggajian extends CI_model {

    public function get_data($table) {
        return $this->db->get($table);
    }

    public function insert_data($data, $table) {
        $this->db->insert($table, $data);
    }

    public function update_data($table, $data, $whare) {
        $this->db->update($table, $data, $whare);
    }

    public function delete_data($whare, $table) {
        $this->db->where($whare);
        $this->db->delete($table);
    }

    public function insert_batch($table = null, $data = array()) {
        $jumlah = count($data);
        if ($jumlah > 0) {
            $this->db->insert_batch($table, $data);
        }
    }

    public function cek_login() {
        $username = set_value('username');
        $password = set_value('password');

        $result = $this->db->where('username', $username)
                           ->where('password', md5($password))
                           ->limit(1)
                           ->get('data_pegawai');
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return FALSE;
        }
    }

    // ✅ Tambahan 
    public function tampil_data_borongan($bulantahun)
{
	$this->db->select('data_kehadiran.*, data_pegawai.nama_pegawai, data_pegawai.jenis_kelamin, 
		data_jabatan.nama_jabatan, data_jabatan.gaji_pokok, data_jabatan.tj_transport, data_jabatan.uang_makan, 
		data_borongan.tarif_borongan, data_borongan.target_mingguan');
	$this->db->from('data_kehadiran');
	$this->db->join('data_pegawai', 'data_kehadiran.nik = data_pegawai.nik');
	$this->db->join('data_jabatan', 'data_jabatan.nama_jabatan = data_pegawai.jabatan');
	$this->db->join('data_borongan', 'data_borongan.nik = data_pegawai.nik', 'left');
	$this->db->where('data_kehadiran.bulan', $bulantahun);

	return $this->db->get()->result();
}

    }


?>
 