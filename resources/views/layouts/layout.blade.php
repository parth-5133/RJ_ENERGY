@php
    // Instantiate the controller and call the method
    $controller = new App\Http\Controllers\Web\LayoutController();
    $asideMenu = $controller->showMenu();
    $navProfile = $controller->showProfile();
@endphp
<!DOCTYPE html>
<html lang="en" class="light-style layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="/assets/" data-template="horizontal-menu-template">

<head>
    @include('includes.head')
</head>

<body>
    <!-- Start Preloader Content -->
    <div id="preloader" class="preloader-wrap" style="display: none;">
        <div class="preloader">
            <img src="{{ asset('assets/img/illustrations/loader.gif') }}" alt="Loader">
        </div>
    </div>
    <!-- End Preloader Content -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        {{ $navProfile }}
        {{-- @include('includes.nav') --}}
        <div class="layout-page">
            <div class="content-wrapper">

                {{ $asideMenu }}
                {{-- @include('includes.aside') --}}
                @yield('content')
                @include('offcanvas')
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
    <div class="session_time_out" id="sessionTimeOutTemplate" style="display: none;">
        <div class="session_time_out_box_inner">
            <div>Sorry, your session timed out after a long time of inactivity. Please click on the link below to
                login again.</div>
        </div>
    </div>
    </div>

    <!-- horizontal menu js -->
    <script src="{{ asset('assets/vendor/js/horizontal_menu.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/vendor/js/main.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.5.0/remixicon.min.css"
        integrity="sha512-T7lIYojLrqj7eBrV1NvUSZplDBi8mFyIEGFGdox8Bic92Col3GVrscbJkL37AJoDmF2iAh81fRpO4XZukI6kbA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- DataTables JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/tables-datatables-advanced.js') }}"></script>
    <!-- Chart JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>

</body>

</html>
