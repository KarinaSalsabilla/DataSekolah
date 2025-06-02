<?php
session_start();
header('Content-Type: application/json');

$koneksi = mysqli_connect("localhost", "root", "", "sekolah");

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';


$response = [];

if (!$koneksi) {
    $response['status'] = 'error';
    $response['message'] = 'Koneksi database gagal: ' . mysqli_connect_error();
    echo json_encode($response);
    exit;
}

// "SELECT * FROM user_login WHERE username = ?"

$query = mysqli_prepare($koneksi,  "SELECT * FROM user_login WHERE TRIM(username) = ?"
);
mysqli_stmt_bind_param($query, "s", $username);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'] ?? '';
        $_SESSION['role'] = $user['role'] ?? '';
        $_SESSION['idsiswa'] = $user['idsiswa'] ?? '';
        $_SESSION['foto'] = $user['foto'] ?? '';

        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Password salah!';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Username tidak ditemukan!';
}

echo json_encode($response);
exit;
