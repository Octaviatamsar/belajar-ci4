<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Pengguna extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        return view('admin/pengguna', [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->getDaftarUser(),
            'currentUserId' => session()->get('user_id'),
        ]);
    }

    public function toggleAktif(int $id)
    {
        $currentUserId = session()->get('user_id');
        if ($currentUserId === $id) {
            session()->setFlashdata('error', 'Anda tidak boleh mengubah status akun sendiri.');
            return redirect()->back();
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->back();
        }

        $this->userModel->update($id, ['aktif' => $user['aktif'] ? 0 : 1]);

        $status = $user['aktif'] ? 'dinonaktifkan' : 'diaktifkan';
        session()->setFlashdata('sukses', "Akun pengguna '{$user['username']}' berhasil {$status}.");
        return redirect()->back();
    }

    public function ubahRole(int $id)
    {
        $currentUserId = session()->get('user_id');
        if ($currentUserId === $id) {
            session()->setFlashdata('error', 'Anda tidak boleh mengubah role akun sendiri.');
            return redirect()->back();
        }

        $role = $this->request->getPost('role');
        $validRoles = ['admin', 'petugas', 'anggota'];

        if (!in_array($role, $validRoles, true)) {
            session()->setFlashdata('error', 'Role tidak valid.');
            return redirect()->back();
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return redirect()->back();
        }

        $this->userModel->update($id, ['role' => $role]);
        session()->setFlashdata('sukses', "Role pengguna '{$user['username']}' berhasil diperbarui.");
        return redirect()->back();
    }
}
