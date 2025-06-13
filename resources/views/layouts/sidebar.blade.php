<!-- Font Awesome versi 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    .sidebar-custom .nav-link,
    .sidebar-custom .sidebar-heading,
    .sidebar-custom .sidebar-brand-text {
        color: #fff !important;
    }
</style>

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #2e9323;">


    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand-text mx-3">Manajemen Aset</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Data
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->is('location*') ? 'active' : '' }}">
        <a class="nav-link " href="/locations">
            <i class="fa-solid fa-location-dot"></i>
            <span>Lokasi</span></a>
    </li>
    <li class="nav-item {{ request()->is('room*') ? 'active' : '' }}">
        <a class="nav-link " href="/rooms">
            <i class="fa-solid fa-house"></i>
            <span>Ruangan</span></a>
    </li>
    <li class="nav-item {{ request()->is('item*') ? 'active' : '' }}">
        <a class="nav-link " href="/item">
            <i class="fa-solid fa-list"></i>
            <span>Assets</span></a>
    </li>
    <li class="nav-item {{ request()->is('category*') ? 'active' : '' }}">
        <a class="nav-link " href="/category">
            <i class="fas fa-fw fa-table"></i>
            <span>Kategori</span></a>
    </li>

    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Manajemen Peminjaman
    </div>
    <li class="nav-item {{ request()->is('borrow*') ? 'active' : '' }}">
        <a class="nav-link " href="/borrow">
            <i class="fa-solid fa-table-cells-large"></i>
            <span>Daftar Peminjaman</span></a>
    </li>

    @if (Auth::user()->role === 'super_admin')
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Manajemen Pengguna
        </div>
        <li class="nav-item {{ request()->is('user') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.index') }}">
                <i class="fa-solid fa-users"></i>
                <span>Pengguna</span></a>
        </li>
        <li class="nav-item {{ request()->is('user/pending') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.pending') }}">
                <i class="fa-solid fa-user-clock"></i>
                <span>Permintaan Pendaftaran</span></a>
        </li>
        <li class="nav-item {{ request()->is('receptionist/create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('receptionist.create') }}">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Resepsionis</span></a>
        </li>
    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <a href="{{ url('/') }}">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </a>
    </div>

</ul>
