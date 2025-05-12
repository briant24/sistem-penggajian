<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title?></title>
    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            color: #003087;
            margin-bottom: 5px;
        }
        h2 {
            font-size: 18px;
            text-align: center;
            color: #003087;
            margin-top: 0;
        }
        hr {
            width: 60%;
            border: 2px solid #003087;
            margin: 15px auto;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            width: 20%;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .main-table th {
            background-color: #e3f2fd;
            color: #003087;
            padding: 10px;
            border: 1px solid #bcd4e6;
            font-weight: bold;
            text-align: left;
        }
        .main-table td {
            padding: 10px;
            border: 1px solid #bcd4e6;
            text-align: left;
        }
        .main-table .total {
            background-color: #f0f7ff;
            font-weight: bold;
            color: #003087;
        }
        .main-table .total td {
            padding: 12px;
        }
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        .signature-table td {
            vertical-align: top;
            padding: 10px;
            text-align: center;
        }
        .signature-table .pegawai {
            width: 50%;
        }
        .signature-table .finance {
            width: 50%;
        }
        .signature-table p {
            margin: 5px 0;
        }
        .signature-table .signature-line {
            border-bottom: 1px solid #333;
            width: 150px;
            margin: 10px auto;
        }
        @media print {
            body {
                margin: 0;
            }
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>KONVEKSI KAMPOENG BUSANA</h1>
        <h2>Slip Gaji Pegawai Borongan</h2>
        <hr>

        <?php if ($print_slip) : ?>
        <table class="info-table">
            <tr>
                <td>NIK</td>
                <td><?php echo $print_slip->nik ?? '-' ?></td>
            </tr>
            <tr>
                <td>Nama Pegawai</td>
                <td><?php echo $print_slip->nama_pegawai ?? '-' ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td><?php echo $print_slip->nama_jabatan ?? '-' ?></td>
            </tr>
            <tr>
                <td>Bulan</td>
                <td>
                    <?php
                    $nama_bulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    echo isset($nama_bulan[$print_slip->bulan_target]) ? $nama_bulan[$print_slip->bulan_target] : ($print_slip->bulan_target ?: '-');
                    ?>
                </td>
            </tr>
            <tr>
                <td>Tahun</td>
                <td><?php echo $print_slip->tahun_target ?? '-' ?></td>
            </tr>
            <tr>
                <td>Minggu</td>
                <td><?php echo $print_slip->mingguke ?? '-' ?></td>
            </tr>
        </table>

        <table class="main-table">
            <tr>
                <th colspan="2">Pendapatan</th>
                <th>Potongan</th>
            </tr>
            <tr>
                <td>Target Produksi</td>
                <td><?php echo $print_slip->target_mingguan ?? 0 ?> unit</td>
                <td rowspan="2">
                    Potongan (Alpha: <?php echo $print_slip->alpha ?? 0 ?> hari): 
                    <?php $potongan_gaji = ($print_slip->alpha ?? 0) * (!empty($potongan) ? ($potongan[0]->jml_potongan ?? 0) : 0); ?>
                    Rp. <?php echo number_format($potongan_gaji, 0, ',', '.') ?>
                </td>
            </tr>
            <tr>
                <td>Tarif Borongan</td>
                <td>Rp. <?php echo number_format($print_slip->tarif_borongan ?? 0, 0, ',', '.') ?></td>
            </tr>
            <tr class="total">
                <td>Total Pendapatan</td>
                <td colspan="2">
                    <?php 
                    $target = $print_slip->target_mingguan ?? 0;
                    $tarif = $print_slip->tarif_borongan ?? 0;
                    $total_gaji = ($target * $tarif) - $potongan_gaji;
                    ?>
                    Rp. <?php echo number_format($total_gaji, 0, ',', '.') ?>
                </td>
            </tr>
        </table>

        <table class="signature-table">
            <tr>
                <td class="pegawai">
                    <p>Pegawai</p>
                    <br><br>
                    <p class="font-weight-bold"><?php echo $print_slip->nama_pegawai ?? '-' ?></p>
                </td>
                <td class="finance">
                    <p>Bandung, <?php echo date('d M Y') ?> <br> Finance</p>
                    <br><br>
                    <p class="signature-line"></p>
                </td>
            </tr>
        </table>
        <?php else : ?>
        <p class="text-center">Data slip gaji tidak ditemukan.</p>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>