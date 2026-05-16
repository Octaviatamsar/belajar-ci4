<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\KategoriModel;

class Buku extends BaseController
{
    private BukuModel $bukuModel;
    private KategoriModel $kategoriModel;
    public function __construct()
    {
        $this->bukuModel = new BukuModel();
        $this->kategoriModel = new KategoriModel();
    }
    // ──────────────────────────────────────
    // READ - Daftar Buku dengan Search & Paginasi
    // ──────────────────────────────────────
    public function index(): string
    {
        $keyword = $this->request->getGet('q') ?? '';
        $perPage = 10;
        $buku = $this->bukuModel->getBukuPaginate($perPage, $keyword);
        $pager = $this->bukuModel->pager;
        $data = [
            'title' => 'Daftar Buku',
            'buku' => $buku,
            'pager' => $pager,
            'keyword' => $keyword,
            'total' => $this->bukuModel->countAllResults(false),
        ];
        return view('buku/index', $data);
    }
    // ──────────────────────────────────────
    // READ - Statistik buku
    // ──────────────────────────────────────
    public function statistik(): string
    {
        $statistik = $this->bukuModel->getStatistikData();
        $statistik['average_stok'] = $statistik['total'] > 0
            ? round($statistik['total_stok'] / $statistik['total'], 2)
            : 0;

        return view('buku/statistik', [
            'title' => 'Statistik Buku',
            'statistik' => $statistik,
        ]);
    }
    // ──────────────────────────────────────
    // READ - Detail satu buku
    // ──────────────────────────────────────
    public function detail(int $id): string
    {
        $buku = $this->bukuModel
            ->select('buku.*, kategori.nama AS nama_kategori')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->find($id);
        if (!$buku) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku tidak
ditemukan');
        }
        return view('buku/detail', ['title' => 'Detail Buku', 'buku' => $buku]);
    }
    // ──────────────────────────────────────
    // CREATE - Form tambah
    // ──────────────────────────────────────
    public function tambah(): string
    {
        return view('buku/form', [
            'title' => 'Tambah Buku',
            'buku' => null,
            'kategori' => $this->kategoriModel->getDropdown(),
        ]);
    }
    // ──────────────────────────────────────
    // CREATE - Proses simpan
    // ──────────────────────────────────────
    public function simpan()
    {
        $rules = [
            'kode_buku' => [
                'label' => 'Kode Buku',
                'rules' =>
                'required|min_length[3]|max_length[20]|alpha_numeric|is_unique[buku.kode_buku]',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                    'alpha_numeric' => '{field} hanya boleh berisi huruf dan angka.',
                    'is_unique' => 'Kode "{value}" sudah digunakan buku lain.',
                ],
            ],
            'judul' => [
                'label' => 'Judul Buku',
                'rules' => 'required|min_length[2]|max_length[200]',
            ],
            'penulis' => [
                'label' => 'Penulis',
                'rules' => 'required|min_length[2]|max_length[150]',
            ],
            'tahun' => [
                'label' => 'Tahun Terbit',
                'rules' => 'permit_empty|integer|greater_than[1499]|less_than[2100]',
                'errors' => [
                    'greater_than' => '{field} tidak boleh sebelum tahun 1500.',
                    'less_than' => '{field} tidak boleh lebih dari tahun 2099.',
                ],
            ],
            'stok' => [
                'label' => 'Stok',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'greater_than_equal_to' => '{field} tidak boleh bernilai negatif.',
                ],
            ],
            'isbn' => [
                'label' => 'ISBN',
                'rules' => 'permit_empty|min_length[10]|max_length[20]',
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        $data = $this->ambilDataForm();
        $this->bukuModel->insert($data);
        session()->setFlashdata('sukses', "Buku '{$data['judul']}' berhasil ditambahkan.");
        return redirect()->to('/buku');
    }

    // ──────────────────────────────────────
    // UPDATE - Form edit
    // ──────────────────────────────────────
    public function edit(int $id): string
    {
        $buku = $this->bukuModel->find($id);
        if (!$buku) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Buku tidak
ditemukan');
        }
        return view('buku/form', [
            'title' => 'Edit Buku: ' . $buku['judul'],
            'buku' => $buku,
            'kategori' => $this->kategoriModel->getDropdown(),
        ]);
    }
    // ──────────────────────────────────────
    // UPDATE - Proses update
    // ──────────────────────────────────────
    public function update(int $id)
    {
        $data = $this->ambilDataForm();
        // Validasi kode unik, kecuali buku yang sedang diedit
        if ($this->bukuModel->isKodeTaken($data['kode_buku'], $id)) {
            session()->setFlashdata('error', 'Kode buku sudah digunakan buku lain.');
            return redirect()->back()->withInput();
        }
        $this->bukuModel->update($id, $data);
        session()->setFlashdata('sukses', "Buku '{$data['judul']}' berhasil
diperbarui.");
        return redirect()->to('/buku');
    }
    // ──────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────
    public function hapus(int $id)
    {
        $buku = $this->bukuModel->find($id);
        if (!$buku) {
            session()->setFlashdata('error', 'Buku tidak ditemukan.');
            return redirect()->to('/buku');
        }
        $this->bukuModel->delete($id);
        session()->setFlashdata('sukses', "Buku '{$buku['judul']}' berhasil
dihapus.");
        return redirect()->to('/buku');
    }
    // ──────────────────────────────────────
    // EKSPOR CSV
    // ──────────────────────────────────────
    public function ekspor()
    {
        $buku = $this->bukuModel->getBukuDenganKategori();
        $tanggal = date('Y-m-d');
        $filename = "buku-export-{$tanggal}.csv";

        // Header CSV
        $header = ['No', 'Kode', 'Judul', 'Penulis', 'Penerbit', 'Tahun', 'Stok', 'Kategori'];

        // Mulai output CSV
        $output = fopen('php://temp', 'w');
        fputcsv($output, $header);

        foreach ($buku as $i => $b) {
            $row = [
                $i + 1,
                $b['kode_buku'],
                $b['judul'],
                $b['penulis'],
                $b['penerbit'] ?? '',
                $b['tahun'] ?? '',
                $b['stok'],
                $b['nama_kategori'] ?? ''
            ];
            fputcsv($output, $row);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $this->response
            ->setContentType('text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }
    // ──────────────────────────────────────
    // PRIVATE HELPER - Kumpulkan data dari form
    // ──────────────────────────────────────
    private function ambilDataForm(): array
    {
        return [
            'kode_buku' => strtoupper($this->request->getPost('kode_buku')),
            'judul' => $this->request->getPost('judul'),
            'penulis' => $this->request->getPost('penulis'),
            'penerbit' => $this->request->getPost('penerbit'),
            'tahun' => $this->request->getPost('tahun') ?: null,
            'isbn' => $this->request->getPost('isbn'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'stok' => (int) $this->request->getPost('stok'),
            'kategori_id' => $this->request->getPost('kategori_id') ?: null,
        ];
    }
}
