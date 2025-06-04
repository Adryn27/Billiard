<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{ $judul }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('Backend/Gambar/logo3.png') }}" />
        <!-- Bootstrap Icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- SimpleLightbox plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('frontend/css/styles.css') }}" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" href="/#page-top">
                    <img src="{{ asset('Backend/gambar/logo3.png') }}" alt="Logo" style="height: 40px; width: auto; margin-right: 10px;">
                    Cue Town Reserve
                </a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
                        <li class="nav-item"><a class="nav-link" href="/#table">Table</a></li>
                        <li class="nav-item"><a class="nav-link" href="/#kategori">Category</a></li>
                        <li class="nav-item dropdown">
                            @guest
                                <!-- Jika belum login -->
                                <a href="{{ url('auth/google') }}" class="btn btn-primary px-4 py-2">Login</a>
                            @else
                                <!-- Jika sudah login -->
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama) }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                    <span>{{ Auth::user()->nama }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">History</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            @endguest
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
        {{-- footer --}}
        <footer class="bg-light py-5 border-top">
            <div class="container px-4 px-lg-5">
              <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                  <small class="text-primary">&copy; 2025 Kelompok 1. All rights reserved.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="social-icon" aria-label="Instagram" role="img">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                        <path d="M8 5.37A2.63 2.63 0 1 0 8 10.63 2.63 2.63 0 0 0 8 5.37zm0 4.31a1.68 1.68 0 1 1 0-3.36 1.68 1.68 0 0 1 0 3.36z"/>
                        <path d="M12.87 4.37a.58.58 0 1 1-1.16 0 .58.58 0 0 1 1.16 0z"/>
                        <path d="M8 1c1.98 0 2.22 0 3 .04a4.85 4.85 0 0 1 1.64.3 3.04 3.04 0 0 1 1.1.7c.31.31.55.7.7 1.1a4.85 4.85 0 0 1 .3 1.64c.04.78.04 1.02.04 3s0 2.22-.04 3a4.85 4.85 0 0 1-.3 1.64 3.04 3.04 0 0 1-.7 1.1 3.04 3.04 0 0 1-1.1.7 4.85 4.85 0 0 1-1.64.3c-.78.04-1.02.04-3 .04s-2.22 0-3-.04a4.85 4.85 0 0 1-1.64-.3 3.04 3.04 0 0 1-1.1-.7 3.04 3.04 0 0 1-.7-1.1 4.85 4.85 0 0 1-.3-1.64c-.04-.78-.04-1.02-.04-3s0-2.22.04-3a4.85 4.85 0 0 1 .3-1.64 3.04 3.04 0 0 1 .7-1.1 3.04 3.04 0 0 1 1.1-.7 4.85 4.85 0 0 1 1.64-.3c.78-.04 1.02-.04 3-.04zm0 1c-1.95 0-2.18 0-2.95.04a3.77 3.77 0 0 0-1.27.24c-.43.17-.73.39-1.05.7a2.2 2.2 0 0 0-.7 1.05 3.77 3.77 0 0 0-.24 1.27c-.04.77-.04 1-.04 2.95s0 2.18.04 2.95c.06.5.1.88.24 1.27.17.43.39.73.7 1.05a2.2 2.2 0 0 0 1.05.7 3.77 3.77 0 0 0 1.27.24c.77.04 1 .04 2.95.04s2.18 0 2.95-.04c.5-.06.88-.1 1.27-.24.43-.17.73-.39 1.05-.7a2.2 2.2 0 0 0 .7-1.05 3.77 3.77 0 0 0 .24-1.27c.04-.77.04-1 .04-2.95s0-2.18-.04-2.95a3.77 3.77 0 0 0-.24-1.27 2.2 2.2 0 0 0-.7-1.05 2.2 2.2 0 0 0-1.05-.7 3.77 3.77 0 0 0-1.27-.24c-.77-.04-1-.04-2.95-.04z"/>
                      </svg>
                    </a>
                </div>
              </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SimpleLightbox plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ asset('frontend/js/scripts.js') }}"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
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
    </body>
</html>
