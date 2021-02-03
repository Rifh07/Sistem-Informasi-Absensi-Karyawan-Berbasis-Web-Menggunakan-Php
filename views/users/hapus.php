        <?php 
            $title->setTitle('Hapus Karyawan');
            require ("views/HandF/header.php");
            if (!isset($_GET['id'])):
                header("location: views");
            elseif ($sessposisi != "Admin"):
                header('location: ../');
            endif;
            
            $nama = $users->getNamaUsers($_GET['id']);

            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                $delete = $users->hapusUser($_GET['id']);
                if ($delete): header('location: '.BaseUrl.'/karyawan/views?m=success');
                else: $msg = 'Gagal Dihapus';
                endif;
            endif;
        ?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
            </div>
                <?php if (isset($m)):
                        echo "<div class='alert alert-danger'>
                                <b>$msg</b>
                            </div>";
                        endif;
                ?>
            <div class="card-body">
                <div class="alert alert-danger">
                    Yakin ingin menghapus <b><?php echo $nama?></b> dari database?
                </div>  
                <div class="row text-center">
                    <div class="col-md-6">
                        <form action="" method="POST"><button class="btn btn-danger" type="submit">Hapus</button></form>
                    </div>
                    <div class="col-md-6">
                        <a href="views" class="btn btn-primary">Batal</a>
                    </div>
                </div> 
            </div>
          </div>
        </div>
    </div>
      <?php require_once ("views/HandF/footer.php");?>