        <?php 
            $title->setTitle('Daftar Karyawan');
            require_once ("views/HandF/header.php");
            if ($sessposisi != "Admin"):
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
                <?php if (isset($_GET['m'])):
                        echo '<div class="alert alert-success">
                                <b>User Berhasil dihapus</b>
                            </div>';
                    endif;
                ?>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                            <div class="dropdown-menu animated--fade-in" aria-labelledby="aksi">
                                <a class="dropdown-item" href="<?php echo BaseUrl ?>/karyawan/edit?id=<?php echo $data['username']?>">Edit Karyawan</a>
                                <li class="divider"></li>
                                <a class="dropdown-item" href="<?php echo BaseUrl ?>/karyawan/delete?id=<?php echo $data['username']?>">Hapus Karyawan</a>
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