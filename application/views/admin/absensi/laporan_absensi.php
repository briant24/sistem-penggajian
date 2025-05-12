<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?></h1>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-primary text-white">
            Filter Laporan Absensi Pegawai
        </div>
        <div class="card-body">
            <form class="form-inline" action="<?php echo base_url('admin/laporan_absensi/cetak_laporan_absensi') ?>" method="get">
                <div class="form-group mb-2">
                    <label for="bulan" class="mr-2">Bulan:</label>
                    <select class="form-control" name="bulan" id="bulan" required>
                        <option value="">Pilih Bulan</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                <div class="form-group mb-2 ml-4">
                    <label for="tahun" class="mr-2">Tahun:</label>
                    <select class="form-control" name="tahun" id="tahun" required>
                        <option value="">Pilih Tahun</option>
                        <?php
                        $tahun_sekarang = date('Y');
                        for ($i = $tahun_sekarang - 5; $i <= $tahun_sekarang + 5; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2 ml-3"><i class="fas fa-filter"></i> Tampilkan Laporan</button>
            </form>
        </div>
    </div>
</div>