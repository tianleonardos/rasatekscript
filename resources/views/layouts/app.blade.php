<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RasaTEKScript!</title>       
    <link rel="icon" href="{{ asset('images/icon.png') }}">
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body>
    @if(request()->is('/'))
        
        <!-- Script untuk menyembunyikan intro jika sudah pernah dilihat di sesi ini -->
        <script>
            // Jika memori 'introSeen' sudah ada, kita sembunyikan intro lewat CSS
            if (sessionStorage.getItem('introSeen')) {
                document.write('<style>.intro-overlay { display: none !important; }</style>');
            }
        </script>

        <div class="intro-overlay">
            <img src="{{ asset('images/logo.png') }}" alt="RasaTekScript" class="intro-logo-img">
        </div>

        <!-- Script untuk menandai bahwa intro sudah dilihat -->
        <script>
            // Simpan memori 'introSeen' = true
            sessionStorage.setItem('introSeen', 'true');
        </script>

    @endif

    <div id="app" class="d-flex flex-column min-vh-100">
        
        <div class="scrolling-text">
            <div>🍪 Selamat Datang di RasaTekScript - Cookies Terlezat & Terfavorit! 🌟</div>
        </div>

        <nav class="navbar navbar-expand-md navbar-light bg-white sticky-top">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    
                    <!-- 1. Gambar Logo -->
                    <!-- this.remove() akan menghapus elemen <img> sepenuhnya jika error, jadi tidak ada ikon gambar rusak -->
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo" 
                         height="50" 
                         class="d-inline-block align-text-top"
                         onerror="this.remove(); document.getElementById('nav-fallback-text').style.display='inline-block';">
                    
                    <!-- 2. Teks Fallback -->
                    <!-- Muncul HANYA jika gambar di atas dihapus (error) -->
                    <span id="nav-fallback-text" style="display: none;">
                        🍪 RasaTekScript
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            @if(auth()->user()->isCustomer())
                                <li class="nav-item">
                                    <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart"></i> Keranjang
                                        @if(session('cart') && count(session('cart')) > 0)
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                {{ count(session('cart')) }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customer.orders.index') }}">
                                        <i class="fas fa-box"></i> Pesanan Saya
                                    </a>
                                </li>
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                            @endif
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(auth()->user()->isCustomer())
                                        <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 70px; max-width: 400px;">
    
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

        <main class="py-4 flex-grow-1">
            @yield('content')
        </main>

        <footer class="py-4 bg-dark mt-auto">
            <div class="container-fluid px-4 text-center">
                <p class="mb-0">&copy; 2025 RasaTekScript. All Rights Reserved.</p>
                <p class="mb-0">Made with <i class="fas fa-heart text-danger"></i> by Team 12</p>
            </div>
        </footer>
    </div>
</body>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Observer options
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.15 // Elemen akan muncul saat 15% bagiannya masuk layar
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    // Jika elemen masuk viewport -> Fade In
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in-view');
                    } 
                    // Jika elemen keluar viewport -> Fade Out
                    else {
                        entry.target.classList.remove('in-view');
                    }
                });
            }, observerOptions);

            // Cari semua elemen dengan class 'scroll-fade' dan mulai observasi
            const elementsToAnimate = document.querySelectorAll('.scroll-fade');
            elementsToAnimate.forEach(el => observer.observe(el));
        });

        $(document).ready(function() {
            // Tangkap saat form dengan class 'form-add-to-cart' disubmit
            $(document).on('submit', '.form-add-to-cart', function(e) {
                e.preventDefault(); // JANGAN RELOAD HALAMAN

                var form = $(this);
                var url = form.attr('action');
                var method = form.attr('method');
                var data = form.serialize(); // Ambil data form (termasuk CSRF token)

                // Kirim request AJAX
                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    success: function(response) {
                        if (response.status === 'success') {
                            
                            // 1. Update Angka di Badge Keranjang (Navbar)
                            var badge = $('.nav-link .badge');
                            if (badge.length > 0) {
                                badge.text(response.total_cart);
                            } else {
                                // Jika badge belum ada (cart dari 0 ke 1), buat elemen badge baru
                                $('.fa-shopping-cart').parent().append(
                                    '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">' + response.total_cart + '</span>'
                                );
                            }

                            // 2. Tampilkan Notifikasi Melayang (Manual Create HTML)
                            var notifHtml = `
                                <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert">
                                    <i class="fas fa-check-circle me-2"></i> ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            
                            // Masukkan notifikasi ke dalam wadah position-fixed yang kita buat sebelumnya
                            // Kita pakai selector class dari jawaban sebelumnya:
                            $('.position-fixed.top-0.end-0').append(notifHtml);

                            // Auto hide notifikasi baru setelah 3 detik
                            setTimeout(function() {
                                $('.alert').last().fadeOut('slow', function(){ $(this).remove(); });
                            }, 3000);
                        } else if (response.status === 'error') {
                            // Tangani pesan error yang dikirim dari CartController
                            var errorHtml = `
                                <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i> ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `;
                            $('.position-fixed.top-0.end-0').append(errorHtml);
                            
                            // Auto hide error setelah 5 detik
                            setTimeout(function() {
                                $('.alert').last().fadeOut('slow', function(){ $(this).remove(); });
                            }, 5000);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        // Tangani error HTTP/server
                        alert('Terjadi kesalahan server, coba lagi.');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>