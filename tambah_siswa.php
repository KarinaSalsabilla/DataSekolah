<?php
include "koneksi.php";
$db = new database();

if(isset($_POST['simpan'])){
    $db->tambahsiswa(
        $_POST['nisn'],
        $_POST['nama'],
        $_POST['jeniskelamin'],
        $_POST['kodejurusan'],
        $_POST['kelas'],
        $_POST['alamat'],
        $_POST['agama'],
        $_POST['nohp']
    );
    header("location:data_siswa.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Siswa</title>
</head>
<body>
    <h2>Form Tambah Data Siswa</h2>
    <form action="" method="post">
        
        <label for="nisn">NISN:</label><br>
        <input type="text" id="nisn" name="nisn" required><br><br>

        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br><br>

        <label for="jeniskelamin">Jenis Kelamin:</label><br>
        <input type="radio" id="laki-laki" name="jeniskelamin" value="L" required>
        <label for="laki-laki">Laki-Laki</label><br>
        <input type="radio" id="perempuan" name="jeniskelamin" value="P" required>
        <label for="Perempuan">Perempuan</label><br><br>

        <label for="kodejurusan">Kode Jurusan:</label><br>
        <input type="text" id="kodejurusan" name="kodejurusan" required><br><br>

        <label for="kelas">Kelas:</label><br>
        <input type="text" id="kelas" name="kelas" required><br><br>

        <label for="alamat">Alamat:</label><br>
        <textarea id="alamat" name="alamat" required></textarea><br><br>

        <label for="agama">Agama:</label><br>
        <input type="text" id="agama" name="agama" required><br><br>

        <label for="nohp">No HP:</label><br>
        <input type="text" id="nohp" name="nohp" required><br><br>

        <input type="submit" name="simpan" value="Tambah Siswa">
     
</form>
</body>
</html>