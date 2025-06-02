<?php
// datauser.php - Perbaikan dengan error handling dan debugging
include_once "koneksi.php";

// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $db = new database();
} catch (Exception $e) {
    die("Error koneksi database: " . $e->getMessage());
}

session_start();

// Debug session
if (empty($_SESSION)) {
    echo "<script>console.log('Session kosong');</script>";
}

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='index.php';</script>";
    exit;
}

// Ambil role dari session
$role = $_SESSION['role'] ?? '';

// Debug role
echo "<script>console.log('Role user: " . $role . "');</script>";

// Cek akses halaman - admin dan siswa boleh mengakses, tapi dengan hak berbeda
if (!in_array($role, ['admin', 'siswa'])) {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini! Role: $role'); window.location.href='dashboard.php';</script>";
    exit;
}

// Cek apakah method tampildatauser() exists
if (!method_exists($db, 'tampildatauser')) {
    die("Error: Method tampildatauser() tidak ditemukan dalam class database");
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DataSekolah | Data User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />

    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.css" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">

    <!-- jQuery - PENTING: Harus dimuat sebelum DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <style>
        /* Modern Color Palette */
        :root {
            --primary-gradient: linear-gradient(135deg, rgb(79, 120, 255) 0%, rgb(200, 190, 255) 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgba(255, 255, 255, 0.68) 100%);
            --card-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            --card-shadow-hover: 0 20px 40px rgba(50, 50, 93, 0.15), 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Background Enhancement */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        

        .app-content-header {
            background: var(--primary-gradient);
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .app-content-header h3 {
            color: white;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin: 0;
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: white;
            font-weight: 600;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-5px);
        }


        .btn {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }


        .btn-success {
            background: var(--success-gradient);
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.6);
        }

        .btn-danger {
            background: red;
            box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(250, 112, 154, 0.6);
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
            background: white;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 100;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center !important;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table tbody td {
            vertical-align: middle;
            text-align: left;
            font-weight: 400;
            padding: 1rem;
        }

        .table tbody td:first-child {
            font-weight: 400 !important;
            width: 60px;
            height: 60px;
            text-align: center !important;
        }

        .table tbody td:last-child {
            white-space: nowrap;
            text-align: center !important;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 2rem;
        }

        .modal-header.bg-danger {
            background: red;
        }

        .modal-title {
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e3f2fd;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }


        .alert {
            border-radius: 15px;
            border: none;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }


        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .card-body {
            padding: 0;
            border-radius: 0 0 20px 20px;
        }

        /* Number Badge */
        .table tbody td:first-child {
            font-weight: 700;
            width: 60px;
            height: 60px;
            text-align: center;
        }

        /* Action Buttons Container */
        .table tbody td:last-child {
            white-space: nowrap;
        }

        /* Icon Enhancements */
        .bi {
            margin-left: 0.5rem;
            transition: transform 0.3s ease;
        }

        .btn:hover .bi {
            transform: scale(1.1);
        }
    </style>
</head>

<?php if ($role == 'admin'): ?>

    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

        <div class="app-wrapper">

            <?php include "navbar.php"; ?>

            <?php include "sidebar.php"; ?>

            <main class="app-main">
                <div class="app-content-header fade-in">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="mb-0">Data User</h3>
                            </div>
                            <!-- <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Data User</li>
                                </ol>
                            </div> -->
                        </div>
                    </div>
                </div>

                <div class="app-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4 slide-in">
                                    <?php if ($role == 'admin'): ?>
                                        <div class="card-header">
                                            <button type="button" class="btn btn-primary me-auto" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                                                <i class="bi bi-plus-circle-fill"></i>
                                                Tambah User
                                            </button>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table id="example" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Username</th>
                                                        <th>Password</th>
                                                        <th>Nama Lengkap</th>
                                                        <th>Role</th>
                                                        <th>Foto</th>
                                                        <?php if ($role == 'admin'): ?>
                                                            <th></th>
                                                        <?php endif; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    try {
                                                        $userData = $db->tampildatauser();

                                                        if (empty($userData)) {
                                                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data user</td></tr>";
                                                        } else {
                                                            $no = 1;
                                                            foreach ($userData as $x) {
                                                                // Validasi data sebelum ditampilkan
                                                                $username = htmlspecialchars($x['username'] ?? 'N/A');
                                                                $password_display = isset($x['password']) ? substr($x['password'], 0, 10) . '...' : 'N/A';
                                                                $nama_lengkap = htmlspecialchars($x['nama_lengkap'] ?? 'N/A');
                                                                $role_user = htmlspecialchars($x['role'] ?? 'N/A');
                                                                $foto = $x['foto'] ?? '';
                                                                $id_user = $x['id_user'] ?? 0;
                                                    ?>
                                                                <tr>
                                                                    <td><?= $no++; ?></td>
                                                                    <td><?= $username; ?></td>
                                                                    <td><?= $password_display; ?></td>
                                                                    <td><?= $nama_lengkap; ?></td>
                                                                    <td><?= $role_user; ?></td>
                                                                    <td>
                                                                        <?php if (!empty($foto) && file_exists("uploads/$foto")): ?>
                                                                            <img src="uploads/<?= htmlspecialchars($foto); ?>"
                                                                                class="profile-circle img-thumbnail"
                                                                                alt="Foto"
                                                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; display: block; margin: 0 auto; flex-shrink: 0;" />
                                                                        <?php else: ?>
                                                                            <div class="d-flex justify-content-center align-items-center"
                                                                                style="width: 80px; height: 80px; border-radius: 50%; background-color: #f8f9fa; margin: 0 auto;">
                                                                                <i class="bi bi-person-circle text-muted" style="font-size: 2rem;"></i>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <?php if ($role == 'admin'): ?>
                                                                        <td>
                                                                            <button
                                                                                type="button"
                                                                                class="btn btn-success mb-2 me-3 btn-edit"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalEditUser"
                                                                                data-id="<?= $id_user; ?>"
                                                                                data-username="<?= $username; ?>"
                                                                                data-nama="<?= $nama_lengkap; ?>"
                                                                                data-role="<?= $role_user; ?>"
                                                                                data-foto="<?= htmlspecialchars($foto); ?>">
                                                                                Edit <i class="bi bi-pencil-square"></i>
                                                                            </button>

                                                                            <button
                                                                                type="button"
                                                                                class="btn btn-danger mb-2 btn-delete"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#confirmDeleteModal"
                                                                                data-id="<?= $id_user; ?>"
                                                                                data-username="<?= $username; ?>"
                                                                                data-nama="<?= $nama_lengkap; ?>"
                                                                                data-role="<?= $role_user; ?>"
                                                                                data-foto="<?= htmlspecialchars($foto); ?>"
                                                                                title="Hapus data <?= $nama_lengkap; ?>">
                                                                                Hapus <i class="bi bi-trash3-fill"></i>
                                                                            </button>
                                                                        </td>
                                                                    <?php endif; ?>
                                                                </tr>
                                                    <?php
                                                            }
                                                        }
                                                    } catch (Exception $e) {
                                                        echo "<tr><td colspan='7' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- MODAL EDIT USER -->
                            <div class="modal fade" id="modalEditUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <form action="edituser.php" method="POST" enctype="multipart/form-data" id="editUserForm">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-pencil-square"></i> Edit User
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div id="editUserMessage"></div>

                                                <input type="hidden" name="id_user" id="edit-id-user">

                                                <div class="mb-3">
                                                    <label for="edit-username-display" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="edit-username-display" readonly>
                                                    <small class="text-muted">Username tidak dapat diubah</small>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-nama-lengkap" class="form-label">Nama Lengkap</label>
                                                    <input type="text" class="form-control" name="nama_lengkap" id="edit-nama-lengkap" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" name="password" id="edit-password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit-role" class="form-label">Role</label>
                                                    <select name="role" class="form-select" id="edit-role" required>
                                                        <option value="">Pilih Role</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="siswa">Siswa</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Foto Saat Ini</label>
                                                    <div id="current-foto-preview" class="mt-2"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x-circle"></i> Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary" id="saveEditUser">
                                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus Data User
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle-fill"></i>
                                                <strong>Peringatan!</strong> Data berikut akan dihapus secara permanen dan tidak dapat dikembalikan.
                                            </div>

                                            <div class="card">
                                                <div class="card-header ">
                                                    <h6 class="mb-0">Data yang akan dihapus:</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Username</label>
                                                                <div class="form-control-plaintext border rounded px-3 py-2" id="delete-username-display"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Role</label>
                                                                <div class="form-control-plaintext border rounded px-3 py-2 " id="delete-role-display"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Nama Lengkap</label>
                                                                <div class="form-control-plaintext border rounded px-3 py-2 " id="delete-nama-display"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Foto</label>
                                                                <div id="delete-foto-preview" class="border rounded p-3  text-center"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="bi bi-x-circle"></i> Batal
                                            </button>
                                            <a href="#" class="btn btn-danger" id="confirmDeleteButton">
                                                <i class="bi bi-trash3-fill"></i> Ya, Hapus Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tambah User -->
                            <div class="modal fade" id="modalTambahSiswa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-plus-circle-fill"></i> Tambah Data User
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            if (file_exists('formuser.php')) {
                                                include 'formuser.php';
                                            } else {
                                                echo "<div class='alert alert-danger'>File formuser.php tidak ditemukan</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </main>

            <footer class="app-footer">
                <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">

                    <!-- Kiri: Info Sekolah + Hak Cipta -->
                    <div class="mb-3 mb-md-0">
                        <h5 class="mb-1 fw-bold">DataSekolah</h5>
                        <p class="mb-0 small">
                            © 2014-2024 DataSekolah • Sistem Informasi Sekolah
                        </p>
                        <p class="mb-0 fst-italic small">
                            Mewujudkan Generasi Cerdas dan Berkarakter
                        </p>
                    </div>

                    <!-- Kanan: Kontak dan Sosial Media -->
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                        <!-- Contoh Sosial Media -->
                        <a href="https://instagram.com/smkn6solo" target="_blank">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="https://facebook.com/smkn6surakarta" target="_blank">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="https://youtube.com/channel/UC0SHniA81CSTIAMseqD7t-Q/channels" target="_blank">
                            <i class="bi bi-youtube fs-5"></i>
                        </a>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Scripts - PENTING: Urutan loading harus benar -->
        <!-- jQuery harus dimuat pertama -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Bootstrap Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>

        <!-- OverlayScrollbars -->
        <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

        <!-- AdminLTE -->
        <script src="dist/js/adminlte.js"></script>

        <script>
            const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
            const Default = {
                scrollbarTheme: 'os-theme-light',
                scrollbarAutoHide: 'leave',
                scrollbarClickScroll: true,
            };

            document.addEventListener('DOMContentLoaded', function() {
                const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
                if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                    OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                        scrollbars: {
                            theme: Default.scrollbarTheme,
                            autoHide: Default.scrollbarAutoHide,
                            clickScroll: Default.scrollbarClickScroll,
                        },
                    });
                }


                $(document).ready(function() {
                    $('#example').DataTable({
                        responsive: true
                    });
                });
            });

            
            // Handler untuk tombol edit
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                var username = $(this).data('username');
                var nama = $(this).data('nama');
                var role = $(this).data('role');
                var foto = $(this).data('foto');
                var password_hash = $(this).data('password');



                $('#edit-id-user').val(id);
                $('#edit-username-display').val(username);
                $('#edit-nama-lengkap').val(nama);
                $('#edit-role').val(role);


                // Tampilkan indikasi password di modal edit
                if (password_hash && password_hash !== '') {
                    $('#edit-password').attr('placeholder', 'Password tersimpan (terenkripsi)');
                } else {
                    $('#edit-password').attr('placeholder', 'Kosongkan jika tidak ingin mengubah password');
                }

                var fotoPreview = '';
                if (foto && foto !== '' && foto !== null) {
                    fotoPreview = '<div class="text-center">' +
                        '<img src="uploads/' + foto + '" alt="Foto Current" class="img-thumbnail mb-2" style="max-width: 150px;">' +
                        '<div><small class="text-muted">Foto saat ini: ' + foto + '</small></div>' +
                        '</div>';
                } else {
                    fotoPreview = '<div class="text-center"><small class="text-muted">Tidak ada foto saat ini</small></div>';
                }
                $('#current-foto-preview').html(fotoPreview);
            });

            // Handler untuk tombol hapus
            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                var username = $(this).data('username');
                var namaLengkap = $(this).data('nama');
                var role = $(this).data('role');
                var foto = $(this).data('foto');

                var password_hash = $(this).data('password');

                $('#delete-username-display').text(username);
                $('#delete-nama-display').text(namaLengkap);
                $('#delete-role-display').text(role.charAt(0).toUpperCase() + role.slice(1));

                // var passwordDisplay = password_hash && password_hash !== '' ? 'Password: Tersimpan (terenkripsi)' : 'Password: -';
                // $('#delete-nama-display').after('<div class="mb-3"><label class="form-label fw-bold">Password</label><div class="form-control-plaintext border rounded px-3 py-2 bg-light">' + passwordDisplay + '</div></div>');


                var fotoPreview = '';
                if (foto && foto !== '' && foto !== null) {
                    fotoPreview = '<img src="uploads/' + foto + '" alt="Foto" class="img-thumbnail mb-2" style="max-width: 120px;">' +
                        '<div><small class="text-muted">' + foto + '</small></div>';
                } else {
                    fotoPreview = '<small class="text-muted">Tidak ada foto</small>';
                }
                $('#delete-foto-preview').html(fotoPreview);

                $('#confirmDeleteButton').attr('href', 'hapususer.php?id_user=' + id);
            });

            // Reset modal saat ditutup
            $('#modalEditUser').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('#current-foto-preview').html('');
                $('#editUserMessage').empty();
            });

            $('#confirmDeleteModal').on('hidden.bs.modal', function() {
                $('#delete-username-display').text('');
                $('#delete-nama-display').text('');
                $('#delete-role-display').text('');
                $('#delete-foto-preview').html('');
                $('#confirmDeleteButton').attr('href', '#');
            });

            // Handler untuk form edit user dengan AJAX
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: 'edituser.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#modalEditUser').modal('hide');
                            location.reload(); // Refresh halaman untuk update data
                        } else {
                            $('#editUserMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#editUserMessage').html('<div class="alert alert-danger">Terjadi kesalahan saat mengupdate data.</div>');
                    }
                });
            });
        </script>
    </body>
<?php endif; ?>

</html>