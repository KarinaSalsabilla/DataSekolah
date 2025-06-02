<?php
// datajurusan.php - Perbaikan untuk akses halaman
include_once "koneksi.php";

$db = new database();

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
  echo "<script>alert('Silakan login terlebih dahulu!'); window.location.href='index.php';</script>";
  exit;
}

// Ambil role dari session
$role = $_SESSION['role'];

// Cek akses halaman - admin dan siswa boleh mengakses, tapi dengan hak berbeda
if (!in_array($role, ['admin', 'siswa'])) {
  echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location.href='dashboard.php';</script>";
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>DataSekolah | Data Agama</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css">

  <!-- jQuery - PENTING: Harus dimuat sebelum DataTables -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

  <style>
    /* Modern Color Palette */
    :root {
      --primary-gradient: linear-gradient(135deg, rgb(182, 177, 248) 0%, rgb(0, 90, 150) 100%);
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


    .table tbody td:last-child:has(button),
    .table tbody td:last-child:has(a.btn) {
      white-space: nowrap;
      text-align: center !important;
    }

    /* Atau gunakan class-based approach yang lebih reliable: */
    .table tbody td.action-column {
      white-space: nowrap;
      text-align: center !important;
    }

    /* CSS Tema untuk semua role - tidak bergantung pada PHP */
    .table {
      border-radius: 15px;
      overflow: hidden;
      text-align: center;
      font-weight: bold !important;
      /* Tambahkan variabel CSS untuk tema */
      --table-bg: var(--bs-body-bg, #ffffff);
      --table-color: var(--bs-body-color, #212529);
      --table-border: var(--bs-border-color, #dee2e6);
      --table-hover-bg: var(--bs-tertiary-bg, #f8f9fa);
    }

    /* Dark theme support */
    [data-bs-theme="dark"] .table {
      --table-bg: var(--bs-dark, #212529);
      --table-color: var(--bs-light, #f8f9fa);
      --table-border: var(--bs-border-color-translucent, rgba(255, 255, 255, .175));
      --table-hover-bg: var(--bs-gray-800, #343a40);
    }

    .table thead th {
      background: var(--primary-gradient);
      color: white;
      border: none;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-align: center !important;
      vertical-align: middle;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }



    .table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid var(--table-border);
      text-align: left !important;
      background-color: var(--table-bg);
      color: var(--table-color);
    }

    .table tbody tr:hover {
      background: var(--table-hover-bg);
      transform: scale(1.01);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Dark theme hover effect */
    [data-bs-theme="dark"] .table tbody tr:hover {
      background: var(--table-hover-bg);
      box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }

    .table tbody td {
      vertical-align: middle;
      text-align: left !important;
      font-weight: 400;
      word-wrap: break-word;
      overflow-wrap: break-word;
      color: var(--table-color);
      border-bottom-color: var(--table-border);
    }

    .table tbody td:first-child {
      font-weight: 400;
      width: 60px;
      height: 60px;
      text-align: center !important;
    }

    /* Responsive adjustments yang berlaku untuk semua role */
    @media (max-width: 768px) {
      .table {
        font-size: 0.875rem;
      }

      .table thead th {
        padding: 0.75rem 0.25rem;
        font-size: 0.8rem;
      }

      .table tbody td {
        padding: 0.75rem 0.25rem;
        font-size: 0.85rem;
      }

      .table tbody td:first-child {
        width: 15%;
        font-weight: 700;
      }

      .table tbody td:nth-child(2) {
        width: 45%;
      }

      /* Styling untuk kolom aksi di mobile - berlaku untuk semua role */
      .table tbody td:last-child:has(button),
      .table tbody td:last-child:has(a.btn),
      .table tbody td.action-column {
        width: 40%;
      }

      .table .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.25rem;
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
      }

      .table .btn:last-child {
        margin-bottom: 0;
      }

      .table .btn .bi {
        font-size: 0.75rem;
        margin-left: 0.25rem;
      }

      /* Disable hover effects pada mobile untuk performa */
      .table tbody tr:hover {
        transform: none;
        background: var(--table-hover-bg);
      }
    }

    /* Card dan modal theme support */
    .card {
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
      border: 1px solid var(--bs-border-color);
    }

    [data-bs-theme="dark"] .card {
      background-color: var(--bs-dark);
      border-color: var(--bs-border-color-translucent);
    }

    .modal-content {
      background-color: var(--bs-body-bg);
      color: var(--bs-body-color);
    }

    [data-bs-theme="dark"] .modal-content {
      background-color: var(--bs-dark);
    }

    /* DataTables theme support */
    [data-bs-theme="dark"] .dt-container {
      color: var(--bs-light);
    }

    [data-bs-theme="dark"] .dt-search input,
    [data-bs-theme="dark"] .dt-length select {
      background-color: var(--bs-dark);
      border-color: var(--bs-border-color-translucent);
      color: var(--bs-light);
    }

    [data-bs-theme="dark"] .dt-info,
    [data-bs-theme="dark"] .dt-paging .dt-paging-button {
      color: var(--bs-light);
    }

    [data-bs-theme="dark"] .dt-paging .dt-paging-button {
      background-color: var(--bs-dark);
      border-color: var(--bs-border-color-translucent);
    }

    [data-bs-theme="dark"] .dt-paging .dt-paging-button.current {
      background-color: var(--bs-primary);
      border-color: var(--bs-primary);
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
      text-align: center;
      font-style: bold !important;
    }

    .table thead th {
      background: var(--primary-gradient);
      color: white;
      border: none;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-align: center !important;
      vertical-align: middle;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }


    .table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid #f0f0f0;
      text-align: left !important;
    }

    .table tbody tr:hover {
      background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
      transform: scale(1.01);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .table tbody td {
      vertical-align: middle;
      text-align: left !important;
      font-weight: 400;
      word-wrap: break-word;
      overflow-wrap: break-word;

    }

    .table-responsive {
      /* background: white; */
      border-radius: 15px;
      overflow: hidden;
    }

    .table tbody td:first-child {
      font-weight: 400;
      width: 60px;
      height: 60px;
      text-align: center !important;
    }

    <?php if ($role == 'admin'): ?>.table tbody td:last-child {
      white-space: nowrap;
      text-align: center !important;
    }

    <?php endif; ?>.modal-content {
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

    /* Responsif untuk tablet (768px ke bawah) */
    @media (max-width: 768px) {
      .table {
        font-size: 0.875rem;
      }

      .table thead th {
        padding: 0.75rem 0.25rem;
        /* Kurangi padding horizontal */
        font-size: 0.8rem;
      }

      .table tbody td {
        padding: 0.75rem 0.25rem;
        /* Kurangi padding horizontal */
        font-size: 0.85rem;
      }

      /* Kolom nomor urut */
      .table tbody td:first-child {
        width: 15%;
        /* Gunakan persentase */
        font-weight: 700;
      }

      /* Kolom nama agama */
      .table tbody td:nth-child(2) {
        width: 45%;
        /* Sesuaikan lebar */
      }

      /* Kolom aksi */
      .table tbody td:last-child {
        width: 40%;
        /* Sesuaikan lebar */
      }

      /* Tombol dalam tabel - stack vertikal */
      .table .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.25rem;
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
      }

      .table .btn:last-child {
        margin-bottom: 0;
      }

      .table .btn .bi {
        font-size: 0.75rem;
        margin-left: 0.25rem;
      }
    }

    /* Responsif untuk smartphone (576px ke bawah) */
    @media (max-width: 576px) {
      .table {
        font-size: 0.8rem;
      }

      .table thead th {
        padding: 0.5rem 0.125rem;
        font-size: 0.75rem;
      }

      .table tbody td {
        padding: 0.5rem 0.125rem;
        font-size: 0.8rem;
      }

      /* Penyesuaian lebar kolom untuk mobile */
      .table tbody td:first-child {
        width: 12%;
        /* Nomor urut lebih kecil */
      }

      .table tbody td:nth-child(2) {
        width: 48%;
        /* Nama agama */
      }

      .table tbody td:last-child {
        width: 40%;
        /* Aksi */
      }

      /* Tombol aksi untuk mobile */
      .table .btn {
        padding: 0.25rem 0.375rem;
        font-size: 0.7rem;
        margin-bottom: 0.125rem;
      }

      .table .btn .bi {
        font-size: 0.7rem;
      }
    }

    /* Responsif untuk layar sangat kecil (480px ke bawah) */
    @media (max-width: 480px) {
      .table {
        font-size: 0.75rem;
      }

      .table thead th {
        padding: 0.375rem 0.0625rem;
        font-size: 0.7rem;
      }

      .table tbody td {
        padding: 0.375rem 0.0625rem;
        font-size: 0.75rem;
      }

      /* Lebar kolom untuk layar sangat kecil */
      .table tbody td:first-child {
        width: 10%;
      }

      .table tbody td:nth-child(2) {
        width: 50%;
      }

      .table tbody td:last-child {
        width: 40%;
      }

      /* Tombol sangat compact */
      .table .btn {
        padding: 0.125rem 0.25rem;
        font-size: 0.65rem;
        border-radius: 15px;
      }

      .table .btn .bi {
        font-size: 0.65rem;
      }
    }

    /* Disable hover effects pada mobile untuk performa */
    @media (max-width: 768px) {
      .table tbody tr:hover {
        transform: none;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
      }
    }

    /* DataTables responsif adjustments */
    @media (max-width: 768px) {

      .dt-container .dt-length,
      .dt-container .dt-search {
        margin-bottom: 1rem;
      }

      .dt-container .dt-length label,
      .dt-container .dt-search label {
        font-size: 0.875rem;
      }

      .dt-container .dt-length select,
      .dt-container .dt-search input {
        font-size: 0.875rem;
        padding: 0.375rem;
      }

      .dt-container .dt-info,
      .dt-container .dt-paging {
        font-size: 0.875rem;
        margin-top: 1rem;
      }

      .dt-container .dt-paging .dt-paging-button {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
      }
    }
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include "navbar.php"; ?>
    <?php include "sidebar.php"; ?>

    <main class="app-main">
      <div class="app-content-header fade-in">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Data Agama</h3>
            </div>
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
                      Tambah Agama
                    </button>
                  </div>
                <?php endif; ?>

                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table id="example" class="table table-striped">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Agama</th>
                          <?php if ($role == 'admin'): ?>
                            <th></th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $no = 1;
                        foreach ($db->tampildataagama() as $x) {
                        ?>
                          <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $x['nama_agama']; ?></td>
                            <?php if ($role == 'admin'): ?>
                              <td>
                                <button
                                  type="button"
                                  class="btn btn-success mb-2 me-3 btn-edit"
                                  data-bs-toggle="modal"
                                  data-bs-target="#exampleModal2"
                                  data-kode-agama="<?= $x['idagama']; ?>"
                                  data-nama="<?= $x['nama_agama']; ?>">
                                  Edit
                                  <i class="bi bi-pencil-square"></i>
                                </button>

                                <button type="button" class="btn btn-danger mb-2"
                                  data-bs-toggle="modal"
                                  data-bs-target="#confirmDeleteModal"
                                  data-kode-agama="<?= $x['idagama']; ?>"
                                  data-nama="<?= $x['nama_agama']; ?>">
                                  Hapus
                                  <i class="bi bi-trash3-fill"></i>
                                </button>
                              </td>
                            <?php endif; ?>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <?php if ($role == 'admin'): ?>
              <!-- Modal Edit -->
              <div class="modal fade" id="exampleModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <form action="editagama.php" method="POST">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Data Agama</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="edit-nama" class="form-label">Nama Agama</label>
                          <input type="text" class="form-control" id="edit-nama" name="nama" required>
                          <!-- PERBAIKAN: ID unik untuk modal edit -->
                          <input type="hidden" id="edit-kode-agama" name="idagama">
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="edit_agama" class="btn btn-primary">Update Data</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Modal Konfirmasi Hapus -->
              <div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                      <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus Data Agama
                      </h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="alert alert-warning" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Peringatan!</strong> Data berikut akan dihapus secara permanen dan tidak dapat dikembalikan.
                      </div>

                      <div class="card">
                        <div class="card-header">
                          <h6 class="mb-0">Data yang akan dihapus:</h6>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3">
                                <label class="form-label fw-bold">Nama Agama</label>
                                <input type="text" class="form-control-plaintext border rounded px-3 py-2" id="delete-nama" readonly>
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

              <!-- Modal Tambah Agama -->
              <div class="modal fade" id="modalTambahSiswa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalTambahSiswaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalTambahSiswaLabel">Tambah Data Agama</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <?php include 'formAgama.php'; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </main>

    <footer class="app-footer">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="mb-3 mb-md-0">
          <h5 class="mb-1 fw-bold">DataSekolah</h5>
          <p class="mb-0 small">© 2014-2024 DataSekolah • Sistem Informasi Sekolah</p>
          <p class="mb-0 fst-italic small">Mewujudkan Generasi Cerdas dan Berkarakter</p>
        </div>
        <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
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

  <!-- JavaScript Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>

  <!-- OverlayScrollbars -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

  <!-- AdminLTE -->
  <script src="dist/js/adminlte.js"></script>

  <script>
    // OverlayScrollbars Configuration
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

      // Inisialisasi DataTables
      $(document).ready(function() {
        $('#example').DataTable({
          responsive: true,
        });
      });
    });

    // PERBAIKAN: Event handler untuk modal edit dengan ID yang benar
    $(document).on('click', '.btn-edit', function() {
      var kode = $(this).data('kode-agama');
      var nama = $(this).data('nama');

      $('#edit-kode-agama').val(kode); // ID yang sudah diperbaiki
      $('#edit-nama').val(nama); // ID yang sudah diperbaiki
    });

    // JavaScript untuk Modal Hapus
    $('#confirmDeleteModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var kodeAgama = button.data('kode-agama');
      var namaAgama = button.data('nama');

      var modal = $(this);
      modal.find('#delete-nama').val(namaAgama);
      modal.find('#confirmDeleteButton').attr('href', 'hapusagama.php?idagama=' + kodeAgama);
    });
  </script>
</body>

</html>