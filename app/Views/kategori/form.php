<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php $isEdit = !is_null($kategori); ?>
<div class='row justify-content-center'>
    <div class='col-md-6'>
        <div class='card shadow-sm'>
            <div class='card-header bg-primary text-white'>
                <h4 class='mb-0'>
                    <i class='bi bi-<?= $isEdit ? 'pencil' : 'plus-circle' ?>'></i>
                    <?= esc($title) ?>
                </h4>
            </div>
            <div class='card-body'>
                <!-- Error dari session -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class='alert alert-danger alert-dismissible fade show'>
                        <i class='bi bi-exclamation-circle me-2'></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    </div>
                <?php endif; ?>

                <form action='<?= base_url($isEdit ? 'kategori/update/' . $kategori['id'] : 'kategori/simpan') ?>'
                    method='post'>
                    <?= csrf_field() ?>

                    <div class='mb-3'>
                        <label class='form-label fw-bold'>Nama Kategori <span class='text-danger'>*</span></label>
                        <input type='text' name='nama' class='form-control'
                            value='<?= esc(old('nama', $kategori['nama'] ?? '')) ?>'
                            placeholder='Contoh: Teknologi, Fiksi, Sejarah'
                            required>
                        <small class='text-muted'>Nama kategori harus unik dan tidak boleh kosong.</small>
                    </div>

                    <div class='mb-3'>
                        <label class='form-label fw-bold'>Deskripsi</label>
                        <textarea name='deskripsi' class='form-control' rows='4'
                            placeholder='Berikan penjelasan singkat tentang kategori ini (opsional)...'><?= esc(old('deskripsi', $kategori['deskripsi'] ?? '')) ?></textarea>
                    </div>

                    <div class='d-grid gap-2 d-md-flex justify-content-md-end'>
                        <a href='<?= base_url('kategori') ?>' class='btn btn-secondary'>
                            <i class='bi bi-x-circle'></i> Batal
                        </a>
                        <button type='submit' class='btn btn-primary'>
                            <i class='bi bi-check-circle'></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>