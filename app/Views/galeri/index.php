<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class='row mb-4'>
    <div class='col'>
        <h3>Galeri Foto</h3>
        <p class='text-muted'>Pilih kategori untuk memfilter gambar.</p>
        <div class='mb-3'>
            <a href='<?= base_url('galeri') ?>' class='badge bg-secondary me-1 <?= empty($kategori) ? 'text-white' : 'text-light' ?>'>Semua</a>
            <?php foreach ($kategoriList as $item): ?>
                <a href='<?= base_url('galeri') . '?kategori=' . esc($item) ?>' class='badge <?= $kategori === $item ? 'bg-primary' : 'bg-light text-dark' ?> me-1'>
                    <?= esc(ucfirst($item)) ?>
                </a>
            <?php endforeach ?>
        </div>
    </div>
</div>
<div class='row row-cols-1 row-cols-md-3 g-4'>
    <?php if (empty($gambar)): ?>
        <div class='col'>
            <div class='alert alert-warning'>Tidak ada gambar untuk kategori <?= esc($kategori) ?>.</div>
        </div>
    <?php endif ?>

    <?php foreach ($gambar as $item): ?>
        <div class='col'>
            <div class='card h-100 shadow-sm'>
                <img src='<?= esc($item['url_gambar']) ?>' class='card-img-top' alt='<?= esc($item['judul']) ?>'>
                <div class='card-body'>
                    <h5 class='card-title'><?= esc($item['judul']) ?></h5>
                    <p class='card-text'><?= esc(truncate_text($item['deskripsi'], 100)) ?></p>
                </div>
                <div class='card-footer bg-white border-0'>
                    <span class='badge bg-info text-dark'><?= esc(ucfirst($item['kategori'])) ?></span>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection() ?>
