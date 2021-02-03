<?php 
    $title->setTitle('Daftar Karyawan');
    require_once ("views/HandF/header.php");
    if ($sessposisi == "Admin" || $sessposisi == "Supervisor"):
    else :
      header('location: ../');
    endif;
    $data = $users->vUsers();
?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Posisi</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if ($data): foreach ($data as $data):?>
                    <tr>
                        <td><?php echo $data['username']?></td>
                        <td><?php echo $data['nama']?></td>
                        <td><?php echo $data['posisi']?></td>
                        <td>
                            <button class="btn btn-primary dropdown-toggle" type="button" id="aksi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               Aksi
                            </button>
                            <div class="dropdown-menu animated--fade-in"
                                aria-labelledby="aksi">
                                <a class="dropdown-item" href="<?php echo BaseUrl ?>/absensi/bulan/views?id=<?php echo $data['username']?>">Lihat Absensi Bulan Ini </a>
                                <a class="dropdown-item" href="<?php echo BaseUrl ?>/absensi/riwayat/views?id=<?php echo $data['username']?>">Lihat Riwayat Absensi</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
      <?php require_once ("views/HandF/footer.php");?>