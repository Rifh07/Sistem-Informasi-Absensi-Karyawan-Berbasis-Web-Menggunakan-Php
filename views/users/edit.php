        <?php 
            $title->setTitle('Edit Karyawan');
            require ("views/HandF/header.php");
            if (!isset($_GET['id'])):
                header("location: views");
            elseif ($sessposisi != "Admin"):
                header('location: ../');
            endif;
            
            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                $update = $users->editUsers($_POST['Nama'], $_POST['Id'], $_POST['Posisi'], $_POST['Lahir']);
                if ($update): $m = 1; $msg = 'Berhasil Diedit';
                else: $m = 0; $msg = 'Gagal Diedit';
                endif;
            endif;
            $user = $users->getUsers($_GET['id']);
        ?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="row justify-content-center">
            <div class="col-6">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
            </div>
            <div class="card-body">
                <?php 
                    if (isset($m)):
                        if ($m == 1) : echo '<div class="alert alert-success">' ;
                        elseif ($m == 0) : echo '<div class="alert alert-danger">';
                        endif;
                        echo "<b>$msg</b>
                        </div>";
                    endif;
                    foreach ($user as $user):
                ?>
                <form class="user" action="" method="POST">
                    <div class="form-group">
                        <p style="display:inline;"> Nama</p>
                        <input type="text" name="Nama" class="form-control" value="<?php echo $user['nama']?>" placeholder="Masukan Nama Lengkap" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;">ID Karyawan</p>
                        <input type="text" name="Id" class="form-control" value="<?php echo $user['username']?>"  placeholder="Masukan ID Karyawan" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Posisi</p>
                        <input type="text" name="Posisi" class="form-control" value="<?php echo $user['posisi']?>" placeholder="Masukan Posisi" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Tanggal Lahir</p>
                        <input type="date" name="Lahir" class="form-control" value="<?php echo $user['ttl']?>" placeholder="Masukan Tanggal Lahir" require>
                    </div>
                    <button class="btn btn-primary btn-user btn-block" type="submit"><i class="fa fa-save"></i> Simpan</button>
                </form>
                <?php endforeach ?>
            </div>
          </div>
        </div>
    </div>
    </div>
    </div>
      <?php require_once ("views/HandF/footer.php");?>