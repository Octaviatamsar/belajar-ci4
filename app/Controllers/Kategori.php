<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\BukuModel;

class Kategori extends BaseController
{
    private KategoriModel $kategoriModel;
    private BukuModel $bukuModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
        $this->bukuModel = new BukuModel();
    }

    // ──────────────────────────────────────
    // READ - Daftar Kategori
    // ──────────────────────────────────────
    public function index(): string
    {
        $kategori = $this->kategoriModel->orderBy('nama')->findAll();

        // Hitung jumlah buku per kategori
        $kategoriWithCount = [];
        foreach ($kategori as $k) {
            $k['jumlah_buku'] = $this->bukuModel->where('kategori_id', $k['id'])->countAllResults();
            $kategoriWithCount[] = $k;
        }

        $data = [
            'title' => 'Daftar Kategori',
            'kategori' => $kategoriWithCount,
        ];

        return view('kategori/index', $data);
    }

    // ──────────────────────────────────────
    // CREATE - Form Tambah
    // ──────────────────────────────────────
    public function tambah(): string
    {
        return view('kategori/form', [
            'title' => 'Tambah Kategori',
            'kategori' => null,
        ]);
    }

    // ──────────────────────────────────────
    // CREATE - Proses Simpan
    // ──────────────────────────────────────
    public function simpan()
    {
        $nama = $this->request->getPost('nama');
        $deskripsi = $this->request->getPost('deskripsi') ?? '';

        // Validasi
        if (empty(trim($nama))) {
            session()->setFlashdata('error', 'Nama kategori tidak boleh kosong.');
            return redirect()->back()->withInput();
        }

        if ($this->kategoriModel->where('nama', $nama)->first()) {
            session()->setFlashdata('error', 'Nama kategori sudah ada.');
            return redirect()->back()->withInput();
        }

        $data = [
            'nama' => trim($nama),
            'deskripsi' => trim($deskripsi),
        ];

        $this->kategoriModel->insert($data);
        session()->setFlashdata('sukses', "Kategori '{$nama}' berhasil ditambahkan.");
        return redirect()->to('/kategori');
    }

    // ──────────────────────────────────────
    // UPDATE - Form Edit
    // ──────────────────────────────────────
    public function edit(int $id): string
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        return view('kategori/form', [
            'title' => 'Edit Kategori: ' . $kategori['nama'],
            'kategori' => $kategori,
        ]);
    }

    // ──────────────────────────────────────
    // UPDATE - Proses Update
    // ──────────────────────────────────────
    public function update(int $id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        $nama = $this->request->getPost('nama');
        $deskripsi = $this->request->getPost('deskripsi') ?? '';

        // Validasi
        if (empty(trim($nama))) {
            session()->setFlashdata('error', 'Nama kategori tidak boleh kosong.');
            return redirect()->back()->withInput();
        }

        // Cek duplikat nama, kecuali nama yang sedang diedit
        $existing = $this->kategoriModel->where('nama', $nama)->where('id !=', $id)->first();
        if ($existing) {
            session()->setFlashdata('error', 'Nama kategori sudah ada.');
            return redirect()->back()->withInput();
        }

        $data = [
            'nama' => trim($nama),
            'deskripsi' => trim($deskripsi),
        ];

        $this->kategoriModel->update($id, $data);
        session()->setFlashdata('sukses', "Kategori berhasil diperbarui.");
        return redirect()->to('/kategori');
    }

    // ──────────────────────────────────────
    // DELETE - Hapus Kategori
    // ──────────────────────────────────────
    public function hapus(int $id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori tidak ditemukan');
        }

        // Cek apakah ada buku yang menggunakan kategori ini
        $bukuCount = $this->bukuModel->where('kategori_id', $id)->countAllResults();
        if ($bukuCount > 0) {
            session()->setFlashdata('error', "Kategori '{$kategori['nama']}' tidak dapat dihapus karena masih ada {$bukuCount} buku yang menggunakannya.");
            return redirect()->to('/kategori');
        }

        $this->kategoriModel->delete($id);
        session()->setFlashdata('sukses', "Kategori '{$kategori['nama']}' berhasil dihapus.");
        return redirect()->to('/kategori');
    }
}
