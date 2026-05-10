<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class='d-flex justify-content-between align-items-center mb-4'>
    <div>
        <h2><i class='bi bi-tags'></i> Daftar Kategori</h2>
        <p class='text-muted mb-0'>Total: <?= count($kategori) ?> kategori</p>
    </div>
    <a href='<?= base_url('kategori/tambah') ?>' class='btn btn-primary'>
        <i class='bi bi-plus-circle'></i> Tambah Kategori
    </a>
</div>

<!-- Tabel Kategori -->
<?php if (empty($kategori)): ?>
    <div class='text-center py-5'>
        <i class='bi bi-inbox display-1 text-muted'></i>
        <h4 class='mt-3 text-muted'>Belum ada kategori</h4>
        <p><a href='<?= base_url('kategori/tambah') ?>' class='btn btn-primary btn-sm'>Tambah Kategori Pertama</a></p>
    </div>
<?php else: ?>
    <div class='table-responsive'>
        <table class='table table-hover table-bordered align-middle'>
            <thead class='table-primary'>
                <tr>
                    <th width='60'>No.</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th width='120'>Jumlah Buku</th>
                    <th width='130'>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kategori as $i => $k): ?>
                    <tr>
                        <td class='text-center'><?= $i + 1 ?></td>
                        <td>
                            <strong><?= esc($k['nama']) ?></strong>
                        </td>
                        <td>
                            <?php if (!empty($k['deskripsi'])): ?>
                                <small><?= esc(truncate_text($k['deskripsi'], 50)) ?></small>
                            <?php else: ?>
                                <span class='text-muted'>-</span>
                            <?php endif; ?>
                        </td>
                        <td class='text-center'>
                            <span class='badge bg-<?= $k['jumlah_buku'] > 0 ? 'success' : 'secondary' ?>'>
                                <?= $k['jumlah_buku'] ?> buku
                            </span>
                        </td>
                        <td class='text-center'>
                            <a href='<?= base_url('kategori/edit/' . $k['id']) ?>' class='btn btn-sm btn-warning' title='Edit'>
                                <i class='bi bi-pencil'></i>
                            </a>
                            <a href='<?= base_url('kategori/hapus/' . $k['id']) ?>'
                                class='btn btn-sm btn-danger'
                                title='Hapus'
                                onclick="return confirm('Yakin ingin menghapus kategori &quot;<?= esc($k['nama'], 'js') ?>&quot;?')">
                                <i class='bi bi-trash'></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>