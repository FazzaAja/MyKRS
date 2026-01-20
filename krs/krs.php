<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SESSION['user']['role'] === 'mahasiswa') {
    if (isset($_GET['id']) && $_SESSION['user']['id_mhs'] != $_GET['id']) {
        header('Location: ../auth/login.php');
        exit();
    }
}

include_once("../db/koneksi.php");

$id = $_GET['id'];

// Hanya mahasiswa yang bisa tambah KRS
if ($_SESSION['user']['role'] === 'mahasiswa' && isset($_POST['Submit'])) {
    $id_mhs = isset($_POST['id_mhs']) ? intval($_POST['id_mhs']) : 0;
    $id_matkul = isset($_POST['id_matkul']) ? intval($_POST['id_matkul']) : 0;
    $errors = [];
    if ($id_mhs <= 0) {
        $errors[] = 'ID Mahasiswa tidak valid.';
    }
    if ($id_matkul <= 0) {
        $errors[] = 'ID Mata Kuliah tidak valid.';
    }
    if (count($errors) > 0) {
        foreach ($errors as $err) {
            echo '<script>alert("'.$err.'");</script>';
        }
    } else {
        // Gunakan prepared statement
        $stmt = mysqli_prepare($con, "INSERT INTO krs(id_mhs, id_matkul) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ii", $id_mhs, $id_matkul);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: krs.php?id=" . urlencode($id_mhs));
        exit();
    }
}

$result = mysqli_query($con, "SELECT * FROM krs, mahasiswa, mata_kuliah mk WHERE mahasiswa.id = $id AND mk.id = krs.id_matkul AND mahasiswa.id = krs.id_mhs");

$mahasiswa = mysqli_query($con, "SELECT * FROM mahasiswa WHERE id = $id");

// Ambil data mata_kuliah dari API
$api_url = 'http://127.0.0.1/pwd/api_matakuliah.php/';
$matkul_data = [];
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $matkul_data = json_decode($output, true);
} else {
    $matkul_data = json_decode(file_get_contents($api_url), true);
}



// Ambil id_matkul yang sudah diambil oleh mahasiswa
$krs_ids = [];
$krs_result = mysqli_query($con, "SELECT id_matkul FROM krs WHERE id_mhs = '$id'");
while ($row = mysqli_fetch_assoc($krs_result)) {
    $krs_ids[] = $row['id_matkul'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <title>Data Mahasiswa</title>
</head>

<body>
    <?php if ($_SESSION['user']['role'] === 'mahasiswa'): ?>
        <a href="../auth/logout.php" onclick="return confirm('Yakin ingin logout?');"><< Logout</a>
    <?php else: ?>
        <a href="../mahasiswa/mahasiswa.php"><< Kembali</a>
    <?php endif; ?>
    <br />
    <h2>Kartu Rencana Studi (KRS)</h2><br />
   

    <?php 
        $mhs = mysqli_fetch_array($mahasiswa);

        $nim = $mhs['nim'];

        $tahun_masuk = (int)('20' . substr($nim, 0, 2));

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

        echo "<b>Nama         : </b>".$mhs['nama']."<br />";
        echo "<b>NIM          : </b>".$mhs['nim']."<br />";
        echo "<b>Semester     : </b>".$semester_saat_ini;
        echo "<br />";
        echo "<b>Maksimal SKS : </b>24 (3 Mata Kuliah)<br />";
    ?>

    <br /><br />
    <a href="krs_cetak.php?id=<?= $mhs['id'] ?>" target="_blank" style="display:inline-block;margin-bottom:10px;background:#0000FF;color:#fff;padding:8px 16px;text-decoration:none;border-radius:4px;">Cetak PDF</a>

    <div class="table-responsive">
        <table border=1>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Kode MK</th>
                    <th>Matakuliah</th>
                    <th>Kelas</th>
                    <th>SKS</th>
                    <th>Pilih</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            while ($data = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>" . $data['kode'] . "</td>";
                echo "<td>" . $data['nama'] . "</td>";
                echo "<td>" . $data['kelas'] . "</td>";
                echo "<td>" . $data['sks'] . "</td>";
                echo "<td><a href='krs_hapus.php?id_mhs=$data[id_mhs]&id_matkul=$data[id_matkul]' onclick=\"return confirm('Yakin ingin menghapus mata kuliah ini?');\">Delete</a></td>";
                echo "</tr>";
                $i++;
            }
            ?>
            </tbody>
        </table>
    </div>
    
    <?php 
        if($i <= 3){
    ?>

    <br /><br />
    <div class="table-responsive">
        <table border=1>
            <thead>
                <tr>
                    <th>Kode MK</th>
                    <th>Matakuliah</th>
                    <th>Kelas</th>
                    <th>SKS</th>
                    <th>Pilih</th>
                </tr>
            </thead>
            <tbody>
            <form action="krs?id=<?= $mhs["id"] ?>" method="post">
                <input type="hidden" name="id_mhs" value="<?= $mhs["id"] ?>">
                <?php
                    if (is_array($matkul_data)) {
                        foreach ($matkul_data as $mk) {
                            if (!in_array($mk['id'], $krs_ids)) {
                                echo "<input type='hidden' name='id_matkul' value='".$mk["id"]."'>";
                                echo "<tr>";
                            echo "<td>" . $mk['kode'] . "</td>";
                            echo "<td>" . $mk['nama'] . "</td>";
                            echo "<td>" . $mk['kelas'] . "</td>";
                            echo "<td>" . $mk['sks'] . "</td>";
                            echo "<td><input type='submit' name='Submit' value='Tambah'></td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    echo '<tr><td colspan="5">Gagal mengambil data matakuliah dari API.</td></tr>';
                }
            }
            ?>

        </form>
    </table>
</body>

</html>