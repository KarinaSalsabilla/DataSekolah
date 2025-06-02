<?php
include "koneksi.php";
$db = new database();

if(isset($_POST['simpan'])){
    $db->tambahagama(
        $_POST['nama']
    );
    header("location:data_agama.php");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Form Tambah Data Agama</h2>
    <form action="" method="post">

        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br><br>

        <input type="submit" name="simpan" value="Tambah Agama">
        </form>
</body>
</html>