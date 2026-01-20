<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

include_once("../db/koneksi.php");

if (isset($_POST['update'])) {
    // Validasi dan sanitasi input
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? strtoupper(trim($_POST['jenis_kelamin'])) : '';
    $alamat = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
    $tgl_lahir = isset($_POST['tgl_lahir']) ? trim($_POST['tgl_lahir']) : '';

    $errors = [];
    if ($id <= 0) {
        $errors[] = 'ID tidak valid.';
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
        
        $stmt = mysqli_prepare($con, "UPDATE mahasiswa SET nama=?, jenis_kelamin=?, alamat=?, tgl_lahir=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $nama, $jenis_kelamin, $alamat, $tgl_lahir, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo '<script>alert("Data berhasil diubah."); window.location="mahasiswa.php";</script>';
        exit();
    }
}
?>
<?php
$id = $_GET['id'];

$result = mysqli_query($con, "SELECT * FROM mahasiswa WHERE id='$id'");
while ($user_data = mysqli_fetch_array($result)) {
    $nim = $user_data['nim'];
    $nama = $user_data['nama'];
    $jenis_kelamin = $user_data['jenis_kelamin'];
    $alamat = $user_data['alamat'];
    $tgl_lahir = $user_data['tgl_lahir'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Ubah Data Mahasiswa</title>
</head>
    

<body>
    <a href="mahasiswa.php"><< Kembali</a>
    <br />
    <h2>Ubah Data Mahasiswa</h2><br />
    <form name="update_mahasiswa" method="post" action="">
        <table border="0">
            <tr>
                <td>Nama</td>
                <td><input type="text" name="nama" value=<?php echo $nama; ?>></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="L" <?php if($jenis_kelamin=="L") echo "selected"; ?>>Laki-laki</option>
                        <option value="P" <?php if($jenis_kelamin=="P") echo "selected"; ?>>Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>alamat</td>
                <td><input type="text" name="alamat" value=<?php echo $alamat; ?>></td>
            </tr>
            <tr>
                <td>Tgl Lahir</td>
                <td><input type="date" name="tgl_lahir" value=<?php echo $tgl_lahir; ?>></td>
            </tr>
            <tr>
                <td><input type="hidden" name="id" value=<?php echo $id ?>></td>
                <td><input type="submit" name="update" value="Update"></td>
            </tr>
        </table>
    </form>
</body>

</html>