<?php
include "koneksi.php";
$db = new database();

// Ambil kodejurusan dari URL
$kode_jurusan = $_GET['kode_jurusan']; // <- Sesuaikan, huruf besar sesuai link

// Panggil fungsi hapus
$db->hapusjurusan($kode_jurusan); // <- Pakai $db, bukan $database

// Balikin ke halaman daftar siswa
header("Location: datajurusan.php");
exit;
?>
