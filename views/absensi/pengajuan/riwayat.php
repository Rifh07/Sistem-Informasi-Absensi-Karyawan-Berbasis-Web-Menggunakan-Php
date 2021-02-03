<?php 
            $title->setTitle("Riwayat Pengajuan");
            require_once ("views/HandF/header.php");
            $nip = $_SESSION['nip'];
            $data = $absensi->getPengajuan($nip);
        ?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
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
              <div class="table-responsive">
                <table class="table" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Pengajuan</th>
                        <th>Tanggal Diajukan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($data)) : $no=1; foreach ($data as $data): ?>
                    <tr>
                        <td class="text-center"><?php echo $no;?> </td>
                        <td><?php echo $data['username']?></td>
                        <td><?php echo $users->getNamaUsers($data['username'])?></td>
                        <td><?php echo $data['status']?></td>
                        <td><?php echo $data['tgl']?></td>
                        <td><?php echo $data['create_at']?></td>
                        <td><?php echo $data['keterangan']?></td>
                        <td><?php if ($data['approve'] == NULL): echo "Sedang Diproses"; else: echo $data['approve']; endif;?></td>
                    </tr>
                    <?php $no++; endforeach; endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
      <?php require_once ("views/HandF/footer.php");?>