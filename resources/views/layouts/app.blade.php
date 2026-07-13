<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $setting->app_name }} | {{ $title }}</title>
    <meta content="{{ $setting->description }}" name="description">
    <meta content="{{ $setting->keywords }}" name="keywords">
    <meta content="Tamus Tahir" name="author">

    <!-- Favicons -->
    <link href="{{ $setting->logo ? asset('storage/' . $setting->logo) : asset('niceadmin/img/laravel.png') }}" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files (Murni Bootstrap & Vendor Bawaan) -->
    <link href="{{ asset('niceadmin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">

    <!-- add on -->
    <link rel="stylesheet" href="{{ asset('niceadmin/vendor/dataTables/css/dataTables.bootstrap5.css') }}">
    <link href="{{ asset('niceadmin/vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('niceadmin/vendor/select2/css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet" />

    <!-- Template Main CSS File (Dipertahankan sebagai base) -->
    <link href="{{ asset('niceadmin/css/style.css') }}" rel="stylesheet">

    <!-- PayFlow Custom Style Overrides -->
    <style>
        :root {
            --payflow-purple: #7B3DFF;
            --payflow-purple-light: #F2EEFF;
            --payflow-bg: #F4F5F9;
            --payflow-text-dark: #1E1E2F;
            --payflow-text-muted: #8A8B9F;
        }

        body {
            background-color: var(--payflow-bg);
            font-family: 'Nunito', sans-serif;
            color: var(--payflow-text-dark);
        }

        /* Override Header / Topbar */
        .header {
            background-color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.02);
            border-bottom: 1px solid #EBECEF;
            height: 70px;
        }
        .header .logo span {
            color: var(--payflow-text-dark);
            font-weight: 800;
        }

        /* Override Sidebar */
        .sidebar {
            background-color: #fff;
            box-shadow: none;
            border-right: 1px solid #EBECEF;
            width: 250px;
            padding-top: 15px;
            transition: all 0.3s ease;
        }
        .sidebar-nav .nav-link {
            background: #fff;
            color: var(--payflow-text-muted);
            font-weight: 600;
            border-radius: 8px;
            margin: 0 15px 5px 15px;
            padding: 10px 15px;
            font-size: 14px;
            border-left: 3px solid transparent;
        }
        .sidebar-nav .nav-link i {
            color: var(--payflow-text-muted);
            font-size: 18px;
            margin-right: 12px;
        }
        
        /* Active & Hover Sidebar */
        .sidebar-nav .nav-link:hover {
            color: var(--payflow-purple);
            background: var(--payflow-purple-light);
        }
        .sidebar-nav .nav-link:hover i {
            color: var(--payflow-purple);
        }
        .sidebar-nav .nav-link:not(.collapsed) {
            color: var(--payflow-purple);
            background: var(--payflow-purple-light);
            border-left: 3px solid var(--payflow-purple);
        }
        .sidebar-nav .nav-link:not(.collapsed) i {
            color: var(--payflow-purple);
        }

        /* Sidebar Upgrade Box */
        .sidebar-upgrade {
            background: linear-gradient(135deg, #A855F7, #7B3DFF);
            border-radius: 12px;
            padding: 15px;
            margin: 20px 15px;
            color: #fff;
            text-align: center;
        }
        .sidebar-upgrade .btn-light {
            color: var(--payflow-purple);
            font-weight: bold;
            font-size: 12px;
            border-radius: 20px;
            padding: 6px 15px;
            margin-top: 10px;
        }

        /* Header Search */
        .header .search-bar {
            min-width: 300px;
        }
        .header .search-form input {
            border: 1px solid #EBECEF;
            border-radius: 20px;
            padding-left: 15px;
            background: #FAFAFA;
        }
        .header .search-form button {
            color: var(--payflow-text-muted);
        }

        /* Profile Nav */
        .header-nav .nav-profile img {
            border: 2px solid #EBECEF;
        }
        .header-nav .nav-profile span {
            color: var(--payflow-text-dark);
            font-weight: 700;
        }
        .user-handle {
            font-size: 11px;
            color: var(--payflow-text-muted);
            display: block;
            margin-top: -5px;
            font-weight: normal;
        }

        /* Cards Global */
        .card {
            border: 1px solid #EBECEF;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }

        .main {
            padding-top: 90px;
            padding-left: 20px;
            padding-right: 20px;
            transition: all 0.3s ease;
        }
        @media (min-width: 1200px) {
            .main {
                margin-left: 250px;
            }
        }

        /* Toggle Sidebar Behavior Fix */
        @media (min-width: 1200px) {
            .toggle-sidebar .main {
                margin-left: 0 !important;
            }
            .toggle-sidebar .sidebar {
                left: -250px !important;
            }
        }
        @media (max-width: 1199px) {
            .sidebar {
                left: -250px !important;
            }
            .toggle-sidebar .sidebar {
                left: 0 !important;
            }
        }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard.index') }}" class="logo d-flex align-items-center" style="width: 250px;">
                <img src="{{ $setting->logo ? asset('storage/' . $setting->logo) : asset('niceadmin/img/laravel.png') }}" alt="" style="max-height: 30px; margin-right: 10px;">
                <span class="d-none d-lg-block">{{ $setting->app_name }}</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn" style="color: var(--payflow-text-muted);"></i>
        </div><!-- End Logo -->

        <div class="search-bar ms-4 d-none d-md-block">
            <form class="search-form d-flex align-items-center" method="GET" action="{{ route('search.global') }}">
                <input type="text" name="search" placeholder="Pencarian Karyawan/Jabatan..." title="Masukkan kata kunci">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div><!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <!-- Notifications -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number" style="background-color: var(--payflow-purple) !important;">2</span>
                    </a>
                </li>

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('niceadmin/img/noprofil.png') }}" alt="Profile" class="rounded-circle">
                        <div class="ms-2 d-none d-md-block">
                            <span class="d-block">{{ Auth::user()->name }}</span>
                            <span class="user-handle">{{ '@' . strtolower(str_replace(' ', '', Auth::user()->name)) }}</span>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>{{ Auth::user()->role }}</span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard.show') }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="bi bi-box-arrow-right text-danger"></i>
                                <span class="text-danger">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.*') ? '' : 'collapsed' }}" href="{{ route('dashboard.index') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if (in_array(Auth::user()->role, ['Superadmin', 'Admin']))
                
                <li class="nav-heading mt-3" style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; margin: 0 15px 10px 15px;">Master Data</li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employee.*') ? '' : 'collapsed' }}" href="{{ route('employee.index') }}">
                        <i class='bx bx-user-circle'></i>
                        <span>Data Karyawan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('department.*') ? '' : 'collapsed' }}" href="{{ route('department.index') }}">
                        <i class='bx bx-building'></i>
                        <span>Departemen</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('position.*') ? '' : 'collapsed' }}" href="{{ route('position.index') }}">
                        <i class='bx bx-briefcase'></i>
                        <span>Jabatan</span>
                    </a>
                </li>

                <li class="nav-heading mt-3" style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; margin: 0 15px 10px 15px;">Kehadiran & Waktu</li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payroll_period.*') ? '' : 'collapsed' }}" href="{{ route('payroll_period.index') }}">
                        <i class='bx bx-calendar-event'></i>
                        <span>Periode Gaji</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('attendance.*') ? '' : 'collapsed' }}" href="{{ route('attendance.index') }}">
                        <i class='bx bx-time'></i>
                        <span>Rekap Absensi</span>
                    </a>
                </li>

                <li class="nav-heading mt-3" style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; margin: 0 15px 10px 15px;">Transaksi & Penggajian</li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('payroll.*') ? '' : 'collapsed' }}" href="{{ route('payroll.index') }}">
                        <i class='bx bx-wallet'></i>
                        <span>Proses Penggajian</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('allowance.*') ? '' : 'collapsed' }}" href="{{ route('allowance.index') }}">
                        <i class='bx bx-plus-circle'></i>
                        <span>Tunjangan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('deduction.*') ? '' : 'collapsed' }}" href="{{ route('deduction.index') }}">
                        <i class='bx bx-minus-circle'></i>
                        <span>Potongan</span>
                    </a>
                </li>

                <li class="nav-heading mt-3" style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700; margin: 0 15px 10px 15px;">Laporan</li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.*') ? '' : 'collapsed' }}" href="{{ route('report.index') }}">
                        <i class='bx bx-bar-chart-alt-2'></i>
                        <span>Laporan Penggajian</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role === 'Employee')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('my_payroll.*') ? '' : 'collapsed' }}" href="{{ route('my_payroll.index') }}">
                        <i class='bx bx-wallet'></i>
                        <span>Slip Gaji Saya</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role == 'Superadmin')
                <li class="nav-item mt-4">
                    <a class="nav-link {{ request()->routeIs('user.*') ? '' : 'collapsed' }}" href="{{ route('user.index') }}">
                        <i class='bx bx-user-pin'></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('setting.*') ? '' : 'collapsed' }}" href="{{ route('setting.index') }}">
                        <i class='bx bx-cog'></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                </li>
            @endif

            <li class="nav-item mt-auto">
                <a class="nav-link collapsed text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class='bx bx-log-out text-danger'></i>
                    <span>Keluar</span>
                </a>
            </li>
        </ul>
    </aside>

    <main id="main" class="main">
        <!-- Render page specific content here -->
        {{ $slot }}
    </main>

    @stack('modals')

    {{-- modal delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <form action="" method="post" id="form-delete" class="w-100">
                @csrf
                @method('delete')
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <i class="bi bi-exclamation-circle text-danger fs-1 mb-3 d-block"></i>
                        <h6 class="fw-bold">Hapus Data?</h6>
                        <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus data ini? Aksi ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer justify-content-center bg-light border-0">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-danger">Ya, hapus!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- modal logout --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-box-arrow-right text-danger fs-1 mb-3 d-block"></i>
                    <h6 class="fw-bold">Keluar</h6>
                    <p class="text-muted small mb-0">Apakah Anda yakin ingin keluar dari sistem?</p>
                </div>
                <div class="modal-footer justify-content-center bg-light border-0">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">Batal</button>
                    <a href="{{ route('login.logout') }}" class="btn btn-sm" style="background-color: var(--payflow-purple); color:#fff;">Ya, Keluar!</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('niceadmin/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('niceadmin/vendor/parsley/parsley.min.js') }}"></script>
    <script src="{{ asset('niceadmin/vendor/sweetalert2/sweetalert2@11') }}"></script>
    <script src="{{ asset('niceadmin/vendor/dataTables/js/dataTables.js') }}"></script>
    <script src="{{ asset('niceadmin/vendor/dataTables/js/dataTables.bootstrap5.js') }}"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('niceadmin/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('niceadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('niceadmin/vendor/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('niceadmin/js/main.js') }}"></script>

    <script>
        // Init DataTable
        if(document.getElementById('data-table')){
            let dataTable = new DataTable('#data-table', {
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100]
            });
        }
    </script>
    
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{!! session("success") !!}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    @endif
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{!! session("error") !!}',
            showConfirmButton: true
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
