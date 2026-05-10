<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── Halaman Utama ──
$routes->get('/', 'Beranda::index');
$routes->get('tentang', 'Beranda::tentang');

// Route controller Demo
$routes->get('demo', 'Demo::index');
$routes->get('profil', 'Profil::index');
