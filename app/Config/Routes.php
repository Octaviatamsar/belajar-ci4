<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── Halaman Utama ──
$routes->get('/', 'Beranda::index');
$routes->get('tentang', 'Beranda::tentang');

// Route controller Demo
$routes->get('demo', 'Demo::index');
$routes->get('profil', 'Profil::index');
$routes->get('galeri', 'Galeri::index');

// Route CRUD Buku
$routes->get('buku', 'Buku::index');
$routes->get('buku/statistik', 'Buku::statistik');
$routes->get('buku/tambah', 'Buku::tambah');
$routes->post('buku/simpan', 'Buku::simpan');
$routes->get('buku/detail/(:num)', 'Buku::detail/$1');
$routes->get('buku/edit/(:num)', 'Buku::edit/$1');
$routes->post('buku/update/(:num)', 'Buku::update/$1');
$routes->get('buku/hapus/(:num)', 'Buku::hapus/$1');
$routes->get('buku/ekspor', 'Buku::ekspor');

// Route CRUD Kategori
$routes->get('kategori', 'Kategori::index');
$routes->get('kategori/tambah', 'Kategori::tambah');
$routes->post('kategori/simpan', 'Kategori::simpan');
$routes->get('kategori/edit/(:num)', 'Kategori::edit/$1');
$routes->post('kategori/update/(:num)', 'Kategori::update/$1');
$routes->get('kategori/hapus/(:num)', 'Kategori::hapus/$1');


// ═══════════════════════════════════════════
// AUTH ROUTES — tidak butuh login
// ═══════════════════════════════════════════
$routes->get('login', 'Auth::login');
$routes->post('login/proses', 'Auth::prosesLogin');
$routes->get('register', 'Auth::register');
$routes->post('register/proses', 'Auth::prosesRegister');
$routes->get('logout', 'Auth::logout');

// ═══════════════════════════════════════════
// ROUTES YANG MEMBUTUHKAN LOGIN
// ═══════════════════════════════════════════
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // Buku - READ boleh semua yang sudah login
    $routes->get('buku', 'Buku::index');
    $routes->get('buku/detail/(:num)', 'Buku::detail/$1');

    // Buku - WRITE hanya admin dan petugas
    $routes->group('buku', ['filter' => 'role'], function ($routes) {
        $routes->get('tambah', 'Buku::tambah');
        $routes->post('simpan', 'Buku::simpan');
        $routes->get('edit/(:num)', 'Buku::edit/$1');
        $routes->post('update/(:num)', 'Buku::update/$1');
        $routes->get('hapus/(:num)', 'Buku::hapus/$1');
    });
    
    // Kategori - hanya admin dan petugas
    $routes->group('kategori', ['filter' => 'role'], function ($routes) {
        $routes->get('/', 'Kategori::index');
        $routes->get('tambah', 'Kategori::tambah');
        $routes->post('simpan', 'Kategori::simpan');
        $routes->get('edit/(:num)', 'Kategori::edit/$1');
        $routes->post('update/(:num)', 'Kategori::update/$1');
        $routes->get('hapus/(:num)', 'Kategori::hapus/$1');
    });

    // Area admin - hanya role admin
    $routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('/', 'Admin\Dashboard::index');
        $routes->get('pengguna', 'Admin\Pengguna::index');
    });

    // Akun - ganti password untuk pengguna yang sudah login
    $routes->get('akun/ganti-password', 'Auth::gantiPassword');
    $routes->post('akun/proses-ganti-password', 'Auth::prosesGantiPassword');
});
