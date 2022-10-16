<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name') }} | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- datatable -->
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/plugins/toastr/toastr.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ config('app.asset_url') }}/vendor/adminlte/dist/css/adminlte.min.css">
  @yield('css')<!-- all css in child views -->
</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="{{ config('app.asset_url') }}/vendor/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-sign-out-alt"></i>
                <!--<span class="btn btn-danger navbar-badge">Sign-Out</span>-->
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a class="dropdown-item text-center" href="{{ route('logout') }}" 
                    onclick="event.preventDefault();
                    document.getElementById('admin-logout-form').submit();">
                               
                    <!-- Message Start -->
                    <div class="btn btn-danger btn-lg">
                        Sign-Out
                    </div>
                </a>
                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href='#' class="brand-link">
      <img src="{{ config('app.asset_url') }}/vendor/adminlte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-copy"></i>
                      <p>
                        Masters
                        <i class="fas fa-angle-left right"></i>
                        <span class="badge badge-info right">3</span>
                      </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{ route('product.list') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Products</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('customer.list') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Customer List</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('billing.list') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Billing List</p>
                          </a>
                        </li>
                        
                        
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-copy"></i>
                      <p>
                        Payments
                        <i class="fas fa-angle-left right"></i>
                        <span class="badge badge-info right">2</span>
                      </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{ route('payment.list') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Payment Report</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('payment.add') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Payment Add</p>
                          </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item menu-open">
                    <a href="{{ route('customer.view') }}" class="nav-link active">
                      <p>
                        Customer Report
                      </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="{{ route('bill.generate') }}" class="nav-link active">
                        <p>
                          Generate Bill
                        </p>
                    </a>
                </li>
            
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  @yield('body')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="#"></a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
    <div class="float-left d-none d-sm-inline-block">
      <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Attribution-NonCommercial 4.0 International License</a>.
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- InputMask -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/moment/moment.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- overlayScrollbars -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/dist/js/adminlte.js"></script>
<!-- DataTables  & Plugins -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/jszip/jszip.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="{{ config('app.asset_url') }}/vendor/adminlte/plugins/toastr/toastr.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="{{ config('app.asset_url') }}/vendor/adminlte/dist/js/demo.js"></script>

@yield('scripts')<!-- all scripts in child views -->
</body>
</html>
