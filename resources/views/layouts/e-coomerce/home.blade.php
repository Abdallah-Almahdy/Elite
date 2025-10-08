<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>AdminLTE 3 | Top Navigation</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('AdminLTE-3-RTL/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('files/css/nab-bar.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-3-RTL/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaijaan+2:wght@400..800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Baloo Bhaijaan 2", sans-serif;

            .hover-opacity:hover {
                opacity: 0.75;
                transition: all 0.2s ease-in-out;
            }

            .hover-color:hover {
                color: #ffc107 !important;
                transform: scale(1.1);
                transition: 0.2s ease-in-out;
            }

     .footer-link {
  color: #ccc;
  text-decoration: none;
  display: block;
  padding: 4px 0;
  transition: color 0.2s ease, transform 0.2s ease;
}
.footer-link:hover {
  color: #ffc107;
  transform: translateX(-3px);
}

.social-link {
  width: 42px;
  height: 42px;
  border: 1px solid #ffc107;
  border-radius: 50%;
  color: #fff;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease-in-out;
}
.social-link:hover {
  background-color: #ffc107;
  color: #000;
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

.underline {
  position: absolute;
  bottom: -5px;
  right: 0;
  width: 100%;
  height: 2px;
  background-color: #ffc107;
  border-radius: 2px;
}

/* RESPONSIVENESS */
@media (max-width: 768px) {
  footer .row {
    text-align: center !important;
  }
  .social-link {
    margin-bottom: 8px;
  }
  .footer-link {
    text-align: center;
  }
  footer img {
    width: 80px;
  }
  footer p {
    font-size: 0.9rem;
  }
}



        }
    </style>
    @yield('style')
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts.e-coomerce.nav-bar')
        <!-- /.navbar -->

        <!-- Content Wrapper. Contains page content -->
        <div style="text-align: right;
    background-color: rgb(250, 250, 250)" class="text-right content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->

            <!-- Main content -->
            <div class="p-0 content">
                <div class="container">
                    @yield('content')
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        @include('layouts.e-coomerce.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('AdminLTE-3-RTL/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE-3-RTL/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE-3-RTL/dist/js/adminlte.min.js') }}"></script>
    @yield('scripts')
</body>

</html>
