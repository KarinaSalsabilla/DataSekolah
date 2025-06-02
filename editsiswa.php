<?php
include('koneksi.php'); // Pastikan untuk menyertakan koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data dari form
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kodejurusan = $_POST['kodejurusan'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $nohp = $_POST['nohp'];

    // Panggil fungsi edit dari class Database
    $db = new Database();
    $db->editsiswa($nisn, $nama, $jeniskelamin, $kodejurusan, $kelas, $alamat, $agama, $nohp);

    // Redirect setelah update (sesuaikan dengan kebutuhan)
    header("Location: datasiswa.php"); // Redirect kembali ke halaman utama
}
?>
