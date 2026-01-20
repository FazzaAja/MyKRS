<?php
include_once("../koneksi.php");

$id = $_GET['id'];

$result = mysqli_query($con, "DELETE FROM mahasiswa WHERE id='$id'");

header("Location:mahasiswa.php");