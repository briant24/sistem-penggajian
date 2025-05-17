<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Hasil Perhitungan Upah</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Upah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($payroll)): ?>
                            <?php foreach ($payroll as $row): ?>
                                <tr>
                                    <td><?php echo $row->name; ?></td>
                                    <td><?php echo number_format($row->amount, 0, ',', '.'); ?></td>
                                    <td><?php echo $row->date; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3">Belum ada data upah.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>