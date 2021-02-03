        <?php 
            $title->setTitle("Riwayat Absensi");
            require ("views/HandF/header.php");
            if ($sessposisi == "Admin") :
                if (!isset($_GET['id'])):
                    header('location: '.BaseUrl.'/absensi/views') ;
                endif;
                $id = $_GET['id'];
            else :
                $id = $_SESSION['nip'];
            endif;
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $date = $_POST['tahun'].'/'.$_POST['periode'].'/01';
                $d = $_POST['tahun'].'-'.$_POST['periode'].'-01';
                $ket = $absensi->getKeterangan($date, $id);
            }
            $data = $users->getUsers($id);
        ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">


          <!-- Content Row -->
          <div class="container-fluid">

            <!-- Earnings (Monthly) Card Example -->
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row py-3">
                        <div class="col-md-2">
                            <select name="periode" class="form-control" require>
                                <option selected="selected" disabled>Periode</option>
                                <option value="01">Des - Jan</option>
                                <option value="02">Jan - Feb</option>
                                <option value="03">Feb - Mar</option>
                                <option value="04">Mar - Apr</option>
                                <option value="05">Apr - Mei</option>
                                <option value="06">Mei - Jun</option>
                                <option value="07">Jun - Jul</option>
                                <option value="08">Jul - Agu</option>
                                <option value="09">Agu - Sep</option>
                                <option value="10">Sep - Okt</option>
                                <option value="11">Okt - Nov</option>
                                <option value="12">Nov - Des</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="tahun" class="form-control" require>
                                <option selected="selected" disabled>Tahun</option>
                                <?php
                                    for($i=date('Y'); $i>=date('Y')-32; $i--){
                                        echo"<option value='$i'> $i </option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
              <div class="table-responsive">
                <table class="table" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th class="align-middle" rowspan="2">NIP</th>
                        <th class="align-middle" rowspan="2">Nama</th>
                        <th class="align-middle" rowspan="2">Posisi</th>
                        <th class="text-center" colspan="6">Keterangan</th>
                        <th class="align-middle text-center" rowspan="2">Aksi</th>
                    </tr>
                    <tr>
                        <th class="text-center">K</th>
                        <th class="text-center">A</th>
                        <th class="text-center">C</th>
                        <th class="text-center">S</th>
                        <th class="text-center">T</th>
                        <th class="text-center">L</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($ket)) : foreach ($data as $data): ?>
                        <tr>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['posisi']; ?></td>
                            <td class="text-center"><?php echo $ket[0]; ?></td>
                            <td class="text-center"><?php echo $ket[1]; ?></td>
                            <td class="text-center"><?php echo $ket[2]; ?></td>
                            <td class="text-center"><?php echo $ket[3]; ?></td>
                            <td class="text-center"><?php echo $ket[4]; ?></td>
                            <td class="text-center"><?php echo $ket[6]; ?></td>
                            <td class="text-center"><a class="btn btn-primary" href="<?php echo BaseUrl ?>/absensi/print?id=<?php echo $data['username']; ?>&periode=<?php echo $d?>">Print</a></td>
                        </tr>
                    <?php endforeach;  endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
    </div>

        
            <!-- Pending Requests Card Example -->
          
      <!-- End of Main Content -->
      <?php require_once ("views/HandF/footer.php");?>