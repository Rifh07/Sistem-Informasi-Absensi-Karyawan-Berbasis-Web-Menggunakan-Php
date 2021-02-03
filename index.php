<?php
// Simple Routing Created by Syarif Hidayat

require ("config/config.php");
$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

$routes =[
    '' => 'views/home.php',
    'coba' => 'views/absensi/bulanan/coba.php',
    'login' => 'views/login.php',
    'users/logout' => 'views/users/logout.php',
    'users/profile' => 'views/users/profile.php',
    'users/change-password' => 'views/users/change-password.php',
    'absensi/pengajuan' => 'views/absensi/pengajuan/pengajuan.php',
    'absensi/pengajuan/views' => 'views/absensi/pengajuan/views.php',
    'absensi/pengajuan/riwayat' => 'views/absensi/pengajuan/riwayat.php',
    'absensi/views' => 'views/absensi/views.php',
    'absensi/print' => 'views/absensi/print.php',
    'absensi/bulan/views' => 'views/absensi/bulanan/views.php',
    'absensi/riwayat/views' => 'views/absensi/riwayat/views.php',
    'absensi/tarik-data' => 'views/mesin/tarik_data.php',
    'mesin/config' => 'views/mesin/config.php',
    'karyawan/views' => 'views/users/users.php',
    'karyawan/tambah' => 'views/users/tambah.php',
    'karyawan/edit' => 'views/users/edit.php',
    'karyawan/delete' => 'views/users/hapus.php',
    
];

if (array_key_exists($path, $routes)){
    require $routes[$path];
} else {
    // require "404.php";
}
