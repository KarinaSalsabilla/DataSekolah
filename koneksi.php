<?php
class Database
{
    var $host = "localhost";
    var $username = "root";
    var $password = "";
    var $db = "sekolah";
    var $koneksi;


    function __construct()
    {
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->password
        );
        $cekdb = mysqli_select_db(
            $this->koneksi,
            $this->db
        );
    }

    function tampildatasiswa()
    {
        $hasil = []; // Tambahkan ini
        $data = mysqli_query($this->koneksi, "SELECT * FROM siswa");
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }



    function tampildatajurusan()
    {
        $hasil = [];
        $data = mysqli_query($this->koneksi, "SELECT * FROM jurusan");
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }


    function tampildataagama()
    {
         $hasil = [];
        $data = mysqli_query(
            $this->koneksi,
            "select * from agama"
        );
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function tampildatauser()
    {
         $hasil = [];
        $data = mysqli_query($this->koneksi, "SELECT * FROM user_login");
        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil ?? [];
    }


    function tampilkodejurusan()
    {
         $hasil = [];
        $data = mysqli_query(
            $this->koneksi,
            "SELECT 
                s.nisn AS 'NISN', 
                s.nama AS 'Nama', 
                s.jenis_kelamin AS 'Jenis Kelamin', 
                j.nama_jurusan AS 'Jurusan', 
                j.kode_jurusan AS 'kode_jurusan',     
                s.kelas AS 'Kelas', 
                s.alamat AS 'Alamat', 
                a.nama_agama AS 'agama', 
                a.idagama AS 'idagama',              
                s.nohp AS 'No Hp' 
            FROM siswa s
            LEFT JOIN jurusan j ON s.kode_jurusan = j.kode_jurusan
            LEFT JOIN agama a ON s.agama = a.idagama"
        );

        while ($row = mysqli_fetch_array($data)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    function tambahsiswa(
        $nisn,
        $nama,
        $jeniskelamin,
        $kodejurusan,
        $kelas,
        $alamat,
        $agama,
        $nohp
    ) {
        $query = "INSERT INTO siswa (nisn, nama, jenis_kelamin, kode_jurusan, kelas, alamat, agama, nohp) 
                  VALUES('$nisn', '$nama', '$jeniskelamin', '$kodejurusan', '$kelas', '$alamat', '$agama', '$nohp')";

        if (!mysqli_query($this->koneksi, $query)) {
            echo "Error: " . mysqli_error($this->koneksi);
        }
    }


    function tambahjurusan(
        $nama_jurusan
    ) {
        mysqli_query(
            $this->koneksi,
            "insert into jurusan (nama_jurusan)
            values('$nama_jurusan')"
        );
    }

    function tambahagama(
        $nama_agama
    ) {
        mysqli_query(
            $this->koneksi,
            "insert into agama (nama_agama)
                 values('$nama_agama')"
        );
    }

    public function tambahuser($username, $password, $nama_lengkap, $role, $foto)
    {
        $stmt = $this->koneksi->prepare("INSERT INTO user_login (username, password, nama_lengkap, role, foto) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $nama_lengkap, $role, $foto);
        $stmt->execute();
    }


    function hapussiswa($nisn)
    {
        $stmt = $this->koneksi->prepare("DELETE FROM siswa WHERE nisn = ?");
        $stmt->bind_param("s", $nisn);
        $stmt->execute();
    }

    function hapusjurusan($kode_jurusan)
    {
        mysqli_query(
            $this->koneksi,
            "delete from jurusan where kode_jurusan='$kode_jurusan'"
        );
    }

    function hapusagama($idagama)
    {
        mysqli_query(
            $this->koneksi,
            "delete from agama where idagama='$idagama'"
        );
    }

    function hapususer($id_user)
    {
        mysqli_query(
            $this->koneksi,
            "delete from user_login where id_user='$id_user'"
        );
    }

    function editsiswa($nisn, $nama, $jeniskelamin, $jurusan, $kelas, $alamat, $agama, $nohp)
    {
        mysqli_query(
            $this->koneksi,
            "update siswa set 
                nama='$nama', 
                jenis_kelamin='$jeniskelamin', 
                kode_jurusan='$jurusan', 
                kelas='$kelas', 
                alamat='$alamat', 
                agama='$agama', 
                nohp='$nohp' 
            where nisn='$nisn'"
        );
    }

    function editjurusan($kode_jurusan, $nama_jurusan)
    {
        mysqli_query(
            $this->koneksi,
            "UPDATE jurusan SET nama_jurusan='$nama_jurusan' WHERE kode_jurusan='$kode_jurusan'"
        );
    }

    function editagama($idagama, $nama_agama)
    {
        mysqli_query(
            $this->koneksi,
            "UPDATE agama SET nama_agama='$nama_agama' WHERE idagama='$idagama'"
        );
    }


    function login($username, $password)
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM user_login WHERE username = '$username' AND password = '$password'"
        );

        if (mysqli_num_rows($query) > 0) {
            return mysqli_fetch_assoc($query);
        } else {
            return false;
        }
    }
}
