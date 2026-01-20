
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
include_once("../koneksi.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$where = '';
if ($search !== '') {
    $search_esc = mysqli_real_escape_string($con, $search);
    $where = "WHERE nim LIKE '%$search_esc%' OR nama LIKE '%$search_esc%' OR alamat LIKE '%$search_esc%'";
}
$sql_data = "SELECT * FROM mahasiswa $where LIMIT $limit OFFSET $offset";
$result = mysqli_query($con, $sql_data);
$sql_count = "SELECT COUNT(*) as total FROM mahasiswa $where";
$res_count = mysqli_query($con, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_rows = $row_count['total'];
$total_pages = ceil($total_rows / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Data Mahasiswa</title>
</head>

<body>
    <a href="../logout.php"><< Logout</a><br />
    <h2>Data Mahasiswa</h2><br />
    <a href="mahasiswa_tambah.php">+ Tambah Data Baru</a><br />
    <form method="get" style="margin: 16px 0; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">

        <input type="text" name="search" placeholder="Cari NIM, Nama, atau Alamat" value="<?= htmlspecialchars($search ?? '', ENT_QUOTES) ?>" style="padding:6px;width:220px;">
        <button type="submit" style="padding:6px 12px;">Cari</button>
        <?php if ($search): ?>
            <a href="mahasiswa.php" style="margin-left:10px;">Reset</a> |
        <?php endif; ?>

        <br />    

        <label for="limit" style="margin-right:2px;">Tampil:</label>
        <select name="limit" id="limit" onchange="this.form.submit()" style="padding:2px 6px;font-size:13px;width:auto;display:inline-block;vertical-align:middle;">
            <?php foreach ([5,10,20,50,100] as $opt): ?>
                <option value="<?= $opt ?>" <?= $limit==$opt?'selected':'' ?>><?= $opt ?></option>
            <?php endforeach; ?>
        </select>

    </form>
    <div class="table-responsive">
        <table border=1>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Tanggal Lahir</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($data = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $data['nim'] . "</td>";
                echo "<td>" . $data['nama'] . "</td>";
                echo "<td>" . $data['jenis_kelamin'] . "</td>";
                echo "<td>" . $data['alamat'] . "</td>";
                echo "<td>" . $data['tgl_lahir'] . "</td>";
                echo "<td><a href='../krs/krs.php?id=$data[id]'>KRS</a> | <a href='mahasiswa_ubah.php?id=$data[id]'>Ubah</a> | <a href='mahasiswa_hapus.php?id=$data[id]' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a></td>";
                echo "</tr>";
            }
            ?>
            
                <?php if ($total_pages > 1): ?>
                <div style="margin:16px 0;display:flex;gap:4px;flex-wrap:wrap;align-items:center;">
                    <?php for ($p=1; $p<=$total_pages; $p++): ?>
                        <?php
                            $params = $_GET;
                            $params['page'] = $p;
                            $url = 'mahasiswa.php?' . http_build_query($params);
                        ?>
                        <a href="<?= $url ?>" style="padding:6px 10px;<?= $p==$page?'background:#007bff;color:#fff;border-radius:4px;':'' ?>border:1px solid #ddd;"> <?= $p ?> </a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            
            </tbody>
        </table>
    </div>
</body>

</html>