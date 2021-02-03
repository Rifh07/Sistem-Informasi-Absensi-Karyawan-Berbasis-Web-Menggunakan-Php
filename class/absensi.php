<?php 

class Absensi
{
    public $db;

    public function __construct() 
    {
        $this->db = new DB_Class();
    }

    public function getTanggal()
    {
        $tgl = date('d'); 

        // JIKA TANGGAL DIBULAN INI KURANG DARI 16
        if ($tgl < 16) :
            $today = date('Y-m-d');
            $date = date('Y-m-d', strtotime('-1 month', strtotime($today)));

            $bulan = date('m', strtotime($date)); $tahun = date('Y', strtotime($date));
            $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            for ($i=16; $i < $tanggal+1; $i++) { 
                $tgll[] = "$tahun-$bulan-$i";
            }

            $bulan = date('m'); $tahun = date('Y');
            $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $tggl = $tanggal-16;
            for ($i=1; $i < $tggl+1; $i++) { 
                $tgll[] = "$tahun-$bulan-$i";
            }
            return $tgll;


        // JIKA TANGGAL DIBULAN INI Lebih DARI 16
        else :
            $bulan = date('m'); $tahun = date('Y');
            $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $tggl = $tanggal;
            for ($i=16; $i < $tggl+1; $i++) { 
                $tgll[] = "$tahun-$bulan-$i";
            }

            $today = date('Y-m-d');
            $date = date('Y-m-d', strtotime('+1 month', strtotime($today)));

            $bulan = date('m', strtotime($date)); $tahun = date('Y', strtotime($date));
            $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            $tggl = $tanggal-16;
            for ($i=1; $i < $tggl+1; $i++) { 
                $tgll[] = "$tahun-$bulan-$i";
            }

            return $tgll;
        endif;
    }

    public function getAbsenMasuk($tgl, $nip)
    {
        // $tgl = date('d/m/Y', strtoTime($tgl));
        
        $result = $this->db->konek()->query("SELECT * FROM absensi WHERE username='$nip' AND date(tgl) ='$tgl' AND status='Masuk' ");
        $row = $result->num_rows;

        if ($row > 0) :
            $data = $result->fetch_assoc();
            $data = date('H:i:s', strtotime($data['tgl']));
            return $data;
        else :
            return  "-";
        endif;
    }
    public function getAbsenPulang($tgl, $nip)
    {
        $tgl = date('Y-m-d', strtotime('+1 day', strtotime($tgl)));
        
        $result = $this->db->konek()->query("SELECT * FROM absensi WHERE username='$nip' AND date(tgl) ='$tgl' AND status='Pulang' ");
        $row = $result->num_rows;

        if ($row > 0) :
            $data = $result->fetch_assoc();
            $data = date('H:i:s', strtotime($data['tgl']));
            return $data;
        else :
            return  "-";
        endif;
    }

    public function getStatus($tgl, $nip)
    {
        $getAbsenMasuk = $this->getAbsenMasuk($tgl, $nip);
        $getAbsenPulang = $this->getAbsenPulang($tgl, $nip);
        if ($getAbsenMasuk == '-' AND $getAbsenPulang == '-'):
            $result = $this->db->konek()->query("SELECT * FROM absensi_log WHERE username='$nip' AND date(tgl) ='$tgl'");
            $row = $result->num_rows;

            if ($row > 0) :
                $data = $result->fetch_assoc();
                return $data['status'];
            else :
                return "Tidak Hadir";
            endif;

        elseif ($getAbsenMasuk !== '-') :
            $jam = date_create(date('H:i:s', strtotime($getAbsenMasuk)));
            $masuk = date_create(date('H:i:s', strtotime('20:00:00')));
            if ($jam < $masuk) :
                return "Hadir";
            else :
                $terlambat = date_diff($masuk, $jam);
                $jam = "$terlambat->h:$terlambat->i:$terlambat->s";
                
                return "Terlambat $jam";
            endif;
        endif;     
    }

    public function getKeterangan($tgl, $nip)
    {
        $d = date('d', strtotime($tgl));
        if ($d < 16) :
            $tgls = date('Y-m', strtotime($tgl)).'-16';
            $tgls = date('Y-m-d', strtotime('-1 month', strtotime($tgls)));

            $tgle = date('Y-m', strtotime($tgl)).'-15';
        
        elseif ($d > 15):
            $tgls = date('Y-m', strtotime($tgl)).'-16';

            $tgle = date('Y-m', strtotime($tgl)).'-15';
            $tgle = date('Y-m-d', strtotime('+1 month', strtotime($tgle)));
        
        endif;
        $data[] = $this->getK($tgls, $tgle, $nip);
        $data[] = $this->getA($tgls, $tgle, $nip);
        $data[] = $this->getC($tgls, $tgle, $nip);
        $data[] = $this->getS($tgls, $tgle, $nip);
        $data[] = $this->getT($tgls, $tgle, $nip);
        $data[] = $this->getJmlhTgl($tgls, $tgle, $nip);
        $data[] = $this->getL($tgls, $tgle, $nip, '0');
        $data[] = $this->getL($tgls, $tgle, $nip, '1');
        $data[] = $this->getAbsen5Bulan($tgls, $tgle);
        return $data;
    }

    public function getK($tgls, $tgle, $nip)
    {
        $masuk = $this->db->konek()->query("SELECT * FROM absensi WHERE username='$nip' AND status='Masuk' AND date(tgl) BETWEEN '$tgls' AND '$tgle'");
        $pulang = $this->db->konek()->query("SELECT * FROM absensi WHERE username='$nip' AND status='Pulang' AND date(tgl) BETWEEN '$tgls' AND '$tgle'");
        
        $masuk =  $masuk->num_rows;
        $pulang = $pulang->num_rows;

        if ($masuk > $pulang) :
            return $masuk;
        elseif ($masuk < $pulang) :
            return $pulang;
        elseif ($masuk == $pulang) :
            return $masuk;
        else :
            return 'error';
        endif;
    }

    public function getA($tgls, $tgle, $nip)
    {
        $merah = 0;
        $bulan = date('m', strtotime($tgls)); $tahun = date('Y', strtotime($tgls));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for ($i=16; $i < $tanggal+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }

        $bulan = date('m', strtotime($tgle)); $tahun = date('Y', strtotime($tgle));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        
        $tggl = $tanggal-16;
        for ($i=1; $i < $tggl+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }
        for ($i=0; $i<count($tgll); $i++){
            $tgl = $tgll[$i];
            $tgl = date('D', strtotime($tgl));
            if ($tgl == 'Sun') :
                $merah += 1;
            endif;
        }

        $tgll = count($tgll);
        $masuk = $this->getK($tgls, $tgle, $nip);
        $cuti = $this->getC($tgls, $tgle, $nip);
        $sakit = $this->getS($tgls, $tgle, $nip);
        $jumlah = $tgll-$masuk-$cuti-$sakit-$merah;

        return $jumlah;
    }

    public function getC($tgls, $tgle, $nip)
    {
        $result = $this->db->konek()->query("SELECT * FROM absensi_log WHERE username='$nip' AND status='Cuti' AND approve='Disetujui' AND date(tgl) BETWEEN '$tgls' AND '$tgle'");
        $result =  $result->num_rows;

        return $result;
    }

    public function getS($tgls, $tgle, $nip)
    {
        $result = $this->db->konek()->query("SELECT * FROM absensi_log WHERE username='$nip' AND status='Sakit' AND approve='Disetujui' AND date(tgl) BETWEEN '$tgls' AND '$tgle'");
        $result =  $result->num_rows;

        return $result;
    }

    public function getT($tgls, $tgle, $nip)
    {
        $masuk = date_create(date('H:i:s', strtotime('20:00:00')));
        $terlambat = 0;

        $bulan = date('m', strtotime($tgls)); $tahun = date('Y', strtotime($tgls));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for ($i=16; $i < $tanggal+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }

        $bulan = date('m', strtotime($tgle)); $tahun = date('Y', strtotime($tgle));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        
        $tggl = $tanggal-16;
        for ($i=1; $i < $tggl+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }

        for ($i=0; $i<count($tgll); $i++){
            $tgl = $tgll[$i];
            $result = $this->db->konek()->query("SELECT * FROM absensi WHERE username='$nip' AND date(tgl) ='$tgl' AND status='Masuk' ");
            $data = $result->fetch_assoc();
            $row = $result->num_rows;
            if ($row > 0):
                $jam = date_create(date('H:i:s', strtotime($data['tgl'])));
                if ($jam > $masuk) :
                    $terlambat += 1;
                endif;
            endif;
        }
        
        // $result = $this->db->konek()->query("SELECT * FROM absensi_log WHERE username='$nip' AND status='Cuti' AND date(tgl) BETWEEN '$tgls' AND '$tgle'");
        // $result =  $result->num_rows;

        return $terlambat;
    }

    public function getL($tgls, $tgle, $nip, $kode)
    {
        $Pulang = date_create(date('H:i:s', strtotime('05:00:00')));
        $Lembur = 0;
        $lemburjam = new DateTime(date('H:i:s', strtotime('00:00:00')));

        $bulan = date('m', strtotime($tgls)); $tahun = date('Y', strtotime($tgls));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for ($i=16; $i < $tanggal+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }

        $bulan = date('m', strtotime($tgle)); $tahun = date('Y', strtotime($tgle));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        
        $tggl = $tanggal-16;
        for ($i=1; $i < $tggl+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }

        for ($i=0; $i<count($tgll); $i++){
            $tgl = $tgll[$i];
            $result = $this->db->konek()->query("SELECT * FROM absensi WHERE username='$nip' AND date(tgl) ='$tgl' AND status='Pulang' ");
            $data = $result->fetch_assoc();
            $row = $result->num_rows;
            if ($row > 0):
                $jam = date_create(date('H:i:s', strtotime($data['tgl'])));
                if ($jam > $Pulang) :
                    $Lembur += 1;
                    $lemburj = date_diff($Pulang, $jam);
                    $lemburj = 'PT'.$lemburj->h.'H'.$lemburj->i.'M'.$lemburj->s.'S';
                    $lemburjam->add(
                        new \DateInterval("$lemburj")
                    );
                endif;
            endif;
        }
        if ($kode == 0 ):
            return $Lembur;
        else :
            $lemburjam = date_format($lemburjam, 'H:i:s');
            return $lemburjam;
        endif;
    }

    public function getJmlhTgl($tgls, $tgle, $nip)
    {
        $merah = 0;
        $bulan = date('m', strtotime($tgls)); $tahun = date('Y', strtotime($tgls));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        for ($i=16; $i < $tanggal+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }

        $bulan = date('m', strtotime($tgle)); $tahun = date('Y', strtotime($tgle));
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        
        $tggl = $tanggal-16;
        for ($i=1; $i < $tggl+1; $i++) { 
            $tgll[] = "$tahun-$bulan-$i";
        }
        for ($i=0; $i<count($tgll); $i++){
            $tgl = $tgll[$i];
            $tgl = date('D', strtotime($tgl));
            if ($tgl == 'Sun') :
                $merah += 1;
            endif;
        }
        $data = count($tgll)-$merah;
        return $data;
    }

    public function Pengajuan($pengajuan, $dari, $sampai, $ket)
    {
        $username = $_SESSION['nip'];
        while ($dari <= $sampai) {
            $result = $this->db->konek()->query("INSERT INTO absensi_log (username, tgl, status, keterangan) VALUES ('$username', '$dari', '$pengajuan', '$ket')");
            $dari = date('Y-m-d',strtotime('+1 days',strtotime($dari)));
        }
        if ($result) :
            return TRUE;
        else :
            return FALSE;
        endif;
    }
    
    public function getPengajuan($nip)
    {
            $result = $this->db->konek()->query("SELECT * FROM absensi_log WHERE username='$nip'");
            $rows = $result->num_rows;
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            if ($rows > 0):
                return $data;
            endif;     
    }

    public function getsPengajuan()
    {
        $result = $this->db->konek()->query("SELECT * FROM absensi_log WHERE approve IS NULL");
        $rows = $result->num_rows;
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        if ($rows > 0):
            return $data;
        endif;
    }

    public function approve($id, $app)
    {
        $username = $_SESSION['nip'];
        if ($app == "Terima Pengajuan") :
            $update = $this->db->konek()->query("UPDATE absensi_log SET approve='Disetujui', approver='$username' WHERE id='$id'");
            if ($update) :
                return "Pengajuan Disetujui";
            else :
                return FALSE;
            endif;
        else :
            $update = $this->db->konek()->query("UPDATE absensi_log SET approve='Ditolak', approver='$username' WHERE id='$id' ");
            if ($update) :
                return "Pengajuan Ditolak";
            else :
                return FALSE;
            endif;
        endif;
    }

    public function getPeriode($tgl)
    {
        $tgls = "16 ".date('M', strtotime('-1 month', strtotime($tgl)));
        $tgle = '15 '.date('M Y', strtotime($tgl));
        return "$tgls - $tgle";
    }

    public function getReferensi($username)
    {
        $ref = date("HisdmY");
        $tgl = date("Y-m-d H:i:s");
        $result = $this->db->konek()->query("INSERT INTO print_slip (username, referensi, tgl) VALUES ('$username', '$ref', '$tgl')");
        if ($result) :
            return $ref;
        else :
            return FALSE;
        endif; 
    }

    public function getMasuk($total_karyawan)
    {
        $tgl = date('Y-m-d');
        $jam = date('H:i');
        // $jam = 18;
        if ($jam > "17:00") :
            $result = $this->db->konek()->query("SELECT * FROM absensi WHERE date(tgl) ='$tgl' AND status='Masuk' ");
        else :
            $tgl = date('Y-m-d', strtotime('-1 day', strtotime($tgl)));
            $result = $this->db->konek()->query("SELECT * FROM absensi WHERE date(tgl) ='$tgl' AND status='Masuk' ");

        endif;
        $row = $result->num_rows;
        $hasil = $row/$total_karyawan*100;
        return $hasil."%<sup>($row)</sup>";
    }

    public function getBulan()
    {
        $d = date('d');
        if ($d > 15) :
            $tgls = date('Y-m');
            $tglt = date('Y-m', strtotime('+1 month', strtotime($tgls)));
            $tgle = date('Y-m', strtotime('-5 month', strtotime($tglt)));
            while ($tgle<=$tglt){
                $tglu = date('M Y', strtotime($tgle));
                $bulan[] = $tglu;
                $tgle = date('Y-m',strtotime('+1 month',strtotime($tgle)));
            }
            return $bulan;
        else :
            $tgls = date('Y-m');
            $tgle = date('Y-m', strtotime('-5 month', strtotime($tgls)));
            while ($tgle<=$tgls){
                $tglu = date('M Y', strtotime($tgle));
                $bulan[] = $tglu;
                $tgle = date('Y-m',strtotime('+1 month',strtotime($tgle)));
            }
            return $bulan;
        endif;
    }
    
    public function getAbsen5Bulan($tgls, $tgle)
    {
        $result = $this->db->konek()->query("SELECT * FROM users WHERE posisi!='Admin'");
        $row = $result->num_rows;
        if ($row > 0) :
            while ($r = $result->fetch_assoc()) {
                $data[] = $this->getK($tgls, $tgle, $r['username']);
                // $data = array_sum($data)/$row;
            }
            return $data = array_sum($data)/$row;
        else :
            return $data = array(0,0,0,0,0);
        endif;
    }
    
}
