<?php
include "koneksi.php";
$db = new database(); // <- Pakai $db

// Ambil NISN dari URL
$nisn = $_GET['NISN']; // <- Sesuaikan, huruf besar sesuai link

// Panggil fungsi hapus
$db->hapussiswa($nisn); // <- Pakai $db, bukan $database

// Balikin ke halaman daftar siswa
header("Location: datasiswa.php");
exit;
?>
