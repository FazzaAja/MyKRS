<?php
session_start();
if ($_SESSION['user']['role'] === 'mahasiswa') {
    if (isset($_GET['id']) && $_SESSION['user']['id_mhs'] != $_GET['id']) {
        header('Location: ../auth/login.php');
        exit();
    }
}
include_once("../db/koneksi.php");

$id_mhs = $_GET['id_mhs'];
$id_matkul = $_GET['id_matkul'];

$result = mysqli_query($con, "DELETE FROM krs WHERE id_mhs='$id_mhs' AND id_matkul='$id_matkul'");

header("Location:krs.php?id=$id_mhs");