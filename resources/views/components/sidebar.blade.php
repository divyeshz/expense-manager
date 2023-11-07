<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-1">{{  str_replace('_',' ',env('APP_NAME')) }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if(Request::path() == 'dashboard') active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    {{-- Account Tab --}}
    <li class="nav-item @if(Request::path() == 'account') active @endif">
        <a class="nav-link" href="{{ route('account') }}">
            <i class="fas fa-fw fa-solid fa-users"></i>
            <span>Account</span></a>
    </li>

    {{-- Category Tab --}}
    <li class="nav-item @if(Request::path() == 'category') active @endif">
        <a class="nav-link" href="{{ route('category') }}">
            <i class="fas fa-vector-square"></i>
            <span>Category</span></a>
    </li>

    {{-- Category Tab --}}
    <li class="nav-item @if(Request::path() == 'anotherAccount') active @endif">
        <a class="nav-link" href="{{ route('anotherAccount') }}">
            <i class="fas fa-at"></i>
            <span>Another Account</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
