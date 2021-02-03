<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();
ob_start();

if (!isset($_SESSION['nip'])) :
    header("location: /login");
elseif (!isset($_GET['id'])) :
    header ("location: ../");
elseif (!isset($_GET['periode'])) :
    header ("location: ../");
elseif ($_SESSION['posisi'] == "Admin") :
    $username = $_GET['id'];
elseif ($_SESSION['posisi'] !== "Admin") :
    $username = $_SESSION['nip'];
endif; 

$periode = $_GET['periode'];
$user = $users->getUsers($username);
$data = $absensi->getKeterangan($periode, $username);
$reff = $absensi->getReferensi($username);
$masuk = $data[0];
$cuti = $data[2];
$sakit = $data[3];
$terlambat = $data[4];
$lembur = $data[7];

$uang_lembur = 0;

$h = date('H', strtotime($lembur));
$i = date('i', strtotime($lembur));

if ($h > 0):
    $uang_lembur += $h*30000;
    if ($i > 30) :
        $uang_lembur += 30000;
    endif;
endif;
$gapok = $masuk*175000;
$uang_makan = ($masuk-$terlambat)*25000;
$bpjs = 180000;
$penerimaan = $gapok+$uang_makan+$bpjs+$uang_lembur;
$pengurangan = $bpjs;
$total = $penerimaan-$pengurangan;


require('assets/fpdf/fpdf.php');
class PDF extends FPDF{

    function pdfCell($w, $h, $x, $t){
        $height = $h/3;
        $first = $height+2;
        $second = $height+$height+$height+3;
        $len = str_word_count($t);
        $len2 = strlen($t);

        if ($len>2){
            if ($len2>15){
                $txt = explode(" ",$t);
                $this->SetX($x);
                $this->Cell($w,$first,$txt[0]." ".$txt[1],'','','');
                $this->SetX($x);
                $this->Cell($w,$second,$txt[2]." ".$txt[3],'','','');
                $this->SetX($x);
                $this->Cell($w,$h,"",'LTRB',0,'L',0);
            } else {
                $txt = explode(" ",$t);
                $this->SetX($x);
                $this->Cell($w,$first,$txt[0]." ".$txt[1]." ".$txt[2],'','','');
                $this->SetX($x);
                $this->Cell($w,$second,$txt[3],'','','');
                $this->SetX($x);
                $this->Cell($w,$h,"",'LTRB',0,'L',0);
            }
        } else {
            $this->SetX($x);
            $this->Cell($w,$h,$t,'LTRB',0,'L',0);
        }
    }

    function Footer() {
        // mengatur posisi 1,5 cm ke bawah
        $this->SetY(-15);
        // arial italic 8
        $this->SetFont('Arial','I',8);
        // penomoran halaman
        $this->Cell(0,1,'Halaman '.$this->PageNo().'/{nb}',0,0,'R');
    }
}


$pdf = new PDF('L','mm','A4');
$pdf->SetAutoPageBreak('3', '3');
$pdf->AliasNbPages(); // fungsi untuk mengitung jumlah total halaman
$pdf->AddPage(); // membuat halaman
$pdf->SetFont('Times','',12); // Times 12
    
    // $pdf->Image("img/gs1.png",33,30,70);
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(0,0,'HCOS',0,0,'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0,1,'Jl. Kembangan Selatan No. 47, Jakarta 11610',0,0,'C');
    $pdf->Ln();

    $pdf->SetXY(225,26);
    $pdf->SetFont('Times','B',12); // Times 12
    $pdf->Cell(40,11,"Slip Gaji Karyawan",1,1,'C');
    $pdf->Ln(5);
        // GARIS
        $pdf->SetLineWidth(0);
        $pdf->Line(33,40,265,40);
        $pdf->SetLineWidth(0);
        $pdf->Line(33,40.5,265,40.5);

    foreach ($user as $user) :
        $nama = $user['nama'];
        // NIP
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(30,5,'NIP',0,0,'L');
        $pdf->Cell(130,5,": $username ",0,0,'L');
        // PERIODE
        $pdf->Cell(30,5,'Tanggal',0,0,'L');
        $pdf->Cell(0,5,": ".date('d/m/Y'),0,0,'L');
        $pdf->ln();
        // NAMA
        $pdf->Cell(30,5,'Nama',0,0,'L');
        $pdf->Cell(130,5,": ".$user['nama'],0,0,'L');
        // NO REFERENSI
        $pdf->Cell(30,5,'No Referensi',0,0,'L');
        $pdf->Cell(0,5,": $reff",0,0,'L');
        $pdf->Ln();
        // POSISI
        $pdf->Cell(30,5,'Jabatan',0,0,'L');
        $pdf->Cell(130,5,": ".$user['posisi'],0,0,'L');
        // PERIODE
        $pdf->Cell(30,5,'Periode',0,0,'L');
        $pdf->Cell(0,5,": ".$absensi->getPeriode($periode),0,0,'L');
        $pdf->Ln(10);
    endforeach;

        // GARIS
        $pdf->SetLineWidth(0);
        $pdf->Line(33,60,265,60);
        $pdf->SetLineWidth(0);
        $pdf->Line(33,60.5,265,60.5);

    // TH
    $pdf->SetFont('Times','B',13);
    $pdf->Cell(20,5,'-',0,0,'C');
    $pdf->Cell(170,5,"Penerimaan",0,0,'L');
    $pdf->Ln();
    // Gaji Pokok
    $pdf->SetFont('Times','',12);
    $pdf->Cell(20,5,'1.',0,0,'C');
    $pdf->Cell(170,5,"Gaji Pokok",0,0,'L');
    $pdf->Cell(0,5,"Rp. ".number_format(($gapok), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln();
    // Tunjangan Konsumsi
    $pdf->Cell(20,5,'2.',0,0,'C');
    $pdf->Cell(170,5,"Tunjangan Konsumsi",0,0,'L');
    $pdf->Cell(0,5,"Rp. ".number_format(($uang_makan), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln();
    // Tunjangan Konsumsi
    $pdf->Cell(20,5,'3.',0,0,'C');
    $pdf->Cell(170,5,"Tunjangan Lembur",0,0,'L');
    $pdf->Cell(0,5,"Rp. ".number_format(($uang_lembur), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln();
    // Tunjangan BPJS Ketenagakerjaan
    $pdf->Cell(20,5,'4.',0,0,'C');
    $pdf->Cell(170,5,"Tunjangan BPJS Ketenagakerjaan",0,0,'L');
    $pdf->Cell(0,5,"Rp. ".number_format(($bpjs), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln(10);
    // GARIS
    $pdf->SetLineWidth(0);
    $pdf->Line(180,90,265,90);
    $pdf->SetLineWidth(0);
    $pdf->Line(180,90.5,265,90.5);
    // Jumlah
    $pdf->SetFont('Times','B',13);
    $pdf->Cell(190,5,'Jumlah',0,0,'R');
    $pdf->Cell(0,5,"Rp. ".number_format(($penerimaan), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln(10);
    // GARIS
    $pdf->SetLineWidth(0);
    $pdf->Line(180,99,265,99);
    $pdf->SetLineWidth(0);
    $pdf->Line(180,99.5,265,99.5);
    // TH
    $pdf->Cell(20,5,'-',0,0,'C');
    $pdf->Cell(170,5,"Pengurangan",0,0,'L');
    $pdf->Ln();
    // Gaji Pokok
    $pdf->SetFont('Times','',12);
    $pdf->Cell(20,5,'1.',0,0,'C');
    $pdf->Cell(170,5,"Tunjangan BPJS Ketenagakerjaan",0,0,'L');
    $pdf->Cell(0,5,"Rp. ".number_format(($bpjs), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln(10);
    // GARIS
    $pdf->SetLineWidth(0);
    $pdf->Line(180,115,265,115);
    $pdf->SetLineWidth(0);
    $pdf->Line(180,115.5,265,115.5);
    // JUMLAH
    $pdf->SetFont('Times','B',13);
    $pdf->Cell(190,5,'Jumlah',0,0,'R');
    $pdf->Cell(0,5,"Rp. ".number_format(($pengurangan), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln(10);
    // GARIS
    $pdf->SetLineWidth(0);
    $pdf->Line(180,124,265,124);
    $pdf->SetLineWidth(0);
    $pdf->Line(180,124.5,265,124.5);
    // JUMLAH
    $pdf->SetFont('Times','B',13);
    $pdf->Cell(190,5,'Jumlah Diterima',0,0,'R');
    $pdf->Cell(0,5,"Rp. ".number_format(($total), 0, ".", ".").",-",0,0,'R');
    $pdf->Ln(20);
    // GARIS
    $pdf->SetLineWidth(0);
    $pdf->Line(33,133,265,133);
    $pdf->SetLineWidth(0);
    $pdf->Line(33,133.5,265,133.5);

    $pdf->SetFont('Times','B',13);
    $pdf->Cell(0,5,'Jakarta, '.date('d M Y'),0,0,'C');
    $pdf->ln(10);
    $pdf->Cell(117.5,5,'Manager,',0,0,'C');
    $pdf->Cell(117.5,5,'Penerima,',0,0,'C');
    $pdf->ln(30);
    $pdf->Cell(117.5,5,'( Jony Attabik )',0,0,'C');
    $pdf->Cell(117.5,5,"( $nama )",0,0,'C');
    

$pdf->Output($reff.'.pdf', 'D'); // menampilkan hasil...

?>