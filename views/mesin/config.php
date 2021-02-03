        <?php 
            $title->setTitle('Konfigurasi Mesin');
            require_once ("views/HandF/header.php");

            if ($sessposisi != "Admin"):
                header('location: ../');
            endif;

            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                $update = $mesin->setMesin($_POST['ip'], $_POST['mesin'], $_POST['key']);
                if ($update): $m = 1; $msg = 'Berhasil Diupdate';
                else: $m = 0; $msg = 'Gagal Diupdate';
                endif;
            endif;
            $mesins = $mesin->getMesin();
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
                    foreach ($mesins as $mesins):
                ?>
                <form class="user" action="" method="POST">
                    <div class="form-group">
                        <p style="display:inline;"> IP</p>
                        <input type="text" name="ip" class="form-control" placeholder="Masukan IP" value="<?php echo $mesins['ip'] ?>" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Nama dan Tipe Mesin</p>
                        <input type="text" name="mesin" class="form-control" placeholder="Masukan Nama dan Tipe Mesin"  value="<?php echo $mesins['nama_mesin']?>" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Key</p>
                        <input type="password" name="key" class="form-control" placeholder="Masukan Key" value="<?php echo $mesins['kunci']?>" require>
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