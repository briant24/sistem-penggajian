<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card mx-auto" style="width: 35%">
        <div class="card-header bg-primary text-white text-center">
            Filter Slip Gaji Pegawai Bulanan
        </div>

        <form method="POST" action="<?php echo base_url('admin/slip_gaji_bulanan/cetak_slip_gaji')?>">
            <div class="card-body">
                <div class="form-group row">
                    <label for="nik" class="col-sm-3 col-form-label">Pegawai</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="nik" id="nik" required>
                            <option value="">Pilih Pegawai</option>
                            <?php foreach ($pegawai as $p) : ?>
                                <option value="<?php echo $p->nik ?>"><?php echo $p->nama_pegawai ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="bulan" class="col-sm-3 col-form-label">Bulan</label>
                    <div class="col-sm-9">
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
                </div>

                <div class="form-group row">
                    <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="tahun" id="tahun" required>
                            <option value="">Pilih Tahun</option>
                            <?php 
                            $tahun_sekarang = date('Y');
                            for ($i = 2020; $i <= $tahun_sekarang + 5; $i++) { ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <button style="width: 100%" type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Cetak Slip Gaji</button>
            </div>
        </form>
    </div>
</div>
<!-- /.container-fluid -->