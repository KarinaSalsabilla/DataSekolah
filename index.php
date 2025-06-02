<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Login | DataSekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- jQuery & Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.css" />

  <style>
    * {
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }

    body {
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(to right,rgb(172, 214, 255),rgb(63, 97, 170));
    }

    .login-box {
      width: 100%;
      max-width: 420px;
      background: #ffffffcc;
      backdrop-filter: blur(10px);
      border-radius: 16px;
      padding: 40px 35px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      animation: fadeIn 0.6s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(25px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-header {
      text-align: center;
      margin-bottom: 30px;
      color: #2c3e50;
    }

    .login-header h1 {
      font-weight: 700;
      font-size: 28px;
      margin-bottom: 8px;
    }

    .login-header .icon {
      font-size: 40px;
      color: #3498db;
      margin-bottom: 10px;
    }

    .form-floating>label i {
      margin-right: 8px;
      color: #6c757d;
    }

    .form-control {
      background: #fff;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 12px 15px;
      font-size: 15px;
      color: #333;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
      border-color: #3498db;
    }

    .btn-primary {
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      font-size: 16px;
      background: linear-gradient(to right, #2980b9, #6dd5fa);
      border: none;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .info-text {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #666;
    }

    /* Modal Styling */
    .modal-content {
      border-radius: 10px;
      overflow: hidden;
    }

    .modal-header {
      background-color: #dc3545;
      color: #fff;
      border-bottom: none;
    }

    .modal-body {
      background-color: #f8d7da;
      color: #721c24;
      font-size: 15px;
      padding: 20px;
    }

    .modal.fade .modal-dialog {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      margin: 0 auto;
    }

    .modal-dialog {
      max-width: 400px;
    }
  </style>


  </style>
</head>

<body>
  <div class="login-box">
    <div class="login-header">
      <div class="icon"><i class="bi bi-mortarboard-fill"></i></div>
      <h1>DataSekolah</h1>
      <p class="text-white-10">Silakan login untuk melanjutkan</p>
    </div>

    <form id="loginForm" method="POST">
      <div class="form-floating mb-3">
        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required />
        <label for="username"><i class="bi bi-person-fill"></i> Username</label>
      </div>

      <div class="form-floating mb-4">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required />
        <label for="password"><i class="bi bi-lock-fill"></i> Password</label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </form>

    <div class="info-text">Belum punya akun? Hubungi admin sekolah</div>
  </div>

  <!-- Modal -->
  <!-- Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="errorModalLabel">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Login Gagal
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="errorModalMessage">
          <!-- Pesan error muncul di sini -->
        </div>
      </div>
    </div>
  </div>


  <script>
    $('#loginForm').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        url: 'login.php', // hanya memproses, tidak berisi HTML
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            window.location.href = 'dashboard.php';
          } else {
            $('#errorModalMessage').text(response.message);
            const modal = new bootstrap.Modal(document.getElementById('errorModal'));
            modal.show();
          }
        },
        error: function() {
          $('#errorModalMessage').text('Terjadi kesalahan saat menghubungkan ke server.');
          const modal = new bootstrap.Modal(document.getElementById('errorModal'));
          modal.show();
        }
      });
    });
  </script>

  <script src="dist/js/adminlte.js"></script>
</body>

</html>