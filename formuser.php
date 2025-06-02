<?php
include_once "koneksi.php";
$db = new database();

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty(trim($_POST['username'])) || empty(trim($_POST['password'])) || empty(trim($_POST['namalengkap'])) || empty($_POST['role'])) {
        $response['message'] = "Semua field wajib diisi!";
        echo json_encode($response);
        exit;
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $namalengkap = $_POST['namalengkap'];
    $role = $_POST['role'];

    $fotoName = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoName = uniqid() . '_' . $_FILES['foto']['name'];
        $targetPath = 'uploads/' . $fotoName;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['foto']['type'];

        if (in_array($fileType, $allowedTypes)) {
            if (!move_uploaded_file($fotoTmp, $targetPath)) {
                $response['message'] = "Gagal upload foto!";
                echo json_encode($response);
                exit;
            }
        } else {
            $response['message'] = "Format foto tidak valid!";
            echo json_encode($response);
            exit;
        }
    }

    $db->tambahuser($username, $password, $namalengkap, $role, $fotoName);
    $response['status'] = 'success';
    $response['message'] = 'User berhasil ditambahkan';
    echo json_encode($response);
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Font gemes -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Comic Neue', cursive;
           
        }

        .form-container {
            max-width: 600px;
            font-style: bold !important;
            margin: 50px auto;
            padding: 20px;
        }

        .form-card {
            background-color: rgb(124, 142, 247);
            border-radius: 20px;
            border: 2px dashed rgb(197, 215, 255);
        }

        /* .card-header {
            background: linear-gradient(to right, rgb(140, 77, 241), rgb(109, 111, 255));
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        } */

    
        .btn-primary {
            background-color: rgb(105, 170, 255);
            border: none;
        }

        .btn-primary:hover {
            background-color: rgb(54, 39, 122);
        }

        input.form-control,
        select.form-select {
            border-radius: 10px;
            /* border: 1px solid #ffb6c1; */
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
                <h4 class="mb-0">Tambah Data User</h4>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger m-3">
                    <?php
                    switch ($_GET['error']) {
                        case 'data_kosong':
                            echo "Semua field wajib diisi!";
                            break;
                        case 'upload_gagal':
                            echo "Gagal mengupload foto!";
                            break;
                        case 'format_tidak_valid':
                            echo "Format file tidak valid! Gunakan JPG, PNG, atau GIF.";
                            break;
                        default:
                            echo "Terjadi kesalahan!";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form class="needs-validation" id="formTambahUser" method="POST" action="" enctype="multipart/form-data" novalidate>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="username" class="form-label">Username</label>
                        <input
                            type="text"
                            class="form-control"
                            id="username"
                            name="username"
                            placeholder="Masukkan username"
                            required />
                        <div class="invalid-feedback">Tolong isi username.</div>
                        <div class="valid-feedback">Bagus🤩</div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required />
                        <div class="invalid-feedback">Tolong isi password.</div>
                        <div class="valid-feedback">Bagus🤩</div>
                    </div>

                    <div class="mb-4">
                        <label for="namalengkap" class="form-label">Nama Lengkap</label>
                        <input
                            type="text"
                            class="form-control"
                            id="namalengkap"
                            name="namalengkap"
                            placeholder="Masukkan nama lengkap"
                            required />
                        <div class="invalid-feedback">Tolong isi nama lengkap.</div>
                        <div class="valid-feedback">Bagus🤩</div>
                    </div>

                    <div class="mb-4">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="" disabled selected>Pilih role</option>
                            <option value="admin">Admin</option>
                            <option value="siswa">Siswa</option>
                        </select>
                        <div class="invalid-feedback">Tolong pilih role user.</div>
                        <div class="valid-feedback">Bagus🤩</div>
                    </div>

                    <!-- <div class="mb-4">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input
                            type="file"
                            class="form-control"
                            id="foto"
                            name="foto"
                            accept="image/*" />
                        <div class="form-text">Format yang didukung: JPG, PNG, GIF (Opsional)</div>
                        <img id="preview" src="#" alt="Preview Foto" style="max-width: 150px; display: none; margin-top: 10px;" />
                    </div> -->
                </div>

                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary px-4" id="btnSimpanUser" name="simpan">
                        <i class="bi bi-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Informasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body" id="feedbackModalBody">
                    <!-- Isi pesan di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script validasi form -->
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

        // Preview foto
        // const fotoInput = document.getElementById('foto');
        // const preview = document.getElementById('preview');

        // fotoInput.addEventListener('change', function() {
        //     const file = this.files[0];
        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             preview.src = e.target.result;
        //             preview.style.display = 'block';
        //         }
        //         reader.readAsDataURL(file);
        //     } else {
        //         preview.style.display = 'none';
        //     }
        // });
    </script>

    <script>
        $('#formTambahUser').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload

            const form = $('#formTambahUser')[0];
            const data = new FormData(form);

            $.ajax({
                url: 'formuser.php',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function(response) {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        $('#feedbackModalLabel').text('Berhasil!');
                        $('#feedbackModalBody').text('User berhasil ditambahkan!');
                        const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                        modal.show();

                        // Redirect setelah modal ditutup
                        $('#feedbackModal').on('hidden.bs.modal', function() {
                            window.location.href = 'datauser.php';
                        });
                    } else {
                        $('#feedbackModalLabel').text('Gagal!');
                        $('#feedbackModalBody').text('Gagal: ' + res.message);
                        const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                        modal.show();
                    }
                },
                error: function() {
                    $('#feedbackModalLabel').text('Kesalahan!');
                    $('#feedbackModalBody').text('Terjadi kesalahan saat mengirim data.');
                    const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    modal.show();
                }
            });
        });
    </script>

    <script>
        // Tambahkan event listener untuk menampilkan modal feedback
        $('#feedbackModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget); // Tombol yang memicu modal
            const message = button.data('message'); // Ambil pesan dari data attribute
            const modalBody = $(this).find('.modal-body');
            modalBody.text(message); // Set pesan ke dalam modal body
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
    </script>

</body>

</html>