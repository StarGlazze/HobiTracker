<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HobiTracker')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('./admin/images/logos/favicon-v2.png')}}" />
    <link rel="stylesheet" href="{{ asset('./admin/css/styles.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('./admin/css/hover-effects.css')}}" />
    <link rel="stylesheet" href="{{ asset('./admin/css/achievement.css')}}" />
    <link rel="stylesheet" href="{{ asset('./admin/css/hobi-custom.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!-- Sidebar Start -->
        @include('admin.layouts.partials.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('admin.layouts.partials.navbar')
            <!--  Header End -->
            <div class="body-wrapper-inner">
                <div class="container-fluid" style="padding-top: 100px">
                    <!--  Row 1 -->
                    @yield('content')
                    @include('admin.layouts.partials.footer')
                </div>
            </div>
        </div>
    </div>

    <!--  Scripts -->
    <script src="{{ asset('./admin/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('./admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('./admin/js/sidebarmenu.js')}}"></script>
    <script src="{{ asset('./admin/js/app.min.js')}}"></script>
    <script src="{{ asset('./admin/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
    <script src="{{ asset('./admin/libs/simplebar/dist/simplebar.js')}}"></script>
    <script src="{{ asset('./admin/js/dashboard.js')}}"></script>
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('./admin/js/hobi-custom.js')}}"></script>
    @yield('scripts')
</body>

</html>
