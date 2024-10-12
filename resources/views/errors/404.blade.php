<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   

    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/chartjs/Chart.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
   
</head>

<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
               

                   
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class='sidebar-title'>Main Menu</li>

                        <!-- Dashboard Menu -->
                        <li class="sidebar-item ">
                            <a href="{{ url('home') }}" class='sidebar-link'>
                                <i data-feather="home" width="20"></i> 
                                <span>Dashboard</span>
                            </a>
                        </li>



                        @if(session('level') == 1)
                        
                        <li class="sidebar-item ">
                                <a href="{{ url('home/user') }}" class='sidebar-link'>
                                    <i data-feather="user" width="20"></i> 
                                    <span>User</span>
                                </a>
                            </li>

                            @endif
                        

                            @if(session('level') == 1)
                       
                            <li class="sidebar-item ">
                                <a href="{{ url('home/kelas') }}" class='sidebar-link'>
                                    <i data-feather="grid" width="20"></i> 
                                    <span>Kelas</span>
                                </a>
                            </li>
                            @endif

                            @if(session('level') == 1 || session('level') == 4 || session('level') == 5)
                        
                            <li class="sidebar-item ">
                                <a href="{{ url('home/tugas') }}" class='sidebar-link'>
                                    <i data-feather="file-plus" width="20"></i> 
                                    <span>Tugas</span>
                                </a>
                            </li>
                            @endif

                            @if(session('level') == 1 || session('level') == 4 )
                       
                            <li class="sidebar-item ">
                                <a href="{{ url('home/nilai') }}" class='sidebar-link'>
                                    <i data-feather="file-text" width="20"></i> 
                                    <span>Penilaian</span>
                                </a>
                            </li>

                            @endif
                       
                            @if(session('level') == 1 || session('level') == 2 || session('level') == 3 || session('level') == 4 || session('level') == 5)
                       
                            <li class="sidebar-item ">
                                <a href="{{ url('home/nilaisiswa') }}" class='sidebar-link'>
                                    <i data-feather="award" width="20"></i> 
                                    <span>Nilai</span>
                                </a>
                            </li>

                            @endif

                            @if(session('level') == 1)
                      
                            <li class="sidebar-item ">
                                <a href="{{ url('home/setting') }}" class='sidebar-link'>
                                    <i data-feather="settings" width="20"></i> 
                                    <span>Setting</span>
                                </a>
                            </li>

                            <li class="sidebar-item ">
                                <a href="{{ url('home/log') }}" class='sidebar-link'>
                                    <i data-feather="globe" width="20"></i> 
                                    <span>Activity Log</span>
                                </a>
                            </li>

                            <!-- Restore Menu -->
                            <li class="sidebar-item ">
                                <a href="#" class='sidebar-link'>
                                    <i data-feather="trash" width="20"></i> 
                                    <span>Restore</span>
                                </a>
                                <ul class="submenu">
                                    <li><a href="{{ url('home/restore_user') }}">Restore User</a></li>
                                    <li><a href="{{ url('home/restore_kelas') }}">Restore Kelas</a></li>
                                    <li><a href="{{ url('home/restore_tugas') }}">Restore Tugas</a></li>
                                </ul>
                            </li>
                            @endif
                            
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        
        <!-- Main Content Section -->
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
                        <!-- User Info and Logout -->
                        <li class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <div class="avatar me-1">
                                    <img src="{{ asset('images/' . session('foto')) }}" alt="">
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">Hi, {{ session('nama') }}</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                            @if(session('id'))
                            <a class="dropdown-item" href="{{ route('home.profile', session('id')) }}"><i data-feather="user"></i> Account</a>
@else
    <p>Session ID tidak tersedia</p>
@endif
                                
                              
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('home/logout') }}"><i data-feather="log-out"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        

            </footer>
        </div>
    </div>
    <script src="{{ asset('js/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script src="{{ asset('vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard.js') }}"></script>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
