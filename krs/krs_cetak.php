<?php
require_once '../vendor/autoload.php';
require_once '../koneksi.php';
$id = $_GET['id'];

// Query data mahasiswa
$mahasiswa = mysqli_query($con, "SELECT * FROM mahasiswa WHERE id = $id");
$mhs = mysqli_fetch_array($mahasiswa);

// Query data KRS
$result = mysqli_query($con, "SELECT mk.kode, mk.nama, mk.kelas, mk.sks FROM krs JOIN mata_kuliah mk ON mk.id = krs.id_matkul WHERE krs.id_mhs = $id");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Kartu Rencana Studi (KRS)', 0, 1, 'C');

$pdf->Ln(10); 

$pdf->SetFont('Arial', 'B', 12);
$leftMargin = 35; 
$pdf->SetX($leftMargin);
$pdf->Cell(30, 8, 'Nama', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, ': ' . $mhs['nama'], 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX($leftMargin);
$pdf->Cell(30, 8, 'NIM', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, ': ' . $mhs['nim'], 0, 1);

// Semester
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX($leftMargin);
$pdf->Cell(30, 8, 'Semester', 0, 0);
$pdf->SetFont('Arial', '', 12);
$tahun_masuk = (int)('20' . substr($mhs['nim'], 0, 2));
$tahun_sekarang = (int)date('Y');
$bulan_sekarang = (int)date('n');
$selisih_tahun = $tahun_sekarang - $tahun_masuk;
$basis_semester = $selisih_tahun * 2;
if ($bulan_sekarang >= 7 && $bulan_sekarang <= 12) {
    $semester_saat_ini = $basis_semester + 1;
} else {
    $semester_saat_ini = $basis_semester;
}
$semester_saat_ini = max(1, $semester_saat_ini);
$pdf->Cell(0, 8, ': ' . $semester_saat_ini, 0, 1);
//

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX($leftMargin);
$pdf->Cell(30, 8, 'Maks SKS', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, ': 24 (3 Mata Kuliah)', 0, 1);

$pdf->Ln(10);
// Table header

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetX($leftMargin);
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(30, 10, 'Kode MK', 1, 0, 'C');
$pdf->Cell(70, 10, 'Matakuliah', 1, 0, 'C');
$pdf->Cell(20, 10, 'Kelas', 1, 0, 'C');
$pdf->Cell(20, 10, 'SKS', 1, 0, 'C');
$pdf->Ln();



$pdf->SetFont('Arial', '', 12);
$i = 1;
$total_sks = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->SetX($leftMargin);
    $pdf->Cell(10, 10, $i, 1);
    $pdf->Cell(30, 10, $row['kode'], 1);
    $pdf->Cell(70, 10, $row['nama'], 1);
    $pdf->Cell(20, 10, $row['kelas'], 1, 0, 'C');
    $pdf->Cell(20, 10, $row['sks'], 1, 0, 'C');
    $pdf->Ln();
    $total_sks += (int)$row['sks'];
    $i++;
}

// Tampilkan jumlah SKS
$pdf->SetX($leftMargin);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, 'Total SKS', 1);
$pdf->Cell(20, 10, $total_sks, 1, 0, 'C');

$pdf->Output('I', 'KRS_' . $mhs['nim'] . '.pdf');

require_once '../vendor/autoload.php';
require_once '../koneksi.php';
require_once '../vendor/setasign/fpdf/fpdf.php';
