          <?php 
          require_once ("views/HandF/header.php");
          $karyawan = $users->vUsers();
          $total_karyawan = 0;
          foreach ($karyawan as $karyawan):
            $total_karyawan += 1;
          endforeach;
          $getUsername = $_SESSION['nip'];
          $bulan = $absensi->getBulan();
          foreach ($bulan as $bln):
            $bln = date("Y-m", strtotime($bln))."-01";
            $ket = $absensi->getKeterangan($bln, $getUsername);
            if ($_SESSION['posisi'] == "Admin") :
              $masuk = $ket[8];
            else:
              $masuk = $ket[0];
            endif;
            $hari = $ket[5];
            $masuk = $masuk/$hari*100;
            $hadir[] = $masuk;
          endforeach;
          ?>
          <div class="container-fluid">
            <div class="container-fluid">
              <div class="row">
                <div class="col-xl-6 col-md-6 mb-4">
                  <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                      <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Karyawan</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_karyawan ?></div>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 mb-4">
                  <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                      <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kehadiran Karyawan</div>
                          <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $absensi->getMasuk($total_karyawan) ?></div>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-7">
                  <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Persentase Kehadiran 5 Bulan Terakhir <?php if ($_SESSION['posisi'] == "Admin") : echo "Karyawan"; else : "Saya"; endif; ?></h6>
                    </div>
                    <div class="card-body">
                      <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <script src="<?php echo BaseUrl ?>/assets/vendor/chart.js/Chart.min.js"></script>
          <script  type="text/javascript">
            Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
            Chart.defaults.global.defaultFontColor = '#858796';

            function number_format(number, decimals, dec_point, thousands_sep) {
              number = (number + '').replace(',', '').replace(' ', '');
              var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                  var k = Math.pow(10, prec);
                  return '' + Math.round(n * k) / k;
                };
              s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
              if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
              }
              if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
              }
              return s.join(dec);
            }
            var ctx = document.getElementById("myAreaChart");
            var myLineChart = new Chart(ctx, {
              type: 'line',
              data: {
                labels: [<?php echo '"'.$bulan[0].'", "'.$bulan[1].'", "'.$bulan[2].'", "'.$bulan[3].'", "'.$bulan[4].'"' ?>],
                datasets: [{
                  label: "Persentase Hadir",
                  lineTension: 0.3,
                  backgroundColor: "rgba(78, 115, 223, 0.05)",
                  borderColor: "rgba(78, 115, 223, 1)",
                  pointRadius: 3,
                  pointBackgroundColor: "rgba(78, 115, 223, 1)",
                  pointBorderColor: "rgba(78, 115, 223, 1)",
                  pointHoverRadius: 3,
                  pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                  pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                  pointHitRadius: 10,
                  pointBorderWidth: 2,
                  data: [<?php echo $hadir[0].', '.$hadir[1].', '.$hadir[2].', '.$hadir[3].', '.$hadir[4] ?>],
                }],
              },
              options: {
                maintainAspectRatio: false,
                layout: {
                  padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                  }
                },
                scales: {
                  xAxes: [{
                    time: {
                      unit: 'date'
                    },
                    gridLines: {
                      display: false,
                      drawBorder: false
                    },
                    ticks: {
                      maxTicksLimit: 7
                    }
                  }],
                  yAxes: [{
                    ticks: {
                      maxTicksLimit: 5,
                      padding: 10,
                      callback: function(value, index, values) {
                        return  value + '%';
                      }
                    },
                    gridLines: {
                      color: "rgb(234, 236, 244)",
                      zeroLineColor: "rgb(234, 236, 244)",
                      drawBorder: false,
                      borderDash: [2],
                      zeroLineBorderDash: [2]
                    }
                  }],
                },
                legend: {
                  display: false
                },
                tooltips: {
                  backgroundColor: "rgb(255,255,255)",
                  bodyFontColor: "#858796",
                  titleMarginBottom: 10,
                  titleFontColor: '#6e707e',
                  titleFontSize: 14,
                  borderColor: '#dddfeb',
                  borderWidth: 1,
                  xPadding: 15,
                  yPadding: 15,
                  displayColors: false,
                  intersect: false,
                  mode: 'index',
                  caretPadding: 10,
                  callbacks: {
                    label: function(tooltipItem, chart) {
                      var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                      return datasetLabel + ': '+ number_format(tooltipItem.yLabel)+'%';
                    }
                  }
                }
              }
            });
          </script>
<?php require_once ("views/HandF/footer.php");?>