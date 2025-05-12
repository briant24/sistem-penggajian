<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title?></title>
    <style type="text/css">
        body {
            font-family: Arial;
            color: black;
        }
        table.table-bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.table-bordered th, table.table-bordered td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        hr {
            width: 50%;
            border-width: 5px;
            color: black;
        }
    </style>
</head>
<body>
    <center>
        <h1>KONVEKSI KAMPOENG BUSANA</h1>
        <h2>Daftar Gaji Pegawai Borongan</h2>
        <hr>
    </center>

    <table>
        <tr>
            <td>Bulan</td>
            <td>:</td>
            <td>
                <?php
                $nama_bulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                $bulan = isset($bulan) ? $bulan : date('m');
                echo isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan;
                ?>
            </td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>:</td>
            <td><?php echo isset($tahun) ? $tahun : date('Y')?></td>
        </tr>
        <tr>
            <td>Minggu</td>
            <td>:</td>
            <td><?php echo isset($minggu) ? $minggu : 1?></td>
        </tr>
    </table>

    <?php if (!empty($cetak_gaji)) { ?>
        <table class="table table-bordered table-striped">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">NIK</th>
                <th class="text-center">Nama Pegawai</th>
                <th class="text-center">Jenis Kelamin</th>
                <th class="text-center">Jabatan</th>
                <th class="text-center">Target Produksi</th>
                <th class="text-center">Tarif Borongan</th>
                <th class="text-center">Potongan (Alpha)</th>
                <th class="text-center">Total Gaji</th>
            </tr>
            <?php 
            $no = 1;
            $potongan = !empty($potongan) ? $potongan[0]->jml_potongan : 0;
            foreach ($cetak_gaji as $g) : 
                $target = isset($g->target_mingguan) ? $g->target_mingguan : 0;
                $tarif = isset($g->tarif_borongan) ? $g->tarif_borongan : 10000; // Default Rp10.000
                $potongan_gaji = isset($g->alpha) ? $g->alpha * $potongan : 0;
                $total_gaji = ($target * $tarif) - $potongan_gaji;
            ?>
            <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $g->nik ?></td>
                <td><?php echo $g->nama_pegawai ?></td>
                <td><?php echo $g->jenis_kelamin ?></td>
                <td><?php echo $g->nama_jabatan ?></td>
                <td><?php echo $target ?> unit</td>
                <td>Rp. <?php echo number_format($tarif, 0, ',', '.') ?></td>
                <td>Rp. <?php echo number_format($potongan_gaji, 0, ',', '.') ?></td>
                <td>Rp. <?php echo number_format($total_gaji, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php } else { ?>
        <p style="text-align: center; color: red; margin-top: 20px;">
            Data gaji borongan untuk bulan <?php echo isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan ?> tahun <?php echo isset($tahun) ? $tahun : date('Y') ?> minggu <?php echo isset($minggu) ? $minggu : 1 ?> tidak ditemukan.
        </p>
    <?php } ?>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>