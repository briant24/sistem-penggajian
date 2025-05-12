<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>

  <table class="table table-striped table-bordered">
    <tr>
      <th>Periode</th>
      <?php if ($is_borongan) { ?>
        <th>Target Produksi</th>
        <th>Tarif Borongan</th>
      <?php } else { ?>
        <th>Gaji Pokok</th>
        <th>Tunjangan Transportasi</th>
        <th>Uang Makan</th>
      <?php } ?>
      <th>Potongan</th>
      <th>Total Gaji</th>
      <th>Cetak Slip</th>
    </tr>

    <?php $potongan_gaji = $potongan[0]->jml_potongan ?? 0; ?>

    <?php foreach ($gaji as $g) : ?>
      <?php 
      if ($is_borongan) {
        $periode = sprintf('Minggu %d, %s %s', $g->mingguke, $nama_bulan[$g->bulan_target] ?? $g->bulan_target, $g->tahun_target);
        $pot_gaji = $g->alpha * $potongan_gaji;
        $total_gaji = ($g->target_mingguan * $g->tarif_borongan) - $pot_gaji;
      } else {
        $bulan = substr($g->bulan, 0, 2);
        $tahun = substr($g->bulan, 2, 4);
        $periode = sprintf('%s %s', $nama_bulan[$bulan] ?? $bulan, $tahun);
        $pot_gaji = $g->alpha * $potongan_gaji;
        $total_gaji = ($g->gaji_pokok + $g->tj_transport + $g->uang_makan) - $pot_gaji;
      }
      ?>
      <tr>
        <td><?php echo $periode ?></td>
        <?php if ($is_borongan) { ?>
          <td><?php echo $g->target_mingguan ?? 0 ?> unit</td>
          <td>Rp. <?php echo number_format($g->tarif_borongan ?? 0, 0, ',', '.') ?></td>
        <?php } else { ?>
          <td>Rp. <?php echo number_format($g->gaji_pokok, 0, ',', '.') ?></td>
          <td>Rp. <?php echo number_format($g->tj_transport, 0, ',', '.') ?></td>
          <td>Rp. <?php echo number_format($g->uang_makan, 0, ',', '.') ?></td>
        <?php } ?>
        <td>Rp. <?php echo number_format($pot_gaji, 0, ',', '.') ?></td>
        <td>Rp. <?php echo number_format($total_gaji, 0, ',', '.') ?></td>
        <td>
          <center>
            <a class="btn btn-sm btn-primary" href="<?php echo base_url('pegawai/data_gaji/cetak_slip/' . ($is_borongan ? $g->id : $g->id_kehadiran)) ?>"><i class="fas fa-print"></i></a>
          </center>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

</div>
<!-- /.container-fluid -->