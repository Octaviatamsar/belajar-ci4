<?php

namespace App\Controllers;

class Galeri extends BaseController
{
    public function index(): string
    {
        $kategori = $this->request->getGet('kategori');

        $gambar = [
            [
                'judul' => 'Air Terjun Grojogan Sewu',
                'url_gambar' => 'https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Pemandangan air terjun yang menakjubkan dengan aliran air yang deras dan lingkungan hijau yang asri, cocok untuk relaksasi dan fotografi alam.',
                'kategori' => 'alam',
            ],
            [
                'judul' => 'Jembatan Modern di Kota',
                'url_gambar' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Siluet jembatan modern saat matahari terbenam yang menampilkan arsitektur perkotaan dan pencahayaan spektakuler.',
                'kategori' => 'arsitektur',
            ],
            [
                'judul' => 'Hutan Pinus di Pagi Hari',
                'url_gambar' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Kabut tipis yang menyelimuti deretan pohon pinus menciptakan suasana damai di tengah suasana alam.',
                'kategori' => 'alam',
            ],
            [
                'judul' => 'Galeri Seni Kontemporer',
                'url_gambar' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Ruang pameran seni dengan lukisan dan instalasi modern yang menantang imajinasi dan kreativitas pengunjung.',
                'kategori' => 'seni',
            ],
            [
                'judul' => 'Gedung Perkantoran Futuristik',
                'url_gambar' => 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Bangunan tinggi dengan fasad kaca mencerminkan lanskap kota dan teknologi pembangunan modern.',
                'kategori' => 'teknologi',
            ],
            [
                'judul' => 'Perpustakaan Kampus',
                'url_gambar' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Interior perpustakaan modern dengan rak buku yang rapi dan suasana belajar yang tenang.',
                'kategori' => 'pendidikan',
            ],
            [
                'judul' => 'Pasar Tradisional yang Ramai',
                'url_gambar' => 'https://images.unsplash.com/photo-1520909553688-7ae3f5f1f14f?auto=format&fit=crop&w=800&q=80',
                'deskripsi' => 'Suasana pasar tradisional yang penuh warna dengan penjual dan pembeli berinteraksi di bawah atap kios.',
                'kategori' => 'urban',
            ],
        ];

        $kategoriList = array_unique(array_column($gambar, 'kategori'));
        sort($kategoriList);

        $gambarFilter = array_filter($gambar, function ($item) use ($kategori) {
            return empty($kategori) || $item['kategori'] === $kategori;
        });

        $data = [
            'title' => 'Halaman Galeri',
            'gambar' => $gambarFilter,
            'kategori' => $kategori,
            'kategoriList' => $kategoriList,
        ];

        return view('galeri/index', $data);
    }
}
