<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{ $judul }}</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('Backend/Gambar/logo3.png') }}"
    />

    <!-- Fonts and icons -->
    <script src="{{ asset('Backend/assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{ asset('Backend/assets/css/fonts.min.css') }}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('Backend/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('Backend/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('Backend/assets/css/kaiadmin.min.css') }}" />
     <!-- Custom styles for basicdatatable-->
     <link href="{{ asset('Backend/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="Backend/assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{ route('backend.dashboard.index') }}" class="logo">
              <img
                src="{{ asset('Backend/Gambar/logo3.png') }}"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
              <span class="text-white fw-bold ms-2">Cue Town Reserve</span>
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Main</h4>
              </li>
              <li class="nav-item active">
                <a href="{{ route('backend.dashboard.index') }}">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Management</h4>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.user.index') }}">
                  <i class="fas fa-user"></i>
                  <p>User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.customer.index') }}">
                  <i class="fas fa-users"></i>
                  <p>Customer</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Billiard</h4>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.category.index') }}">
                  <i class="fas fa-clipboard-list"></i>
                  <p>Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.table.index') }}">
                  <i class="fas fa-th-large"></i>
                  <p>Table</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Report</h4>
              </li>
              <li class="nav-item">
                <a href="{{ route('transaksi') }}">
                  <i class="fas fa-money-bill-wave"></i>
                  <p>Transaction</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div style="font-family: Arial, sans-serif; font-size: 18px; font-weight: 600; color: #333;">
                  <b><span id="waktu-sekarang" style="background-color:#f0f0f0; padding: 6px 12px; border-radius: 5px; font-family: 'Courier New', monospace;"></span></b>
                </div>
              </nav>
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      @if (Auth::user()->foto)
                        <img
                          src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                          alt="profile"
                          class="avatar-img rounded-circle"
                        />
                      @else
                        <img
                          src="{{ asset('Backend/Gambar/img-default.jpg') }}"
                          alt="profile"
                          class="avatar-img rounded-circle"
                        />
                      @endif
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">{{ Auth::user()->nama }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            @if (Auth::user()->foto)
                              <img
                                src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                                alt="image profile"
                                class="avatar-img rounded"
                              />
                            @else
                              <img
                                src="{{ asset('Backend/Gambar/img-default.jpg') }}"
                                alt="image profile"
                                class="avatar-img rounded"
                              />
                            @endif
                          </div>
                          <div class="u-text">
                            <h4>{{ Auth::user()->nama }}</h4>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                            <a
                              href="{{ route('profile') }}"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('backend.logout') }}">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
          @yield('content')
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="https://github.com/Adryn27">
                    W_Adryn
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2025, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="https://github.com/Adryn27">W_Adryn</a>
            </div>
            <div>
              Distributed by
              <a target="_blank" href="#">Kelompok 1</a>.
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('Backend/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('Backend/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('Backend/assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('Backend/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('Backend/assets/js/plugin/chart.js/chart.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('Backend/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('Backend/assets/js/plugin/chart-circle/circles.min.js') }}"></script>

    <!-- Datatables -->
    {{-- <script src="{{ asset('Backend/assets/js/plugin/datatables/datatables.min.js') }}"></script> --}}
    <!-- Page level plugins -->
    <script src="{{ asset('Backend/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('Backend/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
      $(document).ready(function () {
        $('#dataTable').DataTable();
      });
    </script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('Backend/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('Backend/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('Backend/assets/js/plugin/jsvectormap/world.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('Backend/sweetalert/sweetalert2.all.min.js') }}"></script>
    @if (session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}"
      })
    </script>
    @endif

    <script type="text/javascript">
      // Konfirmasi delete
      $('.show_confirm').click(function(event){
        var form=$(this).closest("form");
        var konfdelete=$(this).data("konf-delete");
        event.preventDefault();
        Swal.fire({
          title: 'Konfirmasi Hapus Data?',
          html: "Data yang dihapus <strong>" + konfdelete + "</strong> tidak dapat dikembalikan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, dihapus',
          cancelButtonText: 'Batal',
        }).then((result)=>{
          if(result.isConfirmed){
            Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success')
              .then(()=>{
                form.submit();
              });
          }
        });
      });
    </script>

    <!-- Script Preview Foto -->
    <script>
      //Preview Foto
          function previewFoto(){
              const foto = document.querySelector('input[name="foto"]');
              const fotoPreview = document.querySelector('.foto-preview');
              fotoPreview.style.display = 'block';
              const fotoReader = new FileReader();
              fotoReader.readAsDataURL(foto.files[0]);
              fotoReader.onload = function(fotoEvent) {
              fotoPreview.src = fotoEvent.target.result;
              fotoPreview.style.width = '100%';
              }
          }
    </script>

    {{-- Countdown --}}
    <script>
      document.addEventListener("DOMContentLoaded", function () {
          document.querySelectorAll('.countdown').forEach(function (el) {
              const startTime = new Date(el.dataset.starttime).getTime();
              const endTime = new Date(el.dataset.endtime).getTime();
      
              function updateCountdown() {
                  const now = new Date().getTime();
      
                  if (now < startTime) {
                      el.innerText = "--:--:--";
                      return;
                  }
      
                  const distance = endTime - now;
      
                  if (distance <= 0) {
                      el.innerText = "00:00:00";
                      return;
                  }
      
                  const hours = String(Math.floor(distance / (1000 * 60 * 60))).padStart(2, '0');
                  const minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                  const seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
      
                  el.innerText = `${hours}:${minutes}:${seconds}`;
              }
      
              setInterval(updateCountdown, 1000);
              updateCountdown();
          });
      });
    </script>      
    
    {{-- <script>
      document.addEventListener('DOMContentLoaded', function () {
          const jamMulaiInput = document.querySelector('input[name="jam_mulai"]');
          const durasiInput = document.querySelector('input[name="durasi"]');
          const waktuMulaiBerakhirEl = document.getElementById('waktu_mulai_berakhir');
          const countdownEl = document.getElementById('countdown');
  
          let countdownInterval;
  
          function formatJam(date) {
              const h = date.getHours().toString().padStart(2, '0');
              const m = date.getMinutes().toString().padStart(2, '0');
              return `${h}:${m}`;
          }
  
          function startCountdown(endTime) {
              clearInterval(countdownInterval);
  
              function updateCountdown() {
                  const now = new Date().getTime();
                  const distance = endTime - now;
  
                  if (distance <= 0) {
                      clearInterval(countdownInterval);
                      countdownEl.textContent = "00:00:00";
                      return;
                  }
  
                  const hours = Math.floor(distance / (1000 * 60 * 60));
                  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                  const seconds = Math.floor((distance % (1000 * 60)) / 1000);
  
                  countdownEl.textContent = 
                      String(hours).padStart(2, '0') + ":" + 
                      String(minutes).padStart(2, '0') + ":" + 
                      String(seconds).padStart(2, '0');
              }
  
              updateCountdown();
              countdownInterval = setInterval(updateCountdown, 1000);
          }
  
          function updateWaktuDanCountdown() {
              const jamMulaiValue = jamMulaiInput.value;
              const durasiValue = parseFloat(durasiInput.value);
  
              if (!jamMulaiValue || isNaN(durasiValue) || durasiValue <= 0) {
                  waktuMulaiBerakhirEl.textContent = "--:-- - --:--";
                  countdownEl.textContent = "--:--:--";
                  clearInterval(countdownInterval);
                  return;
              }
  
              // Parse jam_mulai input (datetime-local format)
              const mulaiDate = new Date(jamMulaiValue);
              if (isNaN(mulaiDate.getTime())) {
                  waktuMulaiBerakhirEl.textContent = "--:-- - --:--";
                  countdownEl.textContent = "--:--:--";
                  clearInterval(countdownInterval);
                  return;
              }
  
              // Hitung waktu berakhir (jam_mulai + durasi jam)
              const durasiMs = durasiValue * 60 * 60 * 1000;
              const akhirDate = new Date(mulaiDate.getTime() + durasiMs);
  
              waktuMulaiBerakhirEl.textContent = `${formatJam(mulaiDate)} - ${formatJam(akhirDate)}`;
  
              startCountdown(akhirDate.getTime());
          }
  
          jamMulaiInput.addEventListener('input', updateWaktuDanCountdown);
          durasiInput.addEventListener('input', updateWaktuDanCountdown);
  
          // Optional: update saat modal dibuka
          updateWaktuDanCountdown();
      });
    </script> --}}
  
    {{-- Show Modal Reservasi --}}
    @if(session('show_tambah_modal'))
    <script>
        window.onload = function() {
            var modal = new bootstrap.Modal(document.getElementById('tambahModal'));
            modal.show();
        };
    </script>
    @endif

    {{-- Jam --}}
    <script>
      function updateWaktuSekarang() {
          const now = new Date();
          const formatted = now.toLocaleTimeString();
          document.getElementById('waktu-sekarang').textContent = formatted;
      }
      setInterval(updateWaktuSekarang, 1000);
      updateWaktuSekarang();
    </script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('Backend/assets/js/kaiadmin.min.js') }}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
  </body>
</html>
