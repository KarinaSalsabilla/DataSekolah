<?php
include_once "koneksi.php";
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validasi di sisi server
  if (empty(trim($_POST['nama']))) {
    header("Location: formAgama.php?error=nama_kosong");
    exit;
  }

  $db->tambahagama($_POST['nama']);
  header("Location: datagama.php?status=sukses");
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE 4 | General Form Elements</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS links yang sama seperti sebelumnya -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Custom CSS untuk centering -->
  <style>
    body {
      font-family: 'Comic Neue', cursive;
      background-color: #ffeef8;
    }

    .form-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
    }

    /* .form-card {
      background-color: #fff0f5;
      border-radius: 20px;
      border: 2px dashed #ffb6c1;
    } */

    .card-header {
      /* background: linear-gradient(to right, #ffb6c1, #ffc0cb); */
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    /* .form-label {
      color: #d63384;
      font-weight: bold;
    } */

    .btn-primary {
      background-color: rgb(105, 170, 255);
      border: none;
    }

    .btn-primary:hover {
      background-color: rgb(32, 7, 145);
    }

    input.form-control,
    select.form-select {
      border-radius: 10px;
      
    }

    .valid-feedback,
    .invalid-feedback {
      font-size: 0.9rem;
    }

    #preview {
      border-radius: 10px;
      margin: 10px auto;
      display: block;
    }
  </style>
</head>

<body class="bg-body-tertiary">

  <!-- Container untuk center form -->
  <div class="form-container">
    <div class="card form-card">
      <div class="card-header bg-primary text-white text-center">
        <h4 class="mb-0">Tambah Data Agama</h4>
      </div>

      <!-- Form -->
      <form class="needs-validation" id="formTambahAgama" method="POST" action="formAgama.php" novalidate>
        <div class="card-body">
          <div class="mb-4">
            <label for="nama" class="form-label">Nama Agama</label>
            <input
              type="text"
              class="form-control"
              id="nama"
              name="nama"
              placeholder="Masukkan nama Agama"
              required />
            <div class="invalid-feedback">Tolong isi data Agama.</div>
            <div class="valid-feedback">Bagus🤩</div>
          </div>
          <input type="hidden" id="modal-kode-Agama" name="idagama">
        </div>

        <div class="card-footer text-center">
          <button type="submit" class="btn btn-primary px-4" id="btnSimpanAgama" name="simpan">
            <i class="bi bi-save"></i> Simpan Data
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Script yang sudah diperbaiki -->
  <script>
    (() => {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach((form) => {
        form.addEventListener('submit', (event) => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();

    document.getElementById("formTambahAgama").addEventListener("submit", function(e) {
      e.preventDefault();

      if (!this.checkValidity()) {
        this.classList.add('was-validated');
        return false;
      }

      const namaInput = document.getElementById('nama');
      if (!namaInput.value.trim()) {
        alert("Nama Agama tidak boleh kosong!");
        namaInput.focus();
        return false;
      }

      const formData = new FormData(this);

      fetch('formAgama.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (response.redirected) {
            window.location.href = response.url;
          } else {
            alert("Gagal menyimpan data.");
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert("Terjadi kesalahan.");
        });
    });
  </script>

</body>

</html>