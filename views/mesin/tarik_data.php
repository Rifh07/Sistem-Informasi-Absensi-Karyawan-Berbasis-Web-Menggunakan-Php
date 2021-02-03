<?php 
    $title->setTitle('Tarik Data');
    require_once ("views/HandF/header.php");
    if ($sessposisi == "Admin" || $sessposisi == "Supervisor") :
    else :
        header('location: ../');
    endif;
    
    $mesins = $mesin->getMesin();
    $data = $mesin->getLog();
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
        $update = $mesin->tarikData();
        if ($update): $m = 1; $msg = 'Proses Berhasil';
        else: $m = 0; $msg = 'Proses Gagal';
        endif;
    endif;
?>
        <div class="container-fluid">
          <div class="container-fluid">
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-10">
                        <h5 class="m-0 align-middle font-weight-bold text-primary"><?php echo $title->getTitle();?></h6>
                    </div>
                    <div class="col-2">
                        <form action="" method="POST"><button class="btn btn-primary btn-sm" type="submit" aria-expanded="false"><i class="fa fa-download" aria-hidden="true"></i> Tarik Data</button></form>
                    </div>
                </div>
            </div>
                <div class="alert alert-info">
                    <table>
                        <?php  foreach ($mesins as $mesins):?>
                            <tr>
                                <td style="width: 120px; font-weight: bold;">IP</td>
                                <td>: <?php  echo $mesins['ip']?></td>
                            </tr>
                            <tr>
                                <td style="width: 120px; font-weight: bold;">Nama Mesin</td>
                                <td>: <?php  echo $mesins['nama_mesin']?></td>
                            </tr>
                            <tr>
                                <td style="width: 120px; font-weight: bold;">Terakhir Ditarik</td>
                                <td>
                                    : <?php if ($mesin->getLast()) :  echo date('D, d/M/y H:i',strtotime($mesin->getLast())); else : echo "Tidak Ada"; endif;?>
                                </td>
                            </tr>
                        <?php  endforeach; ?>
                    </table>
                </div>
            <div class="card-body">
                <?php if (isset($m)):
                        if ($m == 1) : echo '<div class="alert alert-success">';
                        elseif ($m == 0) : echo '<div class="alert alert-danger">';
                        endif;
                        echo "<b>$msg</b>
                        </div>";
                    endif;
                ?>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th>Hari, Tanggal</th>
                        <th>IP</th>
                        <th>Oleh</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if ($data): foreach ($data as $data):?>
                    <tr>
                        <td><?php  echo date('D, d/M/y H:i',strtotime($data['tgl']))?></td>
                        <td><?php  echo $data['ip']?></td>
                        <td><?php  echo $users->getNamaUsers($data['username'])?></td>
                        <td class="center"><?php  echo $data['status']?></td>
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