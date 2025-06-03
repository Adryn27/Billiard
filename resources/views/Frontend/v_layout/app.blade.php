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
                <a class="navbar-brand" href="#page-top">
                    <img src="{{ asset('Backend/gambar/logo3.png') }}" alt="Logo" style="height: 40px; width: auto; margin-right: 10px;">
                    Cue Town Reserve
                </a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
                        <li class="nav-item"><a class="nav-link" href="#table">Table</a></li>
                        <li class="nav-item"><a class="nav-link" href="#kategori">Category</a></li>
                        <li class="nav-item">
                            <a class="btn btn-primary px-4 py-2" href="#">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
        {{-- footer --}}
        <footer class="bg-light py-5">
            <div class="container px-4 px-lg-5"><div class="small text-center text-muted">Copyright &copy; 2025 - Kelompok 1</div></div>
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
