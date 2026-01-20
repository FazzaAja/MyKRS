<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

include_once("../db/koneksi.php");

$id = $_GET['id'];

$result = mysqli_query($con, "DELETE FROM mahasiswa WHERE id='$id'");

header("Location:mahasiswa.php");