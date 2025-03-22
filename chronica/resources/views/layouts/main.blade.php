<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Chronica</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('assest/css/style.css') }}" rel="stylesheet">
    @yield('styles')

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">   

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('assest/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center bg-light px-lg-5">
            <div class="col-12 col-md-8">
                <div class="d-flex justify-content-between">
                    <div class="bg-primary text-white text-center py-2" style="width: 100px;">Tranding</div>
                    <div class="owl-carousel owl-carousel-1 tranding-carousel position-relative d-inline-flex align-items-center ml-3" style="width: calc(100% - 100px); padding-left: 90px;">
                        <div class="text-truncate"><a class="text-secondary" href="">Labore sit justo amet eos sed, et sanctus dolor diam eos</a></div>
                        <div class="text-truncate"><a class="text-secondary" href="">Gubergren elitr amet eirmod et lorem diam elitr, ut est erat Gubergren elitr amet eirmod et lorem diam elitr, ut est erat</a></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-right d-none d-md-block">
                Monday, January 01, 2045
            </div>
        </div>
        <div class="row align-items-center py-2 px-lg-5">
            <div class="col-lg-4">
                <a href="" class="navbar-brand d-none d-lg-block">
                    <h1 class="m-0 display-5 text-uppercase"><span class="text-primary">Chro</span>nica</h1>
                </a>
            </div>
            <div class="col-lg-8 text-center text-lg-right">
                <img class="img-fluid" src="assest/img/ads-700x70.jpg" alt="">
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid p-0 mb-3">
        <nav class="navbar navbar-expand-lg bg-light navbar-light py-2 py-lg-0 px-lg-5">
            <a href="" class="navbar-brand d-block d-lg-none">
                <h1 class="m-0 display-5 text-uppercase"><span class="text-primary">Chro</span>nica</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-0 px-lg-3" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="/" class="nav-item nav-link active">Home</a>
                    <a href="/blog" class="nav-item nav-link">Blog</a>
                    <a href="/contact" class="nav-item nav-link">Contact</a>
                    
                    @if(Auth::check()) <!-- Vérifie si l'utilisateur est connecté -->
                    @if(Auth::user()->role === 'admin') <!-- Vérifie si l'utilisateur est un administrateur -->
                        <li class="nav-item">
                            <a class="nav-item nav-link" href="/admin">Administration</a>
                        </li>
                    @endif
                    @if(in_array(Auth::user()->role, ['admin', 'author'])) <!-- Vérifie si l'utilisateur est un admin ou un auteur -->
                        <a class="nav-item nav-link" href="/articles/create">Nouvel article</a>
                    @endif
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"> {{ Auth::user()->name }}  </a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a class="dropdown-item" href="/profile">Profil</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                @method('POST')
                                <button type="submit" class="dropdown-item" type="submit">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a class="nav-item nav-link" href="/login">Connexion</a>
                    <a class="nav-item nav-link" href="/register">Inscription</a>
                @endif
                
                
                </div>
                
                <form action="{{ route('articles.search') }}" method="GET" class="d-flex">
                    <div class="input-group ml-auto" style="width: 100%; max-width: 300px;">
                        <input type="text" name="title" class="form-control" placeholder="Keyword" value="{{ request()->title }}">
                        <div class="input-group-append">
                            <button class="input-group-text text-secondary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
                
            </div>
        </nav>
    </div>
    <!-- Main Content -->
    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('errors'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach(session('errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

     <!-- Footer Start -->
     <div class="container-fluid bg-light pt-5 px-sm-3 px-md-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-5">
                <a href="index.html" class="navbar-brand">
                    <h1 class="mb-2 mt-n2 display-5 text-uppercase"><span class="text-primary">Chro</span>nica</h1>
                </a>
                <p>Volup amet magna clita tempor. Tempor sea eos vero ipsum. Lorem lorem sit sed elitr sed kasd et</p>
                <div class="d-flex justify-content-start mt-4">
                    <a class="btn btn-outline-secondary text-center mr-2 px-0" style="width: 38px; height: 38px;" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-secondary text-center mr-2 px-0" style="width: 38px; height: 38px;" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-secondary text-center mr-2 px-0" style="width: 38px; height: 38px;" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-secondary text-center mr-2 px-0" style="width: 38px; height: 38px;" href="#"><i class="fab fa-instagram"></i></a>
                    <a class="btn btn-outline-secondary text-center mr-2 px-0" style="width: 38px; height: 38px;" href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="font-weight-bold mb-4">Categories</h4>
                <div class="d-flex flex-wrap m-n1">
                    @foreach($categories as $category)
                      <a href="{{ route('articles.byCategory', $category->slug) }}" class="btn btn-sm btn-outline-secondary m-1">
                      {{ $category->name }}
                      </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="font-weight-bold mb-4">Tags</h4>
                <div class="d-flex flex-wrap m-n1">
                    @foreach($tags as $tag)
                    <a href="{{ route('articles.byTag', $tag->slug) }}" class="btn btn-sm btn-outline-secondary m-1">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="font-weight-bold mb-4">Quick Links</h4>
                <div class="d-flex flex-column">
                    <a href="/" class="text-secondary mb-2">Home</a>
                    <a href="/blog" class="text-secondary mb-2">Blog</a>
                    <a href="/contact" class="text-secondary mb-2">Contact</a>
                    <a href="/about" class="text-secondary mb-2">About Us</a>
                    <a href="/privacy-policy" class="text-secondary mb-2">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
 <!-- Back to Top -->
 <a href="#" class="btn btn-dark back-to-top"><i class="fa fa-angle-up"></i></a>


 <!-- JavaScript Libraries -->
 <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
 <script src="{{ asset('assest/lib/easing/easing.min.js') }}"></script>
 <script src="{{ asset('assest/lib/owlcarousel/owl.carousel.min.js') }}"></script>

 <!-- Contact Javascript File -->
 <script src="{{ asset('assest/mail/jqBootstrapValidation.min.js') }}"></script>
 <script src="{{ asset('assest/mail/contact.js') }}"></script>

 <!-- Template Javascript -->
 <script src="{{ asset('assest/js/main.js') }}"></script>



 @yield('scripts')
</body>
</html>
