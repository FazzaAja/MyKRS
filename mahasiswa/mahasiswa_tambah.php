<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

include_once("../db/koneksi.php");

if (isset($_POST['Submit'])) {
    $nim = isset($_POST['nim']) ? trim($_POST['nim']) : '';
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? strtoupper(trim($_POST['jenis_kelamin'])) : '';
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $tgl_lahir = isset($_POST['tgl_lahir']) ? trim($_POST['tgl_lahir']) : '';

    $errors = [];
    if (empty($nim) || !preg_match('/^\d+$/', $nim)) {
        $errors[] = 'NIM harus berupa angka.';
    }
    if (empty($nama) || !preg_match('/^[a-zA-Z .\-]+$/', $nama)) {
        $errors[] = 'Nama hanya boleh huruf, spasi, titik, dan tanda minus.';
    }
    if ($jenis_kelamin !== 'L' && $jenis_kelamin !== 'P') {
        $errors[] = 'Jenis kelamin harus L atau P.';
    }
    if (empty($alamat)) {
        $errors[] = 'Alamat tidak boleh kosong.';
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_lahir)) {
        $errors[] = 'Format tanggal lahir salah (YYYY-MM-DD).';
    }

    if (count($errors) > 0) {
        foreach ($errors as $err) {
            echo '<script>alert("'.$err.'");</script>';
        }
    } else {
        
        $stmt = mysqli_prepare($con, "INSERT INTO mahasiswa(nim, nama, jenis_kelamin, alamat, tgl_lahir) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nim, $nama, $jenis_kelamin, $alamat, $tgl_lahir);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo '<script>alert("Data berhasil disimpan."); window.location="mahasiswa.php";</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Tambah Data Mahasiswa</title>
</head>

<body>
    <a href="mahasiswa.php"><< Kembali</a>
    <br />
    <h2>Tambah Data Mahasiswa</h2><br />
    <form action="" method="post" name="form1">
        <table width="25%" border="0">
            <tr>
                <td>NIM</td>
                <td><input type="number" name="nim"></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama"></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td><input type="text" name="alamat"></td>
            </tr>
            <tr>
                <td>Tgl Lahir</td>
                <td><input type="date" name="tgl_lahir"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="Submit" value="Tambah"></td>
            </tr>
        </table>
    </form>
</body>

</html>