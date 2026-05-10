<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class='row justify-content-center'>
    <div class='col-md-8'>
        <div class='card shadow-sm'>
            <div class='card-header bg-primary text-white'>
                <h4 class='mb-0'>Profil Mahasiswa</h4>
            </div>
            <div class='card-body'>
                <div class='row mb-3'>
                    <div class='col-md-6'>
                        <p class='mb-1 fw-bold'>NPM</p>
                        <p><?= esc($npm) ?></p>
                    </div>
                    <div class='col-md-6'>
                        <p class='mb-1 fw-bold'>Nama Lengkap</p>
                        <p><?= esc($nama) ?></p>
                    </div>
                </div>
                <div class='row mb-3'>
                    <div class='col-md-6'>
                        <p class='mb-1 fw-bold'>Program Studi</p>
                        <p><?= esc($prodi) ?></p>
                    </div>
                    <div class='col-md-6'>
                        <p class='mb-1 fw-bold'>Angkatan</p>
                        <p><?= esc($angkatan) ?></p>
                    </div>
                </div>
                <div class='row mb-4'>
                    <div class='col-md-6'>
                        <p class='mb-1 fw-bold'>IPK</p>
                        <?php
                        $badgeClass = 'bg-danger';
                        if ($ipk >= 3.5) {
                            $badgeClass = 'bg-success';
                        } elseif ($ipk >= 3.0) {
                            $badgeClass = 'bg-warning text-dark';
                        }
                        ?>
                        <span class='badge <?= esc($badgeClass) ?>'><?= esc(number_format($ipk, 2)) ?></span>
                    </div>
                </div>
                <h5 class='mb-3'>Mata Kuliah Semester Ini</h5>
                <ul class='list-group'>
                    <?php foreach ($mata_kuliah as $matkul): ?>
                        <li class='list-group-item'><?= esc($matkul) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
