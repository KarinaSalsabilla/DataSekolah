<?php
include "koneksi.php";
$db = new database();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Agama</title>
</head>
<body>
    <h2>Data Agama</h2>
    <table border="1">
        <tr>
            <th>Kode Agama</th>
            <th>Nama Agama</th>
        </tr>
        <?php

        foreach($db->tampildataagama()as $x){
            ?>
            <tr>
            
                <td><?php echo $x['idagama'];?></td>
                <td><?php echo $x['nama_agama'];?></td>
        
                <td>
                    <a href="edit_siswa.php?nis=<?php echo $x['idagama']; ?>&aksi=edit">Edit</a>


                </td>
            </tr>
        <?php } ?>
    </table>
    <a href="tambah_agama.php">Tambah Data</a>
</body>
</html>