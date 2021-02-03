<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start(); ob_start();
    if (!isset($_SESSION['nip'])):
        header("location: /login");
    elseif (isset($_SESSION['nip'])):
        $sessnama = $users->getNamaUsers($_SESSION['nip']);
        $sessposisi = $_SESSION['posisi'];
        if (!$sessnama):
            header("location: ../users/logout");
        endif;
    endif;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Website ini adalah website absensi">
  <meta name="author" content="Syarif Hidayat">
  <title><?php echo $title->getTitle();?></title>
  <link href="<?php echo BaseUrl?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?php echo BaseUrl?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?php echo BaseUrl?>/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="./">
        <div class="sidebar-brand-icon">
          <div class="sidebar-brand-text mx-3"> hcos</div>
        </div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo BaseUrl?>/">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Absensi" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-clipboard-list"></i>
          <span>Absensi</span>
        </a>
        <div id="Absensi" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
          <?php if ($sessposisi == "Admin" || $sessposisi == "Supervisor") : ?>
              <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/tarik-data">Tarik Data</a>
              <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/views">Absensi</a>
              <?php if ($sessposisi == "Supervisor") :?>
                <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/pengajuan/views">Pengajuan</a>
              <?php elseif ($sessposisi == "Admin") : ?>
                <a class="collapse-item" href="<?php echo BaseUrl?>/mesin/config">Konfigurasi Mesin</a>
              <?php endif; ?>
            <?php else : ?>
              <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/bulan/views">Absensi Bulan Ini</a>
              <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/riwayat/views">Riwayat Absensi</a>
              <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/pengajuan">Pengajuan</a>
              <a class="collapse-item" href="<?php echo BaseUrl?>/absensi/pengajuan/riwayat">Riwayat Pengajuan</a>
            <?php endif; ?>
          </div>
        </div>
      </li>
      <?php if ($sessposisi == "Admin") : ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Karyawan" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-users"></i>
            <span>Karyawan</span>
          </a>
          <div id="Karyawan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="<?php echo BaseUrl?>/karyawan/views">Lihat Karyawan</a>
              <a class="collapse-item" href="<?php echo BaseUrl?>/karyawan/tambah">Tambah Karyawan</a>
            </div>
          </div>
        </li>
      <?php endif; ?>
      <hr class="sidebar-divider d-none d-md-block">
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user"></i>
                <div class="topbar-divider d-none d-sm-block"></div>
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo "$sessnama ($sessposisi)"?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php echo BaseUrl?>/users/profile">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profil
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo BaseUrl ?>/users/logout">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>