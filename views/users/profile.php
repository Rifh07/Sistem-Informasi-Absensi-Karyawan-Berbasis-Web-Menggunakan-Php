<?php 
            $title->setTitle('Profil');
            require ("views/HandF/header.php");
            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                $update = $users->changePassword($_POST['pass1'], $_POST['pass2'], $_POST['pass3']);
                if ($update): 
                    $m = 1; $msg = "Password Berhasil Diubah";
                else: $m = 0; $msg = "Password Gagal Diubah";
                endif;
            endif;
            $user = $users->getUsers($_SESSION['nip']);
        ?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="row justify-content-center">
            <div class="col-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
                        </div>
                        <div class="card-body">
                            <?php foreach ($user as $user):?>
                                <div class="form-group">
                                    <p style="display:inline;"> Nama</p>
                                    <input type="text" class="form-control" value="<?php echo $user['nama']?>" placeholder="Masukan Nama Lengkap" readonly>
                                </div>
                                <div class="form-group">
                                    <p style="display:inline;">ID Karyawan</p>
                                    <input type="text" class="form-control" value="<?php echo $user['username']?>"  placeholder="Masukan ID Karyawan" readonly>
                                </div>
                                <div class="form-group">
                                    <p style="display:inline;"> Posisi</p>
                                    <input type="text" class="form-control" value="<?php echo $user['posisi']?>" placeholder="Masukan Posisi" readonly>
                                </div>
                                <div class="form-group">
                                    <p style="display:inline;"> Tanggal Lahir</p>
                                    <input type="date" class="form-control" value="<?php echo $user['ttl']?>" placeholder="Masukan Tanggal Lahir" readonly>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 align-middle font-weight-bold text-primary">Ubah Password</h6>
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
                            ?>
                            <form action="" method="POST">
                                <div class="form-group">
                                    <p style="display:inline;"> Password Lama</p>
                                    <input type="password" name="pass1" class="form-control" placeholder="Masukan Password Lama" require>
                                </div>
                                <div class="form-group">
                                    <p style="display:inline;">Password Baru</p>
                                    <input type="password" name="pass2" class="form-control" placeholder="Masukan Password Baru" require>
                                </div>
                                <div class="form-group">
                                    <p style="display:inline;">Password Baru (Ulangi)</p>
                                    <input type="password" name="pass3" class="form-control" placeholder="Masukan Password Baru (Ulangi)" require>
                                </div>
                                <button class="btn btn-primary btn-user btn-block" type="submit"><i class="fa fa-save"></i> Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
      <?php require_once ("views/HandF/footer.php");?>