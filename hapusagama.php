<?php
include "koneksi.php";
$db = new database(); // <- Pakai $db

// Ambil NISN dari URL
$idagama = $_GET['idagama']; // <- Sesuaikan, huruf besar sesuai link

// Panggil fungsi hapus
$db->hapusagama($idagama); // <- Pakai $db, bukan $database

// Balikin ke halaman daftar siswa
header("Location: datagama.php");
exit;
?>
