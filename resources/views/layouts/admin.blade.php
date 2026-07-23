<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | বইঘর Admin</title>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <style>
        :root { --primary: #6366f1; }
        body, .nav-sidebar .nav-item > .nav-link, .brand-link { font-family: 'Hind Siliguri', sans-serif !important; }
        .brand-link { background: linear-gradient(135deg, #4f46e5, #6366f1) !important; }
        .brand-text { font-weight: 700 !important; font-size: 1.1rem !important; }
        .sidebar { background: #1e293b !important; }
        .nav-sidebar .nav-item>.nav-link { color: #94a3b8 !important; border-radius: 8px; margin: 2px 8px; }
        .nav-sidebar .nav-item>.nav-link.active, .nav-sidebar .nav-item>.nav-link:hover { background: rgba(99,102,241,.2) !important; color: #a5b4fc !important; }
        .nav-sidebar .nav-item>.nav-link .nav-icon { color: #6366f1 !important; }
        .content-wrapper { background: #f1f5f9 !important; }
        .card { border-radius: 12px !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 1px 3px rgba(0,0,0,.06) !important; }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5) !important; border: none !important; }
        .bg-primary { background: linear-gradient(135deg, #6366f1, #4f46e5) !important; }
        .small-box { border-radius: 12px !important; }
        .table td, .table th { font-size: .88rem; }
        .main-header { border-bottom: 1px solid #e2e8f0 !important; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" target="_blank"><i class="fas fa-external-link-alt"></i> সাইট দেখুন</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle"></i> {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> লগআউট</button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-white">
            <i class="fas fa-book-open ml-2 mr-2" style="color:#a5b4fc"></i>
            <span class="brand-text font-weight-light">বইঘর Admin</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i><p>ড্যাশবোর্ড</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.books.index') }}" class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i><p>বই ম্যানেজমেন্ট</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i><p>ক্যাটাগরি</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.authors.index') }}" class="nav-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-edit"></i><p>লেখক</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i><p>ইউজার</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.withdrawals.index') }}" class="nav-link {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>উইড্র অনুমোদন
                                @php $pw = \App\Models\WithdrawalRequest::where('status','pending')->count(); @endphp
                                @if($pw > 0)<span class="right badge badge-danger">{{ $pw }}</span>@endif
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.ads') }}" class="nav-link {{ request()->routeIs('admin.settings.ads*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-ad"></i><p>বিজ্ঞাপন</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i><p>সাইট সেটিং</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                @endif
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>বইঘর Admin Panel</strong> · {{ now()->year }}
    </footer>
</div>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>
