<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class='d-flex justify-content-between align-items-center mb-4'>
    <div>
        <h2><i class='bi bi-graph-up'></i> Statistik Buku</h2>
        <p class='text-muted mb-0'>Analisis data koleksi perpustakaan</p>
    </div>
    <a href='<?= base_url('buku') ?>' class='btn btn-secondary'>
        <i class='bi bi-arrow-left'></i> Kembali ke Daftar Buku
    </a>
</div>

<!-- Statistik Ringkas -->
<div class='row mb-4'>
    <div class='col-md-4'>
        <div class='card bg-primary text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between align-items-center'>
                    <div>
                        <h6 class='card-title mb-1'>Total Buku</h6>
                        <h3 class='mb-0'><?= $statistik['total'] ?></h3>
                    </div>
                    <i class='bi bi-book display-4 opacity-50'></i>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-4'>
        <div class='card bg-success text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between align-items-center'>
                    <div>
                        <h6 class='card-title mb-1'>Total Stok</h6>
                        <h3 class='mb-0'><?= $statistik['total_stok'] ?></h3>
                    </div>
                    <i class='bi bi-boxes display-4 opacity-50'></i>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-4'>
        <div class='card bg-info text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between align-items-center'>
                    <div>
                        <h6 class='card-title mb-1'>Rata-rata Stok</h6>
                        <h3 class='mb-0'><?= $statistik['average_stok'] ?></h3>
                    </div>
                    <i class='bi bi-graph-up display-4 opacity-50'></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Distribusi per Kategori -->
<div class='row mb-4'>
    <div class='col-md-12'>
        <div class='card'>
            <div class='card-header bg-primary text-white'>
                <h5 class='mb-0'><i class='bi bi-pie-chart'></i> Distribusi Buku Per Kategori</h5>
            </div>
            <div class='card-body'>
                <div class='table-responsive'>
                    <table class='table table-hover table-bordered align-middle'>
                        <thead class='table-light'>
                            <tr>
                                <th width='60'>No.</th>
                                <th>Nama Kategori</th>
                                <th width='150'>Jumlah Buku</th>
                                <th width='150'>Total Stok</th>
                                <th width='150'>Rata-rata Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistik['per_kategori'] as $i => $d): ?>
                                <tr>
                                    <td class='text-center'><?= $i + 1 ?></td>
                                    <td>
                                        <strong><?= esc($d['nama'] ?? 'Tanpa Kategori') ?></strong>
                                    </td>
                                    <td class='text-center'>
                                        <span class='badge bg-primary'><?= $d['jumlah'] ?></span>
                                    </td>
                                    <td class='text-center'>
                                        <span class='badge bg-success'><?= $d['stok'] ?></span>
                                    </td>
                                    <td class='text-center'>
                                        <?= round($d['stok'] / $d['jumlah'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top 5 Buku Stok Terbanyak -->
<div class='row mb-4'>
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-header bg-success text-white'>
                <h5 class='mb-0'><i class='bi bi-arrow-up-circle'></i> Top 5 Buku Stok Terbanyak</h5>
            </div>
            <div class='card-body p-0'>
                <div class='table-responsive'>
                    <table class='table table-hover table-bordered mb-0 align-middle'>
                        <thead class='table-light'>
                            <tr>
                                <th width='40'>No.</th>
                                <th>Judul Buku</th>
                                <th width='80'>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($statistik['top_stok'] as $i => $b): ?>
                                <tr>
                                    <td class='text-center'><?= $i + 1 ?></td>
                                    <td>
                                        <small><?= esc($b['judul']) ?></small>
                                    </td>
                                    <td class='text-center'>
                                        <span class='badge bg-success'><?= $b['stok'] ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Stok Kosong (Perlu Restock) -->
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-header bg-danger text-white'>
                <h5 class='mb-0'><i class='bi bi-exclamation-circle'></i> Buku Perlu Restock (Stok = 0)</h5>
            </div>
            <div class='card-body p-0'>
                <?php if (empty($statistik['stok_kosong'])): ?>
                    <div class='p-3 text-center text-muted'>
                        <i class='bi bi-check-circle display-6'></i>
                        <p class='mt-2'>Semua buku memiliki stok tersedia</p>
                    </div>
                <?php else: ?>
                    <div class='table-responsive'>
                        <table class='table table-hover table-bordered mb-0 align-middle'>
                            <thead class='table-light'>
                                <tr>
                                    <th width='40'>No.</th>
                                    <th>Judul Buku</th>
                                    <th width='100'>Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($statistik['stok_kosong'] as $i => $b): ?>
                                    <tr class='table-danger'>
                                        <td class='text-center'><?= $i + 1 ?></td>
                                        <td>
                                            <small><?= esc($b['judul']) ?></small>
                                        </td>
                                        <td>
                                            <small><?= esc($b['nama_kategori'] ?? '-') ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>