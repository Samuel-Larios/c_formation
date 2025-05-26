<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title')</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    {{-- <link rel="icon" href="{{ asset('assets/img/kaiadmin/Logo_Songhai.jpg') }}" type="image/jpeg"> --}}
    <link rel="shortcut icon" href="{{ asset('assets/img/kaiadmin/Logo_Songhai.jpg') }}" type="image/jpeg">
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/Logo_Songhai.jpg') }}?v=2" type="image/jpeg">

    <!-- Inclure le CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Inclure le JS de Bootstrap et jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Charger JS avec asset() -->
    <script src="{{ asset('js/app.js') }}" defer></script>



    <!-- Fonts and icons -->
    <script src="{{ asset("assets/js/plugin/webfont/webfont.min.js") }}"></script>
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
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset("assets/css/bootstrap.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/css/plugins.min.css") }}" />
    <link rel="stylesheet" href="{{ asset("assets/css/kaiadmin.min.css") }}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    {{-- <link rel="stylesheet" href="{{ asset("assets/css/demo.css") }}" /> --}}
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="{{ route('dashboard') }}" class="logo">
                <img
                    src="{{ asset('assets/img/kaiadmin/Logo_Songhaï.png') }}"
                    alt="Songhaï Logo"
                    class="navbar-brand"
                    height="50"
                    width="auto"
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
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
              <ul class="nav nav-secondary">
                      <!-- Dashboard -->
                      <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                          <a href="{{ route('dashboard') }}">
                              <i class="fas fa-home"></i>
                              <p>Dashboard</p>
                          </a>
                      </li>

                      <!-- Sites -->
                      {{-- <li class="nav-item {{ request()->routeIs('sites.*') ? 'active' : '' }}">
                          <a href="{{ route('sites.index') }}">
                              <i class="fas fa-map-marker-alt"></i>
                              <p>Sites</p>
                          </a>
                      </li> --}}

                      <!-- Students -->
                      <li class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
                          <a href="{{ route('students.index') }}">
                              <i class="fas fa-users"></i>
                              <p>Students</p>
                          </a>
                      </li>

                      <!-- Specializations -->
                      {{-- <li class="nav-item {{ request()->routeIs('specializations.*') ? 'active' : '' }}">
                          <a href="{{ route('specializations.index') }}">
                              <i class="fas fa-graduation-cap"></i>
                              <p>Specializations</p>
                          </a>
                      </li> --}}

                                          <!-- Promotions -->
                      {{-- <li class="nav-item {{ request()->routeIs('promotions.*') ? 'active' : '' }}">
                          <a href="{{ route('promotions.index') }}">
                              <i class="fas fa-trophy"></i>
                              <p>Promotions</p>
                          </a>
                      </li> --}}
                      <li class="nav-item {{ request()->routeIs('promotions.*') || request()->routeIs('promotion_apprenant.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#promotions">
                              <i class="fas fa-trophy"></i>
                              <p>Promotions</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse" id="promotions">
                              <ul class="nav nav-collapse">
                                  <li>
                                      <a href="{{ route('promotions.create') }}">
                                          <span class="sub-item">Create a promotion</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('promotion_apprenant.create') }}">
                                          <span class="sub-item">Enroll a student in a promotion</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('promotions.index') }}">
                                          <span class="sub-item">List of promotions</span>
                                      </a>
                                  </li>

                                  <li>
                                      <a href="{{ route('promotion_apprenant.index') }}">
                                          <span class="sub-item">Students/Promotion</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>

                      <li class="nav-item {{ request()->routeIs('specializations.*') || request()->routeIs('specialites.*') ? 'active' : '' }}">
                          <a data-bs-toggle="collapse" href="#specializations">
                              <i class="fas fa-graduation-cap"></i>
                              <p>Specializations</p>
                              <span class="caret"></span>
                          </a>
                          <div class="collapse" id="specializations">
                              <ul class="nav nav-collapse">
                                  <li>
                                      <a href="{{ route('specialites.create') }}">
                                          <span class="sub-item">Create a specialization</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('specializations.create') }}">
                                          <span class="sub-item">Enroll a student in a specialization</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('specializations.index') }}">
                                          <span class="sub-item">List of students/specializations</span>
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('specialites.index') }}">
                                          <span class="sub-item">List of specializations</span>
                                      </a>
                                  </li>
                              </ul>
                          </div>
                      </li>


                      <!-- Entities -->
                      <li class="nav-item {{ request()->routeIs('entities.*') ? 'active' : '' }}">
                          <a href="{{ route('entities.index') }}">
                              <i class="fas fa-building"></i>
                              <p>Value chains</p>
                          </a>
                      </li>

                      <!-- Job Creations -->
                      <li class="nav-item {{ request()->routeIs('jobcreations.*') ? 'active' : '' }}">
                          <a href="{{ route('jobcreations.index') }}">
                              <i class="fas fa-briefcase"></i>
                              <p>Job Creations</p>
                          </a>
                      </li>

                      <!-- Salaries -->
                      <li class="nav-item {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
                          <a href="{{ route('salaries.index') }}">
                              <i class="fas fa-money-bill"></i>
                              <p>Wage employee</p>
                          </a>
                      </li>

                      <!-- Subventions -->
                      <li class="nav-item {{ request()->routeIs('subventions.*') ? 'active' : '' }}">
                          <a href="{{ route('subventions.index') }}">
                              <i class="fas fa-hand-holding-usd"></i>
                              <p>Program supports</p>
                          </a>
                      </li>

                      <!-- business_status -->
                      <li class="nav-item {{ request()->routeIs('business_status.*') ? 'active' : '' }}">
                          <a href="{{ route('business_status.index') }}">
                              <i class="fas fa-chart-line"></i>
                              <p>Business Status</p>
                          </a>
                      </li>

                      <!-- follow_ups -->
                      <li class="nav-item {{ request()->routeIs('follow_ups.*') ? 'active' : '' }}">
                          <a href="{{ route('follow_ups.index') }}">
                              <i class="fas fa-clipboard-list"></i>
                              <p>Follow Ups</p>
                          </a>
                      </li>



                      <!-- Utilisateurs -->
                      {{-- <li class="nav-item {{ request()->routeIs('utilisateurs.*') ? 'active' : '' }}">
                          <a href="{{ route('utilisateurs.index') }}">
                              <i class="fas fa-user-cog"></i>
                              <p>Users</p>
                          </a>
                      </li> --}}

                      <!-- Matières -->
                      <li class="nav-item {{ request()->routeIs('matieres.*') ? 'active' : '' }}">
                          <a href="{{ route('matieres.index') }}">
                              <i class="fas fa-book"></i>
                              <p>Subjects</p>
                          </a>
                      </li>

                      <!-- Évaluations -->
                      <li class="nav-item {{ request()->routeIs('evaluations.*') ? 'active' : '' }}">
                          <a href="{{ route('evaluations.index') }}">
                              <i class="fas fa-clipboard-check"></i>
                              <p>Evaluations</p>
                          </a>
                      </li>
                  </ul>
                  <ul>
                      {{-- Logo de mastercard --}}
                      <img src="{{ asset('assets/img/kaiadmin/mastercard-symbol-02.jpg') }}" alt="Mastercard Logo" class="navbar-brand" height="100" width="auto">
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
            <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                <div class="container-fluid">
                    <!-- Search Bar -->
                    <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="submit" class="btn btn-search pe-1">
                                    <i class="fa fa-search search-icon"></i>
                                </button>
                            </div>
                            <input type="text" placeholder="Search ..." class="form-control" />
                        </div>
                    </nav>

                    <!-- Topbar Icons -->
                    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                        <!-- Search Icon for Mobile -->
                        <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                                <i class="fa fa-search"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-search animated fadeIn">
                                <form class="navbar-left navbar-form nav-search">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search ..." class="form-control" />
                                    </div>
                                </form>
                            </ul>
                        </li>

                        <!-- Quick Actions Dropdown -->
                        <li class="nav-item topbar-icon dropdown hidden-caret">
                            <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <i class="fas fa-layer-group"></i>
                            </a>
                            <div class="dropdown-menu quick-actions animated fadeIn">
                                <!-- Quick Actions content here -->
                            </div>
                        </li>

                        <!-- User Profile Dropdown -->
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="avatar-sm">
                                    <!-- User Profile Picture -->
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Picture" class="avatar-img rounded-circle">
                                    @else
                                        <div class="avatar-img rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            {{ substr(Auth::user()->name, 0, 1) }} <!-- Display first letter of the name -->
                                        </div>
                                    @endif
                                </div>
                                <span class="profile-username">
                                    <span class="op-7">Hi,</span>
                                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box d-flex align-items-center">
                                            <!-- Avatar -->
                                            <div class="avatar-lg me-4 mx-2">
                                                @if(Auth::user()->profile_photo)
                                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Picture" class="avatar-img rounded">
                                                @else
                                                    <div class="avatar-img rounded bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                        {{ substr(Auth::user()->name, 0, 1) }} <!-- Affiche la première lettre du nom -->
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Informations de l'utilisateur -->
                                            <div class="u-text">
                                                <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                                                <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                                                <a href="#" class="btn btn-xs btn-secondary btn-sm">Voir le profil</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">My Profile</a>
                                        <a class="dropdown-item" href="#">My Balance</a>
                                        <a class="dropdown-item" href="#">Inbox</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Account Settings</a>
                                        <div class="dropdown-divider"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- End Navbar -->

          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                @yield('content')
            </div>
          </div>
        </div>

        {{-- Footer --}}
        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <nav>
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="http://www.kasdigit.com">KAS Digit</a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright">
                    &copy; 2025 - Songhaï. All rights reserved.
                </div>
            </div>
        </footer>

        {{-- End Footer --}}
      </div>

    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset("assets/js/core/jquery-3.7.1.min.js") }}"></script>
    <script src="{{ asset("assets/js/core/popper.min.js") }}"></script>
    <script src="{{ asset("assets/js/core/bootstrap.min.js") }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset("assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js") }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset("assets/js/plugin/chart.js/chart.min.js") }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset("assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js") }}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset("assets/js/plugin/chart-circle/circles.min.js") }}"></script>

    <!-- Datatables -->
    <script src="{{ asset("assets/js/plugin/datatables/datatables.min.js") }}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset("assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js") }}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset("assets/js/plugin/jsvectormap/jsvectormap.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugin/jsvectormap/world.js") }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset("assets/js/plugin/sweetalert/sweetalert.min.js") }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset("assets/js/kaiadmin.min.js") }}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ asset("assets/js/setting-demo.js") }}"></script>
    <script src="{{ asset("assets/js/demo.js") }}"></script>
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
