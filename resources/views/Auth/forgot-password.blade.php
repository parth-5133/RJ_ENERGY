<!DOCTYPE html>

<html lang="en" class="light-style layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Forgot Password | Skysphere Infosoft LLP</title>

    <meta name="description" content="" />
    <!-- Fonts -->
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/Favicon.png') }}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

</head>

<body>
    <!-- Content -->
    <section class="background-radial-gradient overflow-hidden">
        <div
            class="container px-4 py-5 px-md-5 text-center text-lg-start d-flex align-items-center justify-content-center">
            <div class="row justify-content-center align-items-center">
                <div class="col-xxl-5 col-md-6 mb-5 mb-xxl-0 text-xxl-start text-lg-center">
                    <h1 class="display-5 fw-bold ls-tight" style="color: #fff">
                        Forgot <br>
                        <span style="color: hsl(286, 100%, 73%)">Password??</span>
                    </h1>
                    <p class="mb-4 opacity-70" style="color: #fff">
                        Enter your e-mail address below to reset your password.
                    </p>
                </div>

                <div class="col-xxl-7 col-md-7 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass">
                        <div class="card-body">
                            <div class="app-brand justify-content-center mt-2 mb-5">
                                <a>
                                    <img src="{{ asset('assets/img/favicon/Full_Logo.jpg') }}" alt="RJ Energy Logo"
                                        style="width: 180px; max-height: 120px; object-fit: contain;">
                                </a>
                            </div>

                            <div class="card-body pt-3 p-0">
                                <form action="javascript:void(0)" id="commonform" name="commonform"
                                    class="form-horizontal" method="POST" enctype="multipart/form-data">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <input type="text" class="form-control" id="email" name="email"
                                            placeholder="Enter your email" autofocus />
                                        <label>Email</label>
                                        <div id="email-error" class="invalid-feedback"></div>
                                    </div>
                                    <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
                                </form>
                                <div class="text-center">
                                    <a href="{{ Route('login') }}"
                                        class="d-flex align-items-center justify-content-center mt-3">
                                        <i class="mdi mdi-chevron-left scaleX-n1-rtl mdi-24px"></i>
                                        Back to login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Include Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            // Validation rules and messages
            $("#commonform").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address.",
                        email: "Please enter a valid email address."
                    }
                },
                errorPlacement: function(error, element) {
                    var errorId = element.attr("name") + "-error";
                    $("#" + errorId).text(error.text());
                    $("#" + errorId).show();
                    element.addClass("is-invalid");
                },
                success: function(label, element) {
                    var errorId = $(element).attr("name") + "-error";
                    $("#" + errorId).text("");
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {
                    var postData = {
                        email: $("#email").val(),
                    };
                    var url = "{{ config('apiConstants.AUTH_URL.FORGOT_PASSWORD') }}";
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: postData,
                        success: function(response) {
                            if (response.status === 200) {
                                toastr.success(response.message, "Success", {
                                    closeButton: true,
                                    progressBar: true,
                                    positionClass: "toast-top-right",
                                });
                            }
                        },
                        error: function(xhr) {
                            // Clear any previous error messages
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').text('');

                            var response = xhr.responseJSON;
                            if (response.message) {
                                toastr.error(response.message, "Error", {
                                    closeButton: true,
                                    progressBar: true,
                                    positionClass: "toast-top-right",
                                });
                            }
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    var inputField = $('#' + key);
                                    var errorField = $('#' + key + '-error');

                                    inputField.addClass('is-invalid');
                                    errorField.text(value).removeClass('d-none');
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
