<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Upah</h6>
        </div>
        <div class="card-body">
            <form method="get">
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="month" class="form-control" onchange="this.form.submit()">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $month == $i ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="year" class="form-control" onchange="this.form.submit()">
                        <?php for ($i = 2020; $i <= 2025; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $year == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Total Upah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($report)): ?>
                            <?php foreach ($report as $row): ?>
                                <tr>
                                    <td><?php echo $row->name; ?></td>
                                    <td><?php echo number_format($row->total_upah, 0, ',', '.'); ?></td>
                                    <td><?php echo $row->date; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3">Belum ada data laporan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>