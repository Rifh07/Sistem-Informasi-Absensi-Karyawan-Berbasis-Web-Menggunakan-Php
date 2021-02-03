        <?php 
            $title->setTitle('Form Pengajuan');
            require ("views/HandF/header.php");

            if ($sessposisi == "Admin" || $sessposisi == "Supervisor") :
                header('location: '.BaseUrl.'/absensi/pengajuan/views') ;
            else :
                $id = $_SESSION['nip'];
            endif;

            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                $update = $absensi->Pengajuan($_POST['Pengajuan'], $_POST['Dari'], $_POST['Sampai'], $_POST['Ket']);
                if ($update): 
                    $m = 1; $msg = "Berhasil Membuat Pengajuan ".$_POST['Pengajuan'];
                else: $m = 0; $msg = "Gagal Membuat Pengajuan ".$_POST['Pengajuan'];
                endif;
            endif;

            $username = $_SESSION['nip'];
            $nama = $users->getNamaUsers($username);
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
                ?>
                <form class="user" action="" method="POST">
                    <div class="form-group">
                        <p style="display:inline;"> Nama</p>
                        <input type="text" class="form-control" value="<?php echo $nama ?>" placeholder="Masukan Nama Lengkap" readonly require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;">ID Karyawan</p>
                        <input type="text" class="form-control" value="<?php echo $username ?>" placeholder="Masukan ID Karyawan" readonly require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Pengajuan</p>
                        <select name="Pengajuan" class="form-control" require>
                            <option selected="selected">Pilih Pengajuan</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Cuti">Cuti</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Dari Tanggal</p>
                        <input type="date" name="Dari" class="form-control" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;"> Sampai Tanggal</p>
                        <input type="date" name="Sampai" class="form-control" require>
                    </div>
                    <div class="form-group">
                        <p style="display:inline;">Keterangan</p>
                        <textarea name="Ket" class="form-control" placeholder="Masukan Keterangan" require></textarea>
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