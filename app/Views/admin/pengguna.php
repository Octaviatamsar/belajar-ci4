<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class='d-flex justify-content-between align-items-center mb-4'>
    <div>
        <h2><i class='bi bi-people'></i> Manajemen Pengguna</h2>
        <p class='text-muted mb-0'>Kelola akun pengguna, status aktif, dan role.</p>
    </div>
    <a href='<?= base_url('admin') ?>' class='btn btn-outline-secondary'>
        <i class='bi bi-arrow-left'></i> Kembali ke Dashboard
    </a>
</div>
<div class='card shadow'>
    <div class='card-body'>
        <div class='table-responsive'>
            <table class='table table-striped table-hover align-middle'>
                <thead class='table-light'>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($user['username']) ?></td>
                            <td><?= esc($user['nama_lengkap']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <?php if ($currentUserId === (int) $user['id']): ?>
                                    <span class='badge bg-dark'><?= esc($user['role']) ?> (Anda)</span>
                                <?php else: ?>
                                    <form action='<?= base_url("admin/pengguna/ubah-role/{$user['id']}") ?>' method='post' class='d-flex gap-2'>
                                        <?= csrf_field() ?>
                                        <select name='role' class='form-select form-select-sm'>
                                            <?php foreach (['admin', 'petugas', 'anggota'] as $roleOption): ?>
                                                <option value='<?= esc($roleOption) ?>' <?= $user['role'] === $roleOption ? 'selected' : '' ?>>
                                                    <?= ucfirst($roleOption) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type='submit' class='btn btn-sm btn-outline-primary'>Simpan</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class='badge bg-<?= $user['aktif'] ? 'success' : 'secondary' ?>'>
                                    <?= $user['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($currentUserId === (int) $user['id']): ?>
                                    <button type='button' class='btn btn-sm btn-outline-secondary' disabled>
                                        Tidak dapat diubah
                                    </button>
                                <?php else: ?>
                                    <form action='<?= base_url("admin/pengguna/toggle/{$user['id']}") ?>' method='post' class='d-inline'>
                                        <?= csrf_field() ?>
                                        <button type='submit' class='btn btn-sm btn-<?= $user['aktif'] ? 'warning' : 'success' ?>'>
                                            <?= $user['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>