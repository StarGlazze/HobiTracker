<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flexy Free Bootstrap Admin Template by WrapPixel</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('./admin/images/logos/favicon.png')}}" />
    <link rel="stylesheet" href="{{ asset('./admin/css/styles.min.css')}}" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        @include('layouts.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('layouts.navbar')
            <!--  Header End -->
            <div class="body-wrapper-inner">
                <div class="container-fluid" style="padding-top: 100px">
                    <!--  Row 1 -->
                    @yield('content')
                    @include('layouts.footer')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('./admin/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('./admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('./admin/js/sidebarmenu.js')}}"></script>
    <script src="{{ asset('./admin/js/app.min.js')}}"></script>
    <script src="{{ asset('./admin/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
    <script src="{{ asset('./admin/libs/simplebar/dist/simplebar.js')}}"></script>
    <script src="{{ asset('./admin/js/dashboard.js')}}"></script>
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
