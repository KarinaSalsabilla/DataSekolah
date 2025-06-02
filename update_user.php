<?php
session_start();
include 'koneksi.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak: Anda belum login!']);
    exit;
}

$id_user = $_SESSION['id_user'];

try {
    // Buat objek dari class Database
    $db = new Database();
    $koneksi = $db->koneksi;

    // Handle Auto Upload Foto
    if (isset($_POST['auto_upload']) && isset($_FILES['foto_baru'])) {
        $upload_dir = 'uploads/';
        
        // Buat direktori jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file = $_FILES['foto_baru'];
        
        // Validasi file
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            echo json_encode(['success' => false, 'message' => 'Tipe file tidak didukung!']);
            exit;
        }
        
        if ($file['size'] > 2 * 1024 * 1024) { // 2MB
            echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar! Maksimal 2MB.']);
            exit;
        }
        
        // Generate nama file unik
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $id_user . '_' . time() . '.' . $extension;
        $filepath = $upload_dir . $filename;
        
        // Hapus foto lama jika ada
        if (!empty($_SESSION['foto']) && file_exists($upload_dir . $_SESSION['foto'])) {
            unlink($upload_dir . $_SESSION['foto']);
        }
        
        // Upload file baru
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Update database
            $stmt = $koneksi->prepare("UPDATE user_login SET foto = ? WHERE id_user = ?");
            $stmt->bind_param("si", $filename, $id_user);
            
            if ($stmt->execute()) {
                $_SESSION['foto'] = $filename;
                echo json_encode([
                    'success' => true, 
                    'message' => 'Foto berhasil diupload!',
                    'filename' => $filename
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto ke database!']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupload foto!']);
        }
        exit;
    }

    // Handle Hapus Foto
    if (isset($_POST['hapus_foto'])) {
        $upload_dir = 'uploads/';
        
        // Hapus file foto jika ada
        if (!empty($_SESSION['foto']) && file_exists($upload_dir . $_SESSION['foto'])) {
            unlink($upload_dir . $_SESSION['foto']);
        }
        
        // Update database
        $stmt = $koneksi->prepare("UPDATE user_login SET foto = NULL WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        
        if ($stmt->execute()) {
            $_SESSION['foto'] = null;
            
            // Generate inisial untuk response
            $initials = '';
            if (!empty($_SESSION['nama_lengkap'])) {
                $parts = explode(' ', $_SESSION['nama_lengkap']);
                foreach ($parts as $p) {
                    if (!empty($p)) {
                        $initials .= strtoupper($p[0]);
                    }
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Foto berhasil dihapus!',
                'initials' => $initials
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus foto dari database!']);
        }
        exit;
    }

    // Handle Update Username dan Password
    // Cek apakah ada data yang akan diupdate
    if (!isset($_POST['username']) && !isset($_POST['password_baru'])) {
        echo json_encode(['success' => false, 'message' => 'Tidak ada data yang akan diupdate!']);
        exit;
    }

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password_baru = isset($_POST['password_baru']) ? $_POST['password_baru'] : '';
    $password_konfirmasi = isset($_POST['password_konfirmasi']) ? $_POST['password_konfirmasi'] : '';

    // Validasi input
    if (empty($username)) {
        echo json_encode(['success' => false, 'message' => 'Username tidak boleh kosong!']);
        exit;
    }

    // Validasi password jika diisi
    if (!empty($password_baru)) {
        if ($password_baru !== $password_konfirmasi) {
            echo json_encode(['success' => false, 'message' => 'Konfirmasi password tidak cocok!']);
            exit;
        }
        
        if (strlen($password_baru) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password minimal 6 karakter!']);
            exit;
        }
    }

    // Cek apakah username sudah digunakan user lain
    $check_username = $koneksi->prepare("SELECT id_user FROM user_login WHERE username = ? AND id_user != ?");
    $check_username->bind_param("si", $username, $id_user);
    $check_username->execute();
    $result = $check_username->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Username sudah digunakan oleh user lain!']);
        exit;
    }

    // Siapkan query update
    $query = "UPDATE user_login SET username = ?";
    $params = [$username];
    $types = "s";

    // Jika password diisi, tambahkan ke query
    if (!empty($password_baru)) {
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
        $query .= ", password = ?";
        $params[] = $hashed_password;
        $types .= "s";
    }

    $query .= " WHERE id_user = ?";
    $params[] = $id_user;
    $types .= "i";

    // Eksekusi query
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        // Update session username
        $_SESSION['username'] = $username;
        
        $message = 'Data berhasil diupdate!';
        if (!empty($password_baru)) {
            $message = 'Username dan password berhasil diupdate!';
        }
        
        echo json_encode(['success' => true, 'message' => $message]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mengupdate data!']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>