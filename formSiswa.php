<?php
include_once "koneksi.php";
$db = new database();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validasi server-side - PENTING untuk mencegah data kosong tersimpan
  $errors = [];
  
  // Validasi NISN
  if (empty($_POST['nisn']) || trim($_POST['nisn']) === '') {
    $errors[] = "NISN harus diisi";
  } elseif (strlen($_POST['nisn']) !== 10 || !is_numeric($_POST['nisn'])) {
    $errors[] = "NISN harus 10 digit angka";
  }
  
  // Validasi Nama
  if (empty($_POST['nama']) || trim($_POST['nama']) === '') {
    $errors[] = "Nama harus diisi";
  }
  
  // Validasi Jenis Kelamin
  if (empty($_POST['jeniskelamin']) || trim($_POST['jeniskelamin']) === '') {
    $errors[] = "Jenis kelamin harus dipilih";
  }
  
  // Validasi Jurusan
  if (empty($_POST['kodejurusan']) || trim($_POST['kodejurusan']) === '') {
    $errors[] = "Jurusan harus dipilih";
  }
  
  // Validasi Kelas - INI YANG PENTING
  if (empty($_POST['kelas']) || trim($_POST['kelas']) === '') {
    $errors[] = "Kelas harus dipilih";
  }
  
  // Validasi Alamat
  if (empty($_POST['alamat']) || trim($_POST['alamat']) === '') {
    $errors[] = "Alamat harus diisi";
  }
  
  // Validasi Agama
  if (empty($_POST['agama']) || trim($_POST['agama']) === '') {
    $errors[] = "Agama harus dipilih";
  }
  
  // Validasi No HP - INI YANG PENTING
  if (empty($_POST['nohp']) || trim($_POST['nohp']) === '') {
    $errors[] = "No HP harus diisi";
  } elseif (strlen($_POST['nohp']) < 10 || strlen($_POST['nohp']) > 13 || !is_numeric($_POST['nohp'])) {
    $errors[] = "No HP harus 10-13 digit angka";
  }
  
  // Jika ada error, jangan simpan ke database
  if (!empty($errors)) {
    // Return JSON response untuk AJAX
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'message' => implode(", ", $errors)
    ]);
    exit;
  }
  
  // Jika semua validasi lolos, baru simpan data
  try {
    $db->tambahsiswa(
      $_POST['nisn'],
      $_POST['nama'],
      $_POST['jeniskelamin'],
      $_POST['kodejurusan'],
      $_POST['kelas'],
      $_POST['alamat'],
      $_POST['agama'],
      $_POST['nohp']
    );
    
    // Return success response
    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'message' => 'Data berhasil disimpan'
    ]);
    exit;
  } catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'message' => 'Gagal menyimpan data: ' . $e->getMessage()
    ]);
    exit;
  }
}
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>AdminLTE 4 | General Form Elements</title>
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE 4 | General Form Elements" />
  <meta name="author" content="ColorlibHQ" />
  <meta
    name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta
    name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!--end::Primary Meta Tags-->
  <!--begin::Fonts-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
    crossorigin="anonymous" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
    crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <link rel="stylesheet" href="dist/css/adminlte.css" />
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fef6fb;
    }

    h4,
    .form-label {
      font-family: 'Comic Neue', cursive;
    }

    .form-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
    }

    .form-card {
      background-color:rgb(101, 103, 255);
      border-radius: 20px;
      border: 2px dashed #ffb6c1;
    }

    .card-header {
      background: linear-gradient(to right,rgb(255, 192, 203)), (182, 226, 255);
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .btn-primary {
      background-color:rgb(105, 170, 255);
      border: none;
    }

    .btn-primary:hover {
      background-color:rgb(32, 7, 145);
    }

    input.form-control,
    select.form-select {
      border-radius: 10px;
      border: 1px solid rgb(0, 0, 0);
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
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->

  <form class="needs-validation" id="formTambahSiswa" method="POST" action="formSiswa.php" novalidate>
    <div class="card-body">
      <div class="row g-3">
        <!-- NISN -->
        <div class="col-md-6 mb-4 mt-4">
          <label for="nisn" class="form-label">NISN</label>
          <input
            type="text"
            class="form-control"
            id="nisn"
            name="nisn"
            maxlength="10"
            pattern="[0-9]{1,10}"
            inputmode="numeric"
            placeholder="Masukkan NISN"
            required />
          <div class="invalid-feedback" id="nisn-error">Tolong isi NISN.</div>
          <div class="valid-feedback">Bagus 🤩!</div>
        </div>

        <!-- Nama -->
        <div class="col-md-6 mb-4 mt-4">
          <label for="nama" class="form-label">Nama</label>
          <input
            type="text"
            class="form-control"
            id="nama"
            name="nama"
            placeholder="Masukkan nama lengkap"
            required />
          <div class="invalid-feedback">Tolong isi Nama.</div>
          <div class="valid-feedback">Bagus🤩!</div>
        </div>
      </div>

      <div class="row">
        <!-- Jenis Kelamin -->
        <div class="col-md-6 mb-4">
          <fieldset class="mb-0">
            <legend class="col-form-label pt-1 mb-1" for="jeniskelamin">Jenis Kelamin</legend>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="jeniskelamin" id="perempuan" value="P" required>
              <label class="form-check-label" for="perempuan">
                <ion-icon name="woman-outline"></ion-icon> Perempuan
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="jeniskelamin" id="laki-laki" value="L" required>
              <label class="form-check-label" for="laki-laki">
                <ion-icon name="man-outline"></ion-icon> Laki-Laki
              </label>
            </div>
            <div class="invalid-feedback">Pilih jenis kelamin.</div>
          </fieldset>
        </div>

        <!-- Alamat -->
        <div class="col-md-6 mb-4">
          <label class="form-label" for="alamat">Alamat</label>
          <textarea
            class="form-control"
            id="alamat"
            name="alamat"
            rows="2"
            placeholder="Masukkan alamat lengkap"
            required></textarea>
          <div class="invalid-feedback">Tolong isi alamat.</div>
          <div class="valid-feedback">Bagus 🤩!</div>
        </div>
      </div>

      <div class="row">
        <!-- Jurusan -->
        <div class="col-md-6 mb-4">
          <label for="kodejurusan" class="form-label">Jurusan</label>
          <select class="form-select" id="kodejurusan" name="kodejurusan" required>
            <option selected disabled value="">Pilih Jurusan...</option>
            <?php
            foreach ($db->tampildatajurusan() as $jurusan) {
              echo "<option value='" . $jurusan["kode_jurusan"] . "'>" . $jurusan["nama_jurusan"] . "</option>";
            }
            ?>
          </select>
          <div class="invalid-feedback">Pilih opsi Jurusan.</div>
          <div class="valid-feedback">Bagus🤩!</div>
        </div>

        <!-- Kelas - DIPERBAIKI -->
        <div class="col-md-6 mb-4">
          <label for="kelas" class="form-label">Kelas</label>
          <select class="form-select" id="kelas" name="kelas" required>
            <option selected disabled value="">Pilih Kelas...</option>
            <option value="X">X</option>
            <option value="XI">XI</option>
            <option value="XII">XII</option>
          </select>
          <div class="invalid-feedback">Pilih Opsi kelas.</div>
          <div class="valid-feedback">Bagus🤩!</div>
        </div>
      </div>

      <div class="row">
        <!-- Agama -->
        <div class="col-md-6 mb-4">
          <label for="agama" class="form-label">Agama</label>
          <select class="form-select" id="agama" name="agama" required>
            <option selected disabled value="">Pilih Agama...</option>
            <?php
            foreach ($db->tampildataagama() as $agama) {
              echo "<option value='" . $agama["idagama"] . "'>" . $agama["nama_agama"] . "</option>";
            }
            ?>
          </select>
          <div class="invalid-feedback">Pilih opsi agama.</div>
          <div class="valid-feedback">Bagus🤩!</div>
        </div>

        <!-- No Hp - DIPERBAIKI -->
        <div class="col-md-6 mb-4">
          <label for="nohp" class="form-label">No hp</label>
          <input
            type="text"
            class="form-control"
            id="nohp"
            name="nohp"
            required
            maxlength="13"
            minlength="10"
            pattern="[0-9]{10,13}"
            inputmode="numeric"
            placeholder="Masukkan No Hp"
            oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
          <div class="invalid-feedback">Tolong isi NoHp dengan angka 10-13 digit.</div>
          <div class="valid-feedback">Bagus🤩!</div>
        </div>
      </div>
    </div>

    <div class="card-footer">
      <button type="submit" class="btn btn-primary float-end" id="btnSimpanSiswa" name="simpan">Simpan Data</button>
    </div>
  </form>

  <!-- Modal Alert -->
  <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-3">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="alertModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i> Peringatan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center" id="alertModalMessage">
          <!-- Isi pesan akan diisi lewat JavaScript -->
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Success -->
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-3">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle-fill me-2"></i> Berhasil</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center" id="successModalMessage">
          Data berhasil disimpan!
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-success" onclick="window.location.href='datasiswa.php'">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const nisnInput = document.getElementById('nisn');
    const errorFeedback = document.getElementById('nisn-error');

    nisnInput.addEventListener('input', function() {
      // Hapus semua karakter yang bukan angka
      this.value = this.value.replace(/\D/g, '');

      if (this.value.length !== 10) {
        nisnInput.classList.add('is-invalid');
        nisnInput.classList.remove('is-valid');
        errorFeedback.textContent = "NISN harus terdiri dari 10 digit angka.";
      } else {
        nisnInput.classList.remove('is-invalid');
        nisnInput.classList.add('is-valid');
      }
    });
  </script>

  <script>
    // Bootstrap 5 form validation script
    (function() {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
          // Prevent default submission
          event.preventDefault()
          event.stopPropagation()
          
          // Manual validation untuk semua field
          let isValid = true;
          let errors = [];
          
          // Cek NISN
          const nisn = document.getElementById('nisn').value.trim();
          if (!nisn || nisn.length !== 10) {
            isValid = false;
            errors.push('NISN harus diisi dengan 10 digit angka');
          }
          
          // Cek Nama
          const nama = document.getElementById('nama').value.trim();
          if (!nama) {
            isValid = false;
            errors.push('Nama harus diisi');
          }
          
          // Cek Jenis Kelamin
          const jeniskelamin = document.querySelector('input[name="jeniskelamin"]:checked');
          if (!jeniskelamin) {
            isValid = false;
            errors.push('Jenis kelamin harus dipilih');
          }
          
          // Cek Alamat
          const alamat = document.getElementById('alamat').value.trim();
          if (!alamat) {
            isValid = false;
            errors.push('Alamat harus diisi');
          }
          
          // Cek Jurusan
          const kodejurusan = document.getElementById('kodejurusan').value;
          if (!kodejurusan) {
            isValid = false;
            errors.push('Jurusan harus dipilih');
          }
          
          // Cek Kelas - PENTING
          const kelas = document.getElementById('kelas').value;
          if (!kelas) {
            isValid = false;
            errors.push('Kelas harus dipilih');
          }
          
          // Cek Agama
          const agama = document.getElementById('agama').value;
          if (!agama) {
            isValid = false;
            errors.push('Agama harus dipilih');
          }
          
          // Cek No HP - PENTING
          const nohp = document.getElementById('nohp').value.trim();
          if (!nohp) {
            isValid = false;
            errors.push('No HP harus diisi');
          } else if (nohp.length < 10 || nohp.length > 13) {
            isValid = false;
            errors.push('No HP harus 10-13 digit angka');
          }
          
          form.classList.add('was-validated')
          
          if (!isValid) {
            document.getElementById("alertModalMessage").innerText = errors.join(', ');
            new bootstrap.Modal(document.getElementById('alertModal')).show();
            return false;
          }
          
          // Jika semua valid, submit form
          submitForm();
          
        }, false)
      })
    })()
    
    // Function untuk submit form dengan AJAX
    function submitForm() {
      const form = document.getElementById("formTambahSiswa");
      const formData = new FormData(form);
      
      // Disable button saat proses
      const btnSubmit = document.getElementById('btnSimpanSiswa');
      btnSubmit.disabled = true;
      btnSubmit.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';

      fetch('formSiswa.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Tampilkan modal success
            document.getElementById("successModalMessage").innerText = data.message;
            new bootstrap.Modal(document.getElementById('successModal')).show();
            
            // Reset form
            form.reset();
            form.classList.remove('was-validated');
          } else {
            // Tampilkan modal error
            document.getElementById("alertModalMessage").innerText = data.message;
            new bootstrap.Modal(document.getElementById('alertModal')).show();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById("alertModalMessage").innerText = "Terjadi kesalahan saat mengirim data.";
          new bootstrap.Modal(document.getElementById('alertModal')).show();
        })
        .finally(() => {
          // Enable button kembali
          btnSubmit.disabled = false;
          btnSubmit.innerHTML = 'Simpan Data';
        });
    }
  </script>

</body>
<!--end::Body-->

</html>