<?php
include "koneksi.php";
$db = new database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Data Siswa</title>

</head>
<body>
    <h2>Data Siswa</h2>
    <table border="1">
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Jurusan</th>
            <th>Kelas</th>
            <th>Alamat</th>
            <th>Agama</th>
            <th>No Hp</th>
            <th>Option</th>
        </tr>
        <?php
        $no = 1;
        foreach($db->tampilkodejurusan()as $x){
            ?>
            <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $x['NISN'];?></td>
                <td><?php echo $x['Nama'];?></td>
                <td><?php echo $x['Jenis Kelamin'];?></td>
                <td><?php echo $x['Jurusan'];?></td>
                <td><?php echo $x['Kelas'];?></td>
                <td><?php echo $x['Alamat'];?></td>
                <td><?php echo $x['agama'];?></td>
                <td><?php echo $x['No Hp'];?></td>
                <td>
                    <a href="edit_siswa.php?nis=<?php echo $x['NISN']; ?>&aksi=edit">Edit</a>
                    <a href="edit_siswa.php?nis=<?php echo $x['NISN']; ?>&aksi=edit">Hapus</a>


                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="tambah_siswa.php">Tambah Data</a>
</body>
</html>