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
    </style>
</head>
<body>
    <center>
        <h1>KONVEKSI KAMPOENG BUSANA</h1>
        <h2>Laporan Kehadiran Pegawai</h2>
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
                echo isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan;
                ?>
            </td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>:</td>
            <td><?php echo $tahun?></td>
        </tr>
    </table>

    <?php if (!empty($lap_kehadiran)) { ?>
        <table class="table table-bordered table-triped">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">NIK</th>
                <th class="text-center">Nama Pegawai</th>
                <th class="text-center">Jabatan</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Sakit</th>
                <th class="text-center">Alpha</th>
            </tr>
            <?php $no=1; foreach($lap_kehadiran as $l) : ?>
            <tr>
                <td class="text-center"><?php echo $no++ ?></td>
                <td class="text-center"><?php echo isset($l->nik) ? $l->nik : '-' ?></td>
                <td class="text-center"><?php echo isset($l->nama_pegawai) ? $l->nama_pegawai : '-' ?></td>
                <td class="text-center"><?php echo isset($l->nama_jabatan) ? $l->nama_jabatan : '-' ?></td>
                <td class="text-center"><?php echo isset($l->hadir) ? $l->hadir : '0' ?></td>
                <td class="text-center"><?php echo isset($l->sakit) ? $l->sakit : '0' ?></td>
                <td class="text-center"><?php echo isset($l->alpha) ? $l->alpha : '0' ?></td>
            </tr>
            <?php endforeach ;?>
        </table>
    <?php } else { ?>
        <p style="text-align: center; color: red; margin-top: 20px;">
            Data absensi untuk bulan <?php echo isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan ?> tahun <?php echo $tahun ?> tidak ditemukan.
        </p>
    <?php } ?>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>