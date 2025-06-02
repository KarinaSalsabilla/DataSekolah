<?php
include "koneksi.php";
$db = new database(); // <- Pakai $db

// Ambil NISN dari URL
$id_user = $_GET['id_user']; // <- Sesuaikan, huruf besar sesuai link

// Panggil fungsi hapus
$db->hapususer($id_user); // <- Pakai $db, bukan $database

// Balikin ke halaman daftar siswa
header("Location: datauser.php");
exit;
?>
