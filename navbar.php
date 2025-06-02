<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- Enhanced Navbar with better styling -->
<nav class="app-header navbar navbar-expand bg-body shadow-sm border-bottom">
    <div class="container-fluid">
        <!-- Start Navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebar-toggle" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list fs-5"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="dashboard.php" class="nav-link fw-medium">
                    <i class="bi bi-house-door me-1"></i>Home
                </a>
            </li>
        </ul>

        <!-- End Navbar links -->
        <ul class="navbar-nav ms-auto align-items-center">
            <!-- Theme Switcher with better design -->
            <li class="nav-item dropdown me-2">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center"
                    id="bd-theme" type="button" aria-expanded="false"
                    data-bs-toggle="dropdown" data-bs-display="static">
                    <span class="theme-icon-active me-1">
                        <i class="bi bi-sun-fill"></i>
                    </span>
                    <span class="d-none d-lg-inline">Theme</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                    aria-labelledby="bd-theme" style="min-width: 10rem; border-radius: 0.75rem;">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center py-2 active"
                            data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2 text-warning"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none text-success"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center py-2"
                            data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2 text-primary"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none text-success"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center py-2"
                            data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-circle-half me-2 text-info"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none text-success"></i>
                        </button>
                    </li>
                </ul>
            </li>

            <!-- Fullscreen Toggle with better styling -->
            <li class="nav-item me-2">
                <a class="nav-link btn btn-outline-secondary btn-sm" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>

            <!-- Enhanced User Menu -->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle user-dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown">
                    <?php
                    // Initialize variables for photo and initials
                    $foto_path = 'dist/assets/img/user2-160x160.jpg';
                    $initials = '';

                    $parts = explode(' ', $_SESSION['nama_lengkap']);
                    if (!empty($parts[0])) {
                        $initials = strtoupper($parts[0][0]);
                    }


                    // Set photo path if uploaded photo exists
                    if (!empty($_SESSION['foto']) && file_exists('uploads/' . $_SESSION['foto'])) {
                        $foto_path = 'uploads/' . $_SESSION['foto'];
                    }
                    ?>

                    <div class="user-avatar-container me-2">
                        <?php if (!empty($_SESSION['foto']) && file_exists('uploads/' . $_SESSION['foto'])): ?>
                            <img src="uploads/<?= htmlspecialchars($_SESSION['foto']) ?>"
                                class="user-image rounded-circle shadow-sm border border-2 border-white"
                                alt="User Image"
                                id="navbar-user-image"
                                style="width: 42px; height: 42px; object-fit: cover;" />
                        <?php else: ?>
                            <div class="user-image rounded-circle bg-gradient text-white d-flex align-items-center justify-content-center shadow-sm border border-2 border-white"
                                id="navbar-user-initials"
                                style="width: 42px; height: 42px; font-weight: 600; font-size: 14px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <?= htmlspecialchars($initials) ?>
                            </div>
                        <?php endif; ?>
                        <div class="user-status-indicator"></div>
                    </div>

                    <div class="user-info d-none d-md-block">
                        <span class="user-name fw-medium"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'User') ?></span>
                        <small class="user-role text-muted d-block"><?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'user')) ?></small>
                    </div>
                </a>

                <!-- Enhanced Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow-lg border-0"
                    style="border-radius: 1rem; min-width: 320px;">
                    <!-- User Header -->
                    <li class="user-header text-white text-center p-4 position-relative"
                        style="background: rgb(59, 160, 255); border-top-left-radius: 1rem; border-top-right-radius: 1rem;">

                        <div class="user-avatar-large mb-3">
                            <?php if (!empty($_SESSION['foto']) && file_exists('uploads/' . $_SESSION['foto'])): ?>
                                <img src="<?= htmlspecialchars($foto_path) ?>"
                                    class="img-circle shadow-lg border border-4 border-white"
                                    id="dropdown-user-image"
                                    style="width: 100px; height: 100px; object-fit: cover;"
                                    alt="User Image" />
                            <?php else: ?>
                                <div class="img-circle shadow-lg bg-white text-dark d-flex align-items-center justify-content-center mx-auto border border-4 border-white"
                                    id="dropdown-user-initials"
                                    style="width: 100px; height: 100px; font-size: 36px; font-weight: 700;">
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'User') ?></h6>
                        <p class="mb-1 opacity-75"><?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'user')) ?></p>
                        <small class="opacity-50">@<?= htmlspecialchars($_SESSION['username'] ?? 'N/A') ?></small>
                    </li>

                    <!-- Action Buttons -->
                    <li class="p-3 bg-light">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                                data-bs-toggle="modal" data-bs-target="#ubahFotoModal">
                                <i class="bi bi-person-gear me-2"></i>
                                Kelola Profil
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center"
                                data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Sign Out
                            </button>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Enhanced Profile Modal -->
<div class="modal fade" id="ubahFotoModal" tabindex="-1" aria-labelledby="ubahFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form id="formUpdateUser" action="update_user.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white border-0" style="border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem;">
                    <h5 class="modal-title fw-bold" id="ubahFotoModalLabel">
                        <i class="bi bi-person-gear me-2"></i>Kelola Profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Enhanced Profile Photo Section -->
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-photo-container">
                                <?php
                                $foto_path_modal = 'dist/assets/img/user2-160x160.jpg';
                                if (!empty($_SESSION['foto']) && file_exists('uploads/' . $_SESSION['foto'])) {
                                    $foto_path_modal = 'uploads/' . $_SESSION['foto'];
                                }
                                ?>

                                <div class="position-relative d-inline-block">
                                    <?php if (!empty($_SESSION['foto']) && file_exists('uploads/' . $_SESSION['foto'])): ?>
                                        <img src="<?= htmlspecialchars($foto_path_modal) ?>"
                                            alt="Foto Profil"
                                            class="rounded-circle shadow-lg border border-4 border-light"
                                            id="modal-profile-image"
                                            style="width:140px; height:140px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle shadow-lg bg-gradient text-white d-flex align-items-center justify-content-center border border-4 border-light"
                                            id="modal-profile-initials"
                                            style="width: 140px; height: 140px; font-size: 48px; font-weight: bold; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <?= htmlspecialchars($initials) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="profile-photo-overlay">
                                        <i class="bi bi-camera text-white"></i>
                                    </div>
                                </div>

                                <!-- Photo Controls -->
                                <div class="mt-3">
                                    <input type="file"
                                        id="foto_auto_upload"
                                        name="foto_baru"
                                        accept="image/*"
                                        class="d-none" />

                                    <button type="button" id="btn-ubah-foto" class="btn btn-primary btn-sm me-2">
                                        <i class="bi bi-camera-fill me-1"></i> Ubah Foto
                                    </button>

                                    <?php if (!empty($_SESSION['foto']) && file_exists('uploads/' . $_SESSION['foto'])): ?>
                                        <button type="button" id="btn-hapus-foto" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    <?php endif; ?>

                                    <!-- Enhanced Loading indicator -->
                                    <div id="upload-loading" class="mt-3" style="display: none;">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                                <span class="visually-hidden">Uploading...</span>
                                            </div>
                                            <small class="text-muted">Mengupload foto...</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <!-- Enhanced Form Fields -->
                            <div class="mb-3">
                                <label for="username" class="form-label fw-medium">
                                    <i class="bi bi-person me-1 text-primary"></i>Username
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="username"
                                    name="username"
                                    value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>"
                                    required />
                            </div>

                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label fw-medium">
                                    <i class="bi bi-person-badge me-1 text-primary"></i>Nama Lengkap
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="nama_lengkap"
                                    name="nama_lengkap"
                                    value="<?= htmlspecialchars($_SESSION['nama_lengkap'] ?? '') ?>"
                                    readonly />
                            </div>

                            <div class="mb-3">
                                <label for="password_baru" class="form-label fw-medium">
                                    <i class="bi bi-lock me-1 text-primary"></i>Password Baru
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="password_baru"
                                    name="password_baru"
                                    placeholder="Kosongkan jika tidak ingin mengubah" />
                            </div>

                            <div class="mb-3">
                                <label for="password_konfirmasi" class="form-label fw-medium">
                                    <i class="bi bi-shield-check me-1 text-primary"></i>Konfirmasi Password
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="password_konfirmasi"
                                    name="password_konfirmasi"
                                    placeholder="Konfirmasi password baru" />
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Alert Section -->
                    <div id="pesan" class="mt-3"></div>
                </div>

                <div class="modal-footer bg-light border-0 p-3">
                    <button type="submit" name="submit" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="logoutModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Logout
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-box-arrow-right text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-2">Apakah Anda yakin ingin keluar?</h6>
                <p class="text-muted mb-0">Anda akan diarahkan ke halaman login.</p>
            </div>
            <div class="modal-footer border-0 p-3">
                <form method="POST" action="logout.php" class="w-100">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="bi bi-box-arrow-right me-2"></i>Ya, Keluar
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Foto -->
<div class="modal fade" id="modalKonfirmasiHapusFoto" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus foto profil?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="konfirmasiHapusFoto">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced CSS Styles -->
<style>
    .user-avatar-container {
        position: relative;
    }

    .user-status-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background-color: #28a745;
        border: 2px solid white;
        border-radius: 50%;
    }

    /* Navbar User Initials - Always visible */
    #navbar-user-initials {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border: 2px solid rgba(255, 255, 255, 0.8) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
    }

    /* Dropdown User Initials - Responsive to theme */
    #dropdown-user-initials {
        font-weight: 700 !important;
        border: 4px solid #ffffff !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }

    /* Light theme - dropdown initials */
    [data-bs-theme="light"] #dropdown-user-initials {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #ffffff !important;
    }

    /* Dark theme - dropdown initials */
    [data-bs-theme="dark"] #dropdown-user-initials {
        background: linear-gradient(135deg, #4a90e2 0%, #6c5ce7 100%) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Modal Profile Initials - Always visible */
    #modal-profile-initials {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: #ffffff !important;
        font-weight: bold !important;
        border: 4px solid rgba(255, 255, 255, 0.9) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* Dark theme adjustments for modal */
    [data-bs-theme="dark"] #modal-profile-initials {
        background: linear-gradient(135deg, #4a90e2 0%, #6c5ce7 100%) !important;
        border-color: rgba(255, 255, 255, 0.95) !important;
        box-shadow: 0 8px 25px rgba(255, 255, 255, 0.1) !important;
    }

    /* Navbar Enhancements */
    .app-header {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
        background-color: rgba(0, 123, 255, 0.1);
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .user-dropdown-toggle {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .user-dropdown-toggle:hover {
        background-color: rgba(0, 123, 255, 0.05);
        border-color: rgba(0, 123, 255, 0.2);
    }

    .user-name {
        font-size: 0.9rem;
        line-height: 1.2;
    }

    .user-role {
        font-size: 0.75rem;
        line-height: 1;
    }

    /* Profile Photo Container */
    .profile-photo-container {
        position: relative;
    }

    .profile-photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }



    /* Theme Switcher Animation */
    .theme-icon-active i {
        transition: all 0.3s ease;
    }

    /* Dropdown Menu Enhancements */
    .dropdown-menu {
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Modal Enhancements */
    .modal-content {
        border-radius: 1rem;
        overflow: hidden;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7);
    }

    /* Form Enhancements */
    .form-control-lg {
        border-radius: 0.75rem;
        border: 2px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .form-control-lg:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Button Enhancements */
    .btn {
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
        .user-info {
            display: none !important;
        }

        .dropdown-menu-lg {
            min-width: 280px !important;
        }

        .modal-lg {
            max-width: 90%;
        }
    }

    /* Dark theme support */
    [data-bs-theme="dark"] .app-header {
        background: rgba(33, 37, 41, 0.95) !important;
        border-color: rgba(255, 255, 255, 0.1);
    }

    [data-bs-theme="dark"] .user-dropdown-toggle:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Fallback untuk initials - pastikan selalu terlihat */
    .user-image[id*="initials"],
    div[id*="initials"] {
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
        font-weight: 600 !important;
    }
</style>

<!-- Script untuk Theme Switcher -->
<script>
    (() => {
        "use strict";

        const storedTheme = localStorage.getItem("theme");

        const getPreferredTheme = () => {
            if (storedTheme) {
                return storedTheme;
            }

            return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
        };

        const setTheme = function(theme) {
            if (theme === "auto" && window.matchMedia("(prefers-color-scheme: dark)").matches) {
                document.documentElement.setAttribute("data-bs-theme", "dark");
            } else {
                document.documentElement.setAttribute("data-bs-theme", theme);
            }
        };

        setTheme(getPreferredTheme());

        const showActiveTheme = (theme, focus = false) => {
            const themeSwitcher = document.querySelector("#bd-theme");

            if (!themeSwitcher) return;

            const themeSwitcherText = document.querySelector("#bd-theme-text");
            const activeThemeIcon = document.querySelector(".theme-icon-active i");
            const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);

            if (!btnToActive) return;

            document.querySelectorAll("[data-bs-theme-value]").forEach(element => {
                element.classList.remove("active");
                element.setAttribute("aria-pressed", "false");
            });

            btnToActive.classList.add("active");
            btnToActive.setAttribute("aria-pressed", "true");

            const icon = btnToActive.querySelector("i").className;
            activeThemeIcon.className = icon;

            if (themeSwitcherText) {
                const selectedTheme = btnToActive.innerText.trim();
                themeSwitcherText.innerText = selectedTheme;
            }

            if (focus) {
                themeSwitcher.focus();
            }
        };

        window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
            if (storedTheme !== "light" && storedTheme !== "dark") {
                setTheme(getPreferredTheme());
            }
        });

        window.addEventListener("DOMContentLoaded", () => {
            showActiveTheme(getPreferredTheme());

            document.querySelectorAll("[data-bs-theme-value]").forEach(toggle => {
                toggle.addEventListener("click", () => {
                    const theme = toggle.getAttribute("data-bs-theme-value");
                    localStorage.setItem("theme", theme);
                    setTheme(theme);
                    showActiveTheme(theme, true);
                });
            });
        });
    })();
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Trigger file input ketika tombol "Ubah Foto" diklik
        $('#btn-ubah-foto').on('click', function() {
            $('#foto_auto_upload').click();
        });

        // Auto upload foto ketika file dipilih
        $('#foto_auto_upload').on('change', function() {
            const file = this.files[0];
            if (file) {
                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak didukung! Gunakan format JPG, PNG, atau GIF.');
                    return;
                }

                // Validasi ukuran file (maksimal 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    return;
                }

                // Tampilkan loading
                $('#upload-loading').show();

                // Buat FormData untuk upload
                const formData = new FormData();
                formData.append('foto_baru', file);
                formData.append('auto_upload', '1'); // Flag untuk auto upload

                // Upload via AJAX
                $.ajax({
                    url: 'update_user.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            // Parse JSON response
                            const data = typeof response === 'string' ? JSON.parse(response) : response;

                            if (data.success) {
                                // Update gambar di modal
                                const newImageSrc = 'uploads/' + data.filename + '?t=' + new Date().getTime();

                                // Update foto di modal
                                if ($('#modal-profile-image').length) {
                                    $('#modal-profile-image').attr('src', newImageSrc);
                                } else {
                                    $('#modal-profile-initials').replaceWith(
                                        '<img src="' + newImageSrc + '" alt="Foto Profil" class="rounded-circle mb-3" id="modal-profile-image" style="width:120px; height:120px; object-fit: cover;">'
                                    );
                                }

                                // Update foto di navbar
                                if ($('#navbar-user-image').length) {
                                    $('#navbar-user-image').attr('src', newImageSrc);
                                } else {
                                    $('#navbar-user-initials').replaceWith(
                                        '<img src="' + newImageSrc + '" class="user-image rounded-circle shadow" alt="User Image" id="navbar-user-image" style="width: 40px; height: 40px; object-fit: cover;" />'
                                    );
                                }

                                // Update foto di dropdown
                                if ($('#dropdown-user-image').length) {
                                    $('#dropdown-user-image').attr('src', newImageSrc);
                                } else {
                                    $('#dropdown-user-initials').replaceWith(
                                        '<img src="' + newImageSrc + '" class="img-circle elevation-2" id="dropdown-user-image" style="width: 120px; height: 120px; object-fit: cover;" alt="User Image" />'
                                    );
                                }

                                // Tampilkan tombol hapus foto jika belum ada
                                if ($('#btn-hapus-foto').length === 0) {
                                    $('#btn-ubah-foto').after('<button type="button" id="btn-hapus-foto" class="btn btn-sm btn-danger mb-2 ms-2"><i class="bi bi-trash me-1"></i> Hapus Foto</button>');
                                }

                                // Tampilkan pesan sukses
                                showMessage('success', data.message);
                            } else {
                                showMessage('danger', data.message);
                            }
                        } catch (e) {
                            console.error('Parse error:', e);
                            showMessage('danger', 'Terjadi kesalahan dalam memproses response.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        showMessage('danger', 'Terjadi kesalahan saat mengupload foto.');
                    },
                    complete: function() {
                        $('#upload-loading').hide();
                        $('#foto_auto_upload').val('');
                    }
                });
            }
        });

        $(document).on('click', '#btn-hapus-foto', function() {
            $('#modalKonfirmasiHapusFoto').modal('show');
        });

        // Hapus foto
        $(document).on('click', '#konfirmasiHapusFoto', function() {
            $.ajax({
                url: 'update_user.php',
                type: 'POST',
                data: {
                    hapus_foto: '1'
                },
                success: function(response) {
                    try {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;

                        if (data.success) {
                            const initials = data.initials || 'U';

                            // Update modal
                            $('#modal-profile-image').replaceWith(
                                '<div class="rounded-circle mb-3 bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" id="modal-profile-initials" style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">' + initials + '</div>'
                            );

                            // Update navbar
                            $('#navbar-user-image').replaceWith(
                                '<div class="user-image rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center shadow" id="navbar-user-initials" style="width: 40px; height: 40px; font-weight: bold;">' + initials + '</div>'
                            );

                            // Update dropdown
                            $('#dropdown-user-image').replaceWith(
                                '<div class="img-circle elevation-2 bg-white text-dark d-flex align-items-center justify-content-center mx-auto" id="dropdown-user-initials" style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">' + initials + '</div>'
                            );

                            // Hapus tombol hapus
                            $('#btn-hapus-foto').remove();

                            showMessage('success', data.message);
                            $('#modalKonfirmasiHapusFoto').modal('hide');
                        } else {
                            showMessage('danger', data.message);
                        }
                    } catch (e) {
                        console.error('Parse error:', e);
                        showMessage('danger', 'Terjadi kesalahan dalam memproses response.');
                    }
                },
                error: function() {
                    showMessage('danger', 'Terjadi kesalahan saat menghapus foto.');
                }
            });
        });


        // Form update user (untuk username dan password)
        $('#formUpdateUser').on('submit', function(e) {
            e.preventDefault();

            // Validasi client-side
            const password_baru = $('#password_baru').val();
            const password_konfirmasi = $('#password_konfirmasi').val();

            if (password_baru && password_baru !== password_konfirmasi) {
                showMessage('danger', 'Konfirmasi password tidak cocok!');
                return;
            }

            $('#upload-loading').show();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(), // Gunakan serialize() untuk form data biasa
                success: function(response) {
                    try {
                        const data = typeof response === 'string' ? JSON.parse(response) : response;

                        if (data.success) {
                            showMessage('success', data.message);

                            // Update nama di navbar jika diperlukan
                            // Optional: reload atau update tampilan lainnya
                        } else {
                            showMessage('danger', data.message);
                        }
                    } catch (e) {
                        console.error('Parse error:', e);
                        showMessage('danger', 'Terjadi kesalahan dalam memproses response.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    showMessage('danger', 'Terjadi kesalahan saat menyimpan perubahan.');
                },
                complete: function() {
                    $('#upload-loading').hide();
                }
            });
        });

        // Function untuk menampilkan pesan
        function showMessage(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const html = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                message +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
            $('#pesan').html(html);

            // Auto hide after 5 seconds
            setTimeout(function() {
                $('#pesan .alert').fadeOut();
            }, 5000);
        }
    });
</script>