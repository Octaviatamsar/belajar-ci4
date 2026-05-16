<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = true;
    protected $allowedFields = [
        'kode_buku',
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'isbn',
        'deskripsi',
        'stok',
        'kategori_id'
    ];
    /**
     * Ambil semua buku beserta nama kategorinya (JOIN)
     */
    public function getBukuDenganKategori(): array
    {
        return $this
            ->select('buku.*, kategori.nama AS nama_kategori')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->orderBy('buku.judul', 'ASC')
            ->findAll();
    }
    /**
     * Cari buku berdasarkan keyword di judul atau penulis
     */
    public function cari(string $keyword): array
    {
        return $this
            ->select('buku.*, kategori.nama AS nama_kategori')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->groupStart()
            ->like('buku.judul', $keyword)
            ->orLike('buku.penulis', $keyword)
            ->orLike('buku.penerbit', $keyword)
            ->groupEnd()
            ->orderBy('buku.judul', 'ASC')
            ->findAll();
    }
    /**
     * Ambil buku dengan paginasi dan JOIN kategori
     */
    public function getBukuPaginate(int $perPage = 10, string $keyword = '')
    {
        $this->select('buku.*, kategori.nama AS nama_kategori')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->orderBy('buku.judul', 'ASC');
        if (!empty($keyword)) {
            $this->groupStart()
                ->like('buku.judul', $keyword)
                ->orLike('buku.penulis', $keyword)
                ->groupEnd();
        }
        return $this->paginate($perPage);
    }
    /**
     * Cek apakah kode buku sudah ada (untuk validasi unik saat edit)
     */
    public function isKodeTaken(string $kode, int $excludeId = 0): bool
    {
        $qb = $this->where('kode_buku', $kode);
        if ($excludeId > 0) {
            $qb->where('id !=', $excludeId);
        }
        return $qb->countAllResults() > 0;
    }
    /**
     * Ambil statistik buku
     */
    public function getStatistikData(): array
    {
        $db = \Config\Database::connect();
        $total = $this->countAll();
        $totalStok = (int) $db->table('buku')->selectSum('stok')->get()->getRow()->stok;
        $perKategori = $db->table('buku')
            ->select('kategori.nama, COUNT(buku.id) AS jumlah, SUM(buku.stok) AS stok')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->groupBy('buku.kategori_id')
            ->orderBy('jumlah', 'DESC')
            ->get()->getResultArray();
        $topStok = $this->select('buku.*, kategori.nama AS nama_kategori')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->orderBy('stok', 'DESC')
            ->orderBy('judul', 'ASC')
            ->findAll(5);
        $stokKosong = $this->select('buku.*, kategori.nama AS nama_kategori')
            ->join('kategori', 'kategori.id = buku.kategori_id', 'left')
            ->where('stok', 0)
            ->orderBy('judul', 'ASC')
            ->findAll();

        return [
            'total' => $total,
            'total_stok' => $totalStok,
            'per_kategori' => $perKategori,
            'top_stok' => $topStok,
            'stok_kosong' => $stokKosong,
        ];
    }
}
