<?php

namespace App\Controllers;

class Akademik extends BaseController
{
    public function index()
    {
        echo "<h1>Sistem Informasi Akademik</h1>";
        echo "<p>Nama Mahasiswa: Nama Mahasiswa</p>";
    }

    public function matkul()
    {
        echo "<h2>Daftar Mata Kuliah</h2>";
        echo "<ul>";
        echo "<li>Algoritma dan Pemrograman</li>";
        echo "<li>Struktur Data</li>";
        echo "<li>Basis Data</li>";
        echo "<li>Jaringan Komputer</li>";
        echo "<li>Sistem Operasi</li>";
        echo "</ul>";
    }

    public function nilai($nim)
    {
        echo "Nilai mahasiswa dengan NIM: " . $nim;
    }

    public function testAlpha($param)
    {
        echo "Parameter alpha: " . $param;
    }

    public function testAlphanum($param)
    {
        echo "Parameter alphanum: " . $param;
    }

    public function apiStatus()
    {
        echo "Status API: OK";
    }

    public function apiVersion()
    {
        echo "Versi API: 1.0";
    }
}
