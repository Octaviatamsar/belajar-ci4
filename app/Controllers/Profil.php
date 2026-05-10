<?php

namespace App\Controllers;

class Profil extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Halaman Profil',
            'npm' => '2110020068',
            'nama' => 'Mulana Octavia Tamsar',
            'prodi' => 'Teknik Informatika',
            'angkatan' => '2022',
            'ipk' => 3.75,
            'mata_kuliah' => [
                'Pemrograman Web Lanjut',
                'Basis Data Lanjut',
                'Jaringan Komputer',
                'Sistem Informasi',
                'Kecerdasan Buatan',
            ],
        ];

        return view('profil/index', $data);
    }
}
