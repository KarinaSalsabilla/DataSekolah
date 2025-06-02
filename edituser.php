<?php
include_once "koneksi.php";

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

try {
    $db = new Database();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_user = isset($_POST['id_user']) ? (int)$_POST['id_user'] : 0;
        $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($id_user <= 0) {
            throw new Exception("ID User tidak valid");
        }

        if (empty($nama_lengkap)) {
            throw new Exception("Nama lengkap tidak boleh kosong");
        }

        if (empty($role)) {
            throw new Exception("Role tidak boleh kosong");
        }

        if (!in_array($role, ['admin', 'siswa'])) {
            throw new Exception("Role harus admin atau siswa");
        }

        // Cek user
        $checkQuery = "SELECT username FROM user_login WHERE id_user = ?";
        $checkStmt = $db->koneksi->prepare($checkQuery);
        $checkStmt->bind_param("i", $id_user);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) {
            throw new Exception("User tidak ditemukan");
        }

        // Update query
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE user_login SET password = ?, nama_lengkap = ?, role = ? WHERE id_user = ?";
            $stmt = $db->koneksi->prepare($query);
            $stmt->bind_param("sssi", $hashedPassword, $nama_lengkap, $role, $id_user);
        } else {
            $query = "UPDATE user_login SET nama_lengkap = ?, role = ? WHERE id_user = ?";
            $stmt = $db->koneksi->prepare($query);
            $stmt->bind_param("ssi", $nama_lengkap, $role, $id_user);
        }

        $result = $stmt->execute();

        if ($result) {
            // Perbarui session jika user yang diedit adalah user login
            if (isset($_SESSION['username'])) {
                $updatedQuery = "SELECT username, nama_lengkap, role FROM user_login WHERE id_user = ?";
                $stmt2 = $db->koneksi->prepare($updatedQuery);
                $stmt2->bind_param("i", $id_user);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $updatedUser = $result2->fetch_assoc();

                if ($updatedUser && $_SESSION['username'] == $updatedUser['username']) {
                    $_SESSION['nama_lengkap'] = $updatedUser['nama_lengkap'];
                    $_SESSION['role'] = $updatedUser['role'];
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            throw new Exception("Gagal mengupdate data");
        }
    } else {
        throw new Exception("Metode tidak diizinkan");
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
