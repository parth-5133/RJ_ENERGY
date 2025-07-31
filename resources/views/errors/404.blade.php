<!DOCTYPE html>

<html lang="en" class="light-style layout-wide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>404 | RJ ENERGY</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/Favicon.png') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />

</head>

<body>
    <div class="misc-wrapper">
        <div class="d-flex justify-content-center">
            <div class="d-flex flex-column align-items-center">
                <img src={{ asset('assets/img/illustrations/404.png') }} alt="misc-error" class="misc-model img-fluid z-1" width="600" />
                <div>
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary text-center">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
