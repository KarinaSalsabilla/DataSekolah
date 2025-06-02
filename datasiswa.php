<?php
// datasisw.php - Perbaikan untuk DataTables role siswa
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

// Cek akses halaman
if (!in_array($role, ['admin', 'siswa'])) {
  echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location.href='dashboard.php';</script>";
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>DataSekolah | Data Siswa</title>
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
      --primary-gradient: linear-gradient(135deg, rgb(61, 72, 121) 0%, rgb(94, 145, 204) 100%);
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
      background: white;
      text-align: left;
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

    }

    .table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid #f0f0f0;
      padding: 1rem;
      text-align: center !important;
    }

    .table tbody tr:hover {
      background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
      transform: scale(1.01);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }


    .table tbody td {
      text-align: left !important;
      font-weight: 400;
      padding: 1rem;
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

    .table tbody td:first-child {
      font-weight: 400;
      width: 60px;
      height: 60px;
      text-align: center !important;
    }

    .table tbody td:last-child {
      white-space: nowrap;
      text-align: center !important;
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

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include "navbar.php"; ?>
    <?php include "sidebar.php"; ?>

    <main class="app-main">
      <div class="app-content-header fade-in">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Data Siswa</h3>
            </div>
            <!-- <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Siswa</li>
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
                      Tambah Siswa
                    </button>
                  </div>
                <?php endif; ?>

                <div class="card-body p-0">
                  <!-- Pastikan tabel memiliki ID yang konsisten -->
                  <div class="table-responsive">
                    <table id="dataSiswaTable" class="table table-striped" style="width:100%">
                      <thead>
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
                          <?php if ($role == 'admin'): ?>
                            <th> </th>
                          <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $no = 1;
                        $dataSiswa = $db->tampilkodejurusan();
                        if (!empty($dataSiswa)) { // Gunakan !empty agar tidak error saat data kosong
                          foreach ($dataSiswa as $x) {
                        ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= htmlspecialchars($x['NISN'] ?? ''); ?></td>
                              <td><?= htmlspecialchars($x['Nama'] ?? ''); ?></td>
                              <td><?= ($x['Jenis Kelamin'] ?? '') == "L" ? 'Laki-Laki' : 'Perempuan'; ?></td>
                              <td><?= htmlspecialchars($x['Jurusan'] ?? '-'); ?></td>
                              <td><?= htmlspecialchars($x['Kelas'] ?? ''); ?></td>
                              <td><?= htmlspecialchars($x['Alamat'] ?? ''); ?></td>
                              <td><?= htmlspecialchars($x['agama'] ?? '-'); ?></td>
                              <td><?= htmlspecialchars($x['No Hp'] ?? ''); ?></td>

                              <?php if ($role == 'admin'): ?>
                                <td>
                                  <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success btn-sm btn-edit" style="margin-right: 5px;" ;
                                      data-bs-toggle="modal"
                                      data-bs-target="#exampleModal"
                                      data-nisn="<?= htmlspecialchars($x['NISN'] ?? ''); ?>"
                                      data-nama="<?= htmlspecialchars($x['Nama'] ?? ''); ?>"
                                      data-jk="<?= htmlspecialchars($x['Jenis Kelamin'] ?? ''); ?>"
                                      data-jurusan="<?= htmlspecialchars($x['kode_jurusan'] ?? ''); ?>"
                                      data-kelas="<?= htmlspecialchars($x['Kelas'] ?? ''); ?>"
                                      data-alamat="<?= htmlspecialchars($x['Alamat'] ?? ''); ?>"
                                      data-agama="<?= htmlspecialchars($x['idagama'] ?? ''); ?>"
                                      data-nohp="<?= htmlspecialchars($x['No Hp'] ?? ''); ?>">
                                      <i class="bi bi-pencil-square"></i>
                                      Edit
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm"
                                      data-bs-toggle="modal"
                                      data-bs-target="#confirmDeleteModal"
                                      data-nisn="<?= htmlspecialchars($x['NISN'] ?? ''); ?>"
                                      data-nama="<?= htmlspecialchars($x['Nama'] ?? ''); ?>"
                                      data-jk="<?= htmlspecialchars($x['Jenis Kelamin'] ?? ''); ?>"
                                      data-jurusan="<?= htmlspecialchars($x['Jurusan'] ?? '-'); ?>"
                                      data-kelas="<?= htmlspecialchars($x['Kelas'] ?? ''); ?>"
                                      data-alamat="<?= htmlspecialchars($x['Alamat'] ?? ''); ?>"
                                      data-agama="<?= htmlspecialchars($x['agama'] ?? '-'); ?>"
                                      data-nohp="<?= htmlspecialchars($x['No Hp'] ?? ''); ?>">
                                      <i class="bi bi-trash3-fill"></i>
                                      Hapus
                                    </button>
                                  </div>
                                </td>
                              <?php endif; ?>
                            </tr>
                          <?php
                          }
                        } else {
                          ?>
                          <tr>
                            <td colspan="<?= $role == 'admin' ? '10' : '9' ?>" class="text-center">Tidak ada data siswa</td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal Edit -->
            <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <form action="editsiswa.php" method="POST">
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Data Siswa</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <!-- Form edit siswa content -->
                      <div class="mb-3">
                        <label for="modal-nisn" class="form-label ">NISN</label>
                        <input type="text" class="form-control" id="modal-nisn" name="nisn" maxlength="10" oninput="formatNISN(this)" required>
                      </div>
                      <div class="mb-3">
                        <label for="modal-nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="modal-nama" name="nama" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label><br>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="jeniskelamin" id="jk-p" value="P">
                          <label class="form-check-label" for="jk-p">Perempuan</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="jeniskelamin" id="jk-l" value="L">
                          <label class="form-check-label" for="jk-l">Laki-Laki</label>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label for="modal-alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="modal-alamat" name="alamat" required>
                      </div>
                      <div class="mb-3">
                        <label for="modal-jurusan" class="form-label">Jurusan</label>
                        <select class="form-select" id="modal-jurusan" name="kodejurusan" required>
                          <option disabled selected>Pilih Jurusan...</option>
                          <?php foreach ($db->tampildatajurusan() as $jurusan): ?>
                            <option value="<?= $jurusan['kode_jurusan'] ?>"><?= $jurusan['nama_jurusan'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="modal-kelas" class="form-label">Kelas</label>
                        <select class="form-select" id="modal-kelas" name="kelas" required>
                          <option disabled selected>Pilih...</option>
                          <option>X</option>
                          <option>XI</option>
                          <option>XII</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="modal-agama" class="form-label">Agama</label>
                        <select class="form-select" id="modal-agama" name="agama" required>
                          <option disabled selected>Pilih...</option>
                          <?php foreach ($db->tampildataagama() as $agama): ?>
                            <option value="<?= $agama['idagama'] ?>"><?= $agama['nama_agama'] ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="modal-nohp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="modal-nohp" name="nohp" maxlength="13" pattern="[0-9]{10,13}" inputmode="numeric" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>


            <div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                      <i class="bi bi-exclamation-triangle-fill"></i> Konfirmasi Hapus Data Siswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="alert alert-warning">
                      <i class="bi bi-exclamation-triangle-fill"></i>
                      <strong>Peringatan!</strong> Data berikut akan dihapus secara permanen.
                    </div>

                    <div class="card">
                      <div class="card-header">
                        <h6 class="mb-0">Data yang akan dihapus:</h6>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="form-label fw-bold">NISN</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-nisn" readonly>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">Nama</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-nama" readonly>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">Jenis Kelamin</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-jk" readonly>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">Alamat</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-alamat" readonly>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="form-label fw-bold">Jurusan</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-jurusan" readonly>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">Kelas</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-kelas" readonly>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">Agama</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2 " id="delete-agama" readonly>
                            </div>
                            <div class="mb-3">
                              <label class="form-label fw-bold">No HP</label>
                              <input type="text" class="form-control-plaintext border rounded px-3 py-2" id="delete-nohp" readonly>
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

            <!-- Modal untuk Admin -->
            <?php if ($role == 'admin'): ?>
              <!-- Modal Tambah Siswa -->
              <div class="modal fade" id="modalTambahSiswa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Tambah Data Siswa</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <?php include 'formSiswa.php'; ?>
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

  <!-- JavaScript Libraries -->
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

      var hasData = <?= !empty($dataSiswa) ? 'true' : 'false'; ?>;
      // Inisialisasi DataTables - PENTING: Pastikan DOM sudah ready
      $(document).ready(function() {
        if (hasData) {
          $('#dataSiswaTable').DataTable({
            responsive: true,
            language: {
              emptyTable: "Tidak ada data siswa"
            }
            // opsi DataTables lain kalau perlu
          });
        } else {
          // DataTables tidak diinisialisasi, tabel tampil biasa tanpa fungsi datatables
        }
      });
    });



    <?php if ($role == 'admin'): ?>
      // JavaScript untuk Modal Edit
      $(document).ready(function() {
        $('#exampleModal').on('show.bs.modal', function(event) {
          var button = $(event.relatedTarget);
          var modal = $(this);

          modal.find('#modal-nisn').val(button.data('nisn'));
          modal.find('#modal-nama').val(button.data('nama'));
          modal.find('input[name="jeniskelamin"][value="' + button.data('jk') + '"]').prop('checked', true);
          modal.find('#modal-jurusan').val(button.data('jurusan'));
          modal.find('#modal-kelas').val(button.data('kelas'));
          modal.find('#modal-alamat').val(button.data('alamat'));
          modal.find('#modal-agama').val(button.data('agama'));
          modal.find('#modal-nohp').val(button.data('nohp'));
        });

        // JavaScript untuk Modal Hapus
        var deleteModal = document.getElementById('confirmDeleteModal');
        if (deleteModal) {
          deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            document.getElementById('delete-nisn').value = button.getAttribute('data-nisn');
            document.getElementById('delete-nama').value = button.getAttribute('data-nama');
            document.getElementById('delete-jk').value = button.getAttribute('data-jk') === 'L' ? 'Laki-Laki' : 'Perempuan';
            document.getElementById('delete-jurusan').value = button.getAttribute('data-jurusan');
            document.getElementById('delete-kelas').value = button.getAttribute('data-kelas');
            document.getElementById('delete-alamat').value = button.getAttribute('data-alamat');
            document.getElementById('delete-agama').value = button.getAttribute('data-agama');
            document.getElementById('delete-nohp').value = button.getAttribute('data-nohp');

            var confirmButton = deleteModal.querySelector('#confirmDeleteButton');
            confirmButton.href = 'hapussiswa.php?NISN=' + button.getAttribute('data-nisn');
          });
        }
      });
    <?php endif; ?>
  </script>

  <script>
  function formatNISN(input) {
    let val = input.value.replace(/\D/g, ''); // hapus selain angka
    val = val.padStart(2, '0');               // tambah nol depan sampai 3 digit
    input.value = val;
  }
</script>
</body>

</html>