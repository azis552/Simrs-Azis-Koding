<nav class="navbar header-navbar pcoded-header">
    <div class="navbar-wrapper">
        <div class="navbar-logo">
            <a class="mobile-menu" id="mobile-collapse" href="#!">
                <i class="ti-menu"></i>
            </a>
            <div class="mobile-search">
                <div class="header-search">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                            <input type="text" class="form-control" placeholder="Enter Keyword">
                            <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="index.html">
                <img class="img-fluid" src="{{ asset('') }}/assets/images/logo.png"
                    alt="Theme-Logo" />
            </a>
            <a class="mobile-options">
                <i class="ti-more"></i>
            </a>
        </div>

        <div class="navbar-container container-fluid">
            <ul class="nav-left">
                <li>
                    <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a>
                    </div>
                </li>
                <li class="header-search">
                    <div class="main-search morphsearch-search">
                        <div class="input-group">
                            <span class="input-group-addon search-close"><i class="ti-close"></i></span>
                            <input type="text" class="form-control">
                            <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="#!" onclick="javascript:toggleFullScreen()">
                        <i class="ti-fullscreen"></i>
                    </a>
                </li>
            </ul>
            <ul class="nav-right">
                <li class="header-notification">
                    <a href="#!">
                        <i class="ti-bell"></i>
                        <span class="badge bg-c-pink"></span>
                    </a>
                    <ul class="show-notification">
                        <li>
                            <h6>Notifications</h6>
                            <label class="label label-danger">New</label>
                        </li>
                        <li>
                            <div class="media">
                                <img class="d-flex align-self-center img-radius"
                                    src="{{ asset('') }}/assets/images/avatar-2.jpg"
                                    alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5 class="notification-user">John Doe</h5>
                                    <p class="notification-msg">Lorem ipsum dolor sit amet,
                                        consectetuer elit.</p>
                                    <span class="notification-time">30 minutes ago</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <img class="d-flex align-self-center img-radius"
                                    src="{{ asset('') }}/assets/images/avatar-4.jpg"
                                    alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5 class="notification-user">Joseph William</h5>
                                    <p class="notification-msg">Lorem ipsum dolor sit amet,
                                        consectetuer elit.</p>
                                    <span class="notification-time">30 minutes ago</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <img class="d-flex align-self-center img-radius"
                                    src="{{ asset('') }}/assets/images/avatar-3.jpg"
                                    alt="Generic placeholder image">
                                <div class="media-body">
                                    <h5 class="notification-user">Sara Soudein</h5>
                                    <p class="notification-msg">Lorem ipsum dolor sit amet,
                                        consectetuer elit.</p>
                                    <span class="notification-time">30 minutes ago</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>

                <li class="user-profile header-notification">
                    <a href="#!">
                        <img src="{{ asset('') }}/assets/images/avatar-4.jpg" class="img-radius"
                            alt="User-Profile-Image">
                        <span>John Doe</span>
                        <i class="ti-angle-down"></i>
                    </a>
                    <ul class="show-notification profile-notification">
                        <li>
                            <a href="#!">
                                <i class="ti-settings"></i> Settings
                            </a>
                        </li>
                        <li>
                            <a href="user-profile.html">
                                <i class="ti-user"></i> Profile
                            </a>
                        </li>

                        <li>
                            <a href="auth-lock-screen.html">
                                <i class="ti-lock"></i> Lock Screen
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.logout') }}">
                                <i class="ti-layout-sidebar-left"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <nav class="pcoded-navbar">
            <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
            <div class="pcoded-inner-navbar main-menu">

                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.navigation">Layout</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class=" {{ request()->is('dashboard') ? 'active' : '' }} ">
                        <a href="index.html">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Data User</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class=" {{ request()->is('users') ? 'active' : '' }} " >
                        <a href="{{ route('users.index') }}">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Akun</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Menu Awal</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li >
                        <a href="index.html">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Registrasi</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li>
                        <a href="index.html">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">UGD</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Perawatan</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li >
                        <a href="index.html">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Rajal</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li >
                        <a href="index.html">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Ranap</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Farmasi</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="{{ request()->is('obats') ? 'active' : '' }}">
                        <a href="{{ route('obats.index') }}">
                            <span class="pcoded-micon"><i class="ti-archive"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Resep Dokter</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="">
                        <a href="#">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Obat</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="">
                        <a href="{{ route('obats.stokObat') }}">
                            <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.dash.main">Stok Obat</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                    
                </ul>


                

                <div class="pcoded-navigatio-lavel" data-i18n="nav.category.other">Other</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="pcoded-hasmenu ">
                        <a href="javascript:void(0)">
                            <span class="pcoded-micon"><i class="ti-direction-alt"></i><b>M</b></span>
                            <span class="pcoded-mtext" data-i18n="nav.menu-levels.main">Menu
                                Levels</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                        <ul class="pcoded-submenu">
                            <li class="">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext"
                                        data-i18n="nav.menu-levels.menu-level-21">Menu Level 2.1</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>
                            <li class="pcoded-hasmenu ">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="ti-direction-alt"></i></span>
                                    <span class="pcoded-mtext"
                                        data-i18n="nav.menu-levels.menu-level-22.main">Menu Level
                                        2.2</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                                <ul class="pcoded-submenu">
                                    <li class="">
                                        <a href="javascript:void(0)">
                                            <span class="pcoded-micon"><i
                                                    class="ti-angle-right"></i></span>
                                            <span class="pcoded-mtext"
                                                data-i18n="nav.menu-levels.menu-level-22.menu-level-31">Menu
                                                Level 3.1</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="">
                                <a href="javascript:void(0)">
                                    <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                    <span class="pcoded-mtext"
                                        data-i18n="nav.menu-levels.menu-level-23">Menu Level 2.3</span>
                                    <span class="pcoded-mcaret"></span>
                                </a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
        </nav>