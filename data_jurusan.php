<?php
include "koneksi.php";
$db = new database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jurusan</title>
    <link rel="stylesheet" type="text/css" href="style2.css">
    
</head>
<body>
    <h2>Data Jurusan</h2>
    <table border="1">
        <tr>
            <th>Kode Jurusan</th>
            <th>Nama Jurusan</th>
            <th>Option</th>
        </tr>
        <?php

        foreach($db->tampildatajurusan()as $x){
            ?>
            <tr>
            
                <td><?php echo $x['kode_jurusan'];?></td>
                <td><?php echo $x['nama_jurusan'];?></td>
        
                <td>
                    <a href="edit_siswa.php?nis=<?php echo $x['kode_jurusan']; ?>&aksi=edit">Edit</a>


                </td>
            </tr>
        <?php } ?>
    </table>
    
    <a href="tambah_siswa.php">Tambah Data</a>
</body>
</html>