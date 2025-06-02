<?php

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

$nama = $_SESSION['nama_lengkap'];

?>

<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>DataSekolah | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />

  <!-- Fonts -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />

  <!-- Third Party Plugins -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />

  <!-- AdminLTE -->
  <link rel="stylesheet" href="dist/css/adminlte.css" />

  <!-- Chart Libraries -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />


  <style>
    .dropdown-menu .btn {
      transition: all 0.3s ease-in-out;
    }

    .dropdown-menu .btn:hover {
      transform: scale(1.05);
    }

    /* Fix untuk navbar overlap */
    .app-wrapper {
      position: relative;
    }

    /* Pastikan sidebar tidak overlap dengan content */
    .app-main {
      margin-left: 0;
      transition: margin-left 0.3s ease;
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
  </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include "navbar.php"; ?>

    <!-- NAVBAR - Gunakan hanya satu navbar -->
    <?php include "sidebar.php"; ?>
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header fade-in">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Dashboard</h3>
            </div>
            <!-- <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div> -->
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->
      <div class="app-content slide-in">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->

          <?php
          // Koneksi ke database
          $conn = new mysqli("localhost", "root", "", "sekolah");

          // Cek koneksi
          if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
          }

          $result = $conn->query("SELECT COUNT(*) AS jumlah FROM user_login");
          $row = $result->fetch_assoc();
          $jumlah_user = $row['jumlah'];

          // Query menghitung jumlah siswa
          $result = $conn->query("SELECT COUNT(*) AS jumlah FROM siswa");
          $row = $result->fetch_assoc();
          $jumlah_siswa = $row['jumlah'];

          $result = $conn->query("SELECT COUNT(*) AS jumlah FROM jurusan");
          $row = $result->fetch_assoc();
          $jumlah_jurusan = $row['jumlah'];

          $result = $conn->query("SELECT COUNT(*) AS jumlah FROM agama");
          $row = $result->fetch_assoc();
          $jumlah_agama = $row['jumlah'];
          ?>


          <div class="row">

            <div class="col-lg-3 col-6">
              <!--begin::Small Box Widget 3-->
              <div class="small-box text-bg-warning">
                <div class="inner">
                  <h3><?= $jumlah_siswa ?></h3>
                  <p>Data Siswa</p>
                </div>
                <i class="bi bi-person-fill small-box-icon" style="font-size: 80px; position: absolute; top: 2px; right: 10px; opacity: 1;"></i>
                <a
                  href="datasiswa.php"
                  class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                  Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 3-->
            </div>
            <!--begin::Col-->
            <div class="col-lg-3 col-6">
              <!--begin::Small Box Widget 1-->
              <div class="small-box text-bg-primary">
                <div class="inner">
                  <h3><?= $jumlah_agama ?></h3>
                  <p>Data Agama</p>
                </div>
                <i class="fas fa-star-and-crescent small-box-icon"></i>
                <a
                  href="datagama.php"
                  class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                  Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 1-->
            </div>
            <!--end::Col-->
            <div class="col-lg-3 col-6">
              <!--begin::Small Box Widget 2-->
              <div class="small-box text-bg-success">
                <div class="inner">
                  <h3><?= $jumlah_jurusan ?></h3>
                  <p>Data Jurusan</p>
                </div>
                <i class="bi bi-journal-bookmark small-box-icon" style="font-size: 70px; position: absolute; top: 5px; right: 10px; opacity: 1;"></i>
                <a
                  href="datajurusan.php"
                  class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                  Lihat Detail <i class="bi bi-link-45deg"></i>
                </a>
              </div>
              <!--end::Small Box Widget 2-->
            </div>
            <!--end::Col-->

            <!--end::Col-->
            <?php if ($role == 'admin'): ?>
              <div class="col-lg-3 col-6">
                <!--begin::Small Box Widget 4-->
                <div class="small-box text-bg-danger">
                  <div class="inner">
                    <h3><?= $jumlah_user ?></h3>
                    <p>Users</p>
                  </div>
                  <i class="bi bi-people-fill small-box-icon" style="font-size: 70px; position: absolute; top: 5px; right: 10px; opacity: 1;"></i>
                  <a
                    href="datauser.php"
                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Lihat Detail <i class="bi bi-link-45deg"></i>
                  </a>
                </div>
                <!--end::Small Box Widget 4-->
              </div>
            <?php endif; ?>
            <!--end::Col-->
          </div>
          <!--end::Row-->
          <!--begin::Row-->

          <!-- /.row (main row) -->
        </div>


        <div class="card mt-4">
          <div class="card-header">
            <h5 class="card-title">Statistik Data</h5>
          </div>
          <div class="card-body">
            <canvas id="statistikChart" height="100"></canvas>
          </div>
        </div>


      </div>
      <!--end::Container-->
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
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
    <!--begin::Footer-->
  </div>



  <!--end::App Wrapper-->
  <!--begin::Script-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
    integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
    crossorigin="anonymous"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
    crossorigin="anonymous"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src="dist/js/adminlte.js"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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
    });
  </script>
  <!--end::OverlayScrollbars Configure-->
  <!-- OPTIONAL SCRIPTS -->
  <!-- sortablejs -->
  <script
    src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"
    integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ="
    crossorigin="anonymous"></script>
  <!-- sortablejs -->
  <script>
    const connectedSortables = document.querySelectorAll('.connectedSortable');
    connectedSortables.forEach((connectedSortable) => {
      let sortable = new Sortable(connectedSortable, {
        group: 'shared',
        handle: '.card-header',
      });
    });

    const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
    cardHeaders.forEach((cardHeader) => {
      cardHeader.style.cursor = 'move';
    });
  </script>
  <!-- apexcharts -->
  <script
    src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
    integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
    crossorigin="anonymous"></script>


<script>
  // Color Mode Toggler
  (() => {
    "use strict";

    const storedTheme = localStorage.getItem("theme");

    const getPreferredTheme = () => {
      if (storedTheme) {
        return storedTheme;
      }

      return window.matchMedia("(prefers-color-scheme: dark)").matches ?
        "dark" :
        "light";
    };

    const setTheme = function(theme) {
      if (
        theme === "auto" &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
      ) {
        document.documentElement.setAttribute("data-bs-theme", "dark");
      } else {
        document.documentElement.setAttribute("data-bs-theme", theme);
      }
    };

    setTheme(getPreferredTheme());

    const showActiveTheme = (theme, focus = false) => {
      const themeSwitcher = document.querySelector("#bd-theme");

      if (!themeSwitcher) {
        return;
      }

      const themeSwitcherText = document.querySelector("#bd-theme-text");
      const activeThemeIcon = document.querySelector(".theme-icon-active i");
      const btnToActive = document.querySelector(
        `[data-bs-theme-value="${theme}"]`
      );
      
      if (!btnToActive) return; // Safety check
      
      const svgOfActiveBtn = btnToActive.querySelector("i").getAttribute("class");

      for (const element of document.querySelectorAll("[data-bs-theme-value]")) {
        element.classList.remove("active");
        element.setAttribute("aria-pressed", "false");
      }

      btnToActive.classList.add("active");
      btnToActive.setAttribute("aria-pressed", "true");
      if (activeThemeIcon) {
        activeThemeIcon.setAttribute("class", svgOfActiveBtn);
      }
      if (themeSwitcherText) {
        const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
        themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);
      }

      if (focus) {
        themeSwitcher.focus();
      }
    };

    window
      .matchMedia("(prefers-color-scheme: dark)")
      .addEventListener("change", () => {
        if (storedTheme !== "light" || storedTheme !== "dark") {
          setTheme(getPreferredTheme());
        }
      });

    window.addEventListener("DOMContentLoaded", () => {
      showActiveTheme(getPreferredTheme());

      for (const toggle of document.querySelectorAll("[data-bs-theme-value]")) {
        toggle.addEventListener("click", () => {
          const theme = toggle.getAttribute("data-bs-theme-value");
          localStorage.setItem("theme", theme);
          setTheme(theme);
          showActiveTheme(theme, true);
        });
      }
    });
  })();

  // Statistik Chart (Chart.js) - This one exists in your HTML
  document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statistikChart');
    if (ctx) {
      const statistikChart = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Siswa', 'Agama', 'Jurusan', 'User'],
          datasets: [{
            label: 'Jumlah Data',
            data: [<?= $jumlah_siswa ?>, <?= $jumlah_agama ?>, <?= $jumlah_jurusan ?>, <?= $jumlah_user ?>],
            backgroundColor: [
              'rgba(255, 206, 86, 0.6)',
              'rgba(54, 162, 235, 0.6)',
              'rgba(75, 192, 192, 0.6)',
              'rgba(255, 99, 132, 0.6)'
            ],
            borderColor: [
              'rgba(255, 206, 86, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0 // biar tidak koma
              }
            }
          }
        }
      });
    }

    // OverlayScrollbars Configure
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };

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

    // Connected Sortables (if elements exist)
    const connectedSortables = document.querySelectorAll('.connectedSortable');
    connectedSortables.forEach((connectedSortable) => {
      let sortable = new Sortable(connectedSortable, {
        group: 'shared',
        handle: '.card-header',
      });
    });

    const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');
    cardHeaders.forEach((cardHeader) => {
      cardHeader.style.cursor = 'move';
    });

    // Only render ApexCharts if elements exist
    
    // Revenue Chart
    const revenueChartElement = document.querySelector('#revenue-chart');
    if (revenueChartElement) {
      const sales_chart_options = {
        series: [{
            name: 'Digital Goods',
            data: [28, 48, 40, 19, 86, 27, 90],
          },
          {
            name: 'Electronics',
            data: [65, 59, 80, 81, 56, 55, 40],
          },
        ],
        chart: {
          height: 300,
          type: 'area',
          toolbar: {
            show: false,
          },
        },
        legend: {
          show: false,
        },
        colors: ['#0d6efd', '#20c997'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        xaxis: {
          type: 'datetime',
          categories: [
            '2023-01-01',
            '2023-02-01',
            '2023-03-01',
            '2023-04-01',
            '2023-05-01',
            '2023-06-01',
            '2023-07-01',
          ],
        },
        tooltip: {
          x: {
            format: 'MMMM yyyy',
          },
        },
      };

      const sales_chart = new ApexCharts(revenueChartElement, sales_chart_options);
      sales_chart.render();
    }

    // Sparkline charts
    const sparkline1Element = document.querySelector('#sparkline-1');
    if (sparkline1Element) {
      const option_sparkline1 = {
        series: [{
          data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
        }],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline1 = new ApexCharts(sparkline1Element, option_sparkline1);
      sparkline1.render();
    }

    const sparkline2Element = document.querySelector('#sparkline-2');
    if (sparkline2Element) {
      const option_sparkline2 = {
        series: [{
          data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
        }],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline2 = new ApexCharts(sparkline2Element, option_sparkline2);
      sparkline2.render();
    }

    const sparkline3Element = document.querySelector('#sparkline-3');
    if (sparkline3Element) {
      const option_sparkline3 = {
        series: [{
          data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
        }],
        chart: {
          type: 'area',
          height: 50,
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: 'straight',
        },
        fill: {
          opacity: 0.3,
        },
        yaxis: {
          min: 0,
        },
        colors: ['#DCE6EC'],
      };

      const sparkline3 = new ApexCharts(sparkline3Element, option_sparkline3);
      sparkline3.render();
    }

    // World map by jsVectorMap
    const worldMapElement = document.querySelector('#world-map');
    if (worldMapElement) {
      const visitorsData = {
        US: 398, // USA
        SA: 400, // Saudi Arabia
        CA: 1000, // Canada
        DE: 500, // Germany
        FR: 760, // France
        CN: 300, // China
        AU: 700, // Australia
        BR: 600, // Brazil
        IN: 800, // India
        GB: 320, // Great Britain
        RU: 3000, // Russia
      };

      const map = new jsVectorMap({
        selector: '#world-map',
        map: 'world',
      });
    }
  });
</script>

  <!--end::Script-->
</body>
<!--end::Body-->

</html>