<body>
    <div id="app">
        <div id="sidebar" class='active'>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                <img src="{{ asset('images/' . $setting->logo) }}" style="width: 120px; height: auto; display: block; margin: 0 auto;">

                    <!-- <div style="font-size: 20px; color: #333;">{{ $setting->nama_setting }}</div> -->
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class='sidebar-title'>Main Menu</li>

                        <!-- Dashboard Menu -->
                        <li class="sidebar-item {{ ($currentMenu == 'dashboard') ? 'active' : '' }}">
                            <a href="{{ url('home') }}" class='sidebar-link'>
                                <i data-feather="home" width="20"></i> 
                                <span>Dashboard</span>
                            </a>
                        </li>



                        @if(session('level') == 1)
                        
                            <li class="sidebar-item {{ ($currentMenu == 'user') ? 'active' : '' }}">
                                <a href="{{ url('home/user') }}" class='sidebar-link'>
                                    <i data-feather="user" width="20"></i> 
                                    <span>User</span>
                                </a>
                            </li>

                            @endif
                        

                            @if(session('level') == 1)
                       
                            <li class="sidebar-item {{ ($currentMenu == 'kelas') ? 'active' : '' }}">
                                <a href="{{ url('home/kelas') }}" class='sidebar-link'>
                                    <i data-feather="grid" width="20"></i> 
                                    <span>Kelas</span>
                                </a>
                            </li>
                            @endif

                            @if(session('level') == 1 || session('level') == 4 || session('level') == 5)
                        
                            <li class="sidebar-item {{ ($currentMenu == 'tugas') ? 'active' : '' }}">
                                <a href="{{ url('home/tugas') }}" class='sidebar-link'>
                                    <i data-feather="file-plus" width="20"></i> 
                                    <span>Tugas</span>
                                </a>
                            </li>
                            @endif

                            @if(session('level') == 1 || session('level') == 4 )
                       
                            <li class="sidebar-item {{ ($currentMenu == 'nilai') ? 'active' : '' }}">
                                <a href="{{ url('home/nilai') }}" class='sidebar-link'>
                                    <i data-feather="file-text" width="20"></i> 
                                    <span>Penilaian</span>
                                </a>
                            </li>

                            @endif
                       
                            @if(session('level') == 1 || session('level') == 2 || session('level') == 3 || session('level') == 4 || session('level') == 5)
                       
                            <li class="sidebar-item {{ ($currentMenu == 'nilaisiswa') ? 'active' : '' }}">
                                <a href="{{ url('home/nilaisiswa') }}" class='sidebar-link'>
                                    <i data-feather="award" width="20"></i> 
                                    <span>Nilai</span>
                                </a>
                            </li>

                            @endif

                            @if(session('level') == 1)
                      
                            <li class="sidebar-item {{ ($currentMenu == 'setting') ? 'active' : '' }}">
                                <a href="{{ url('home/setting') }}" class='sidebar-link'>
                                    <i data-feather="settings" width="20"></i> 
                                    <span>Setting</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ ($currentMenu == 'log') ? 'active' : '' }}">
                                <a href="{{ url('home/log') }}" class='sidebar-link'>
                                    <i data-feather="globe" width="20"></i> 
                                    <span>Activity Log</span>
                                </a>
                            </li>

                            <!-- Restore Menu -->
                            <li class="sidebar-item has-sub {{ in_array($currentMenu, ['restore_user', 'restore_kelas', 'restore_tugas']) ? 'active' : '' }}">
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
                                <a class="dropdown-item" href="{{ route('home.profile', session('id')) }}"><i data-feather="user"></i> Account</a>
                              
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('home/logout') }}"><i data-feather="log-out"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        
