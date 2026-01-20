<?php
include_once("../koneksi.php");

$id_mhs = $_GET['id_mhs'];
$id_matkul = $_GET['id_matkul'];

$result = mysqli_query($con, "DELETE FROM krs WHERE id_mhs='$id_mhs' AND id_matkul='$id_matkul'");

header("Location:krs.php?id=$id_mhs");