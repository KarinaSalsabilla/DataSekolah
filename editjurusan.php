<?php
include_once "koneksi.php";
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_jurusan'])) {
    $kode_jurusan = $_POST['kode_jurusan'];
    $nama_jurusan = $_POST['nama'];
    
    // Debug untuk melihat data yang diterima
    // echo "Kode: " . $kode_jurusan . ", Nama: " . $nama_jurusan; exit;
    
    $db->editjurusan($kode_jurusan, $nama_jurusan);
    header("Location: datajurusan.php?status=edit_sukses");
    exit;
} else {
    header("Location: datajurusan.php?status=error");
    exit;
}
?>