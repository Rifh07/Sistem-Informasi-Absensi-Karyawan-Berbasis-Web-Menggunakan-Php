        <?php 
            $title->setTitle("Absensi Detail Bulanan");
            require ("views/HandF/header.php");
            if ($sessposisi == "Admin") :
              if (!isset($_GET['id'])):
                  header('location: '.BaseUrl.'/absensi/views') ;
              endif;
                $id = $_GET['id'];
            else :
                $id = $_SESSION['nip'];
            endif;
            $nama = $users->getNamaUsers($id);
            $data = $absensi->getTanggal();
        ?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Hari, Tanggal</th>
                        <th class="text-center">Masuk</th>
                        <th class="text-center">Keluar</th>
                        <th class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no=1; foreach ($data as $data): ?>
                    <tr>
                        <td class="text-center"><?php echo $no;?> </td>
                        <td><?php echo date('D, d M Y', strtotime($data)) ?></td>
                        <td class="text-center"><?php echo $absensi->getAbsenMasuk($data, $id)?></td>
                        <td class="text-center"><?php echo $absensi->getAbsenPulang($data, $id)?></td>
                        <td class="text-center">
                            <?php echo $absensi->getStatus($data, $id)?>
                        </td>
                    </tr>
                    <?php $no++; endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
      <?php require_once ("views/HandF/footer.php");?>