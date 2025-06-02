<?php
include_once "koneksi.php";
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_agama'])) {
    $idagama = $_POST['idagama'];
    $nama_agama = $_POST['nama'];
    
    // Debug untuk melihat data yang diterima
    // echo "Kode: " . $kode_jurusan . ", Nama: " . $nama_jurusan; exit;
    
    $db->editagama($idagama, $nama_agama);
    header("Location: datagama.php?status=edit_sukses");
    exit;
} else {
    header("Location: datagama.php?status=error");
    exit;
}
?>