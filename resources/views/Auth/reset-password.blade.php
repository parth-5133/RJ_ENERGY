<!DOCTYPE html>

<html lang="en" class="light-style layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | RJ ENERGY</title>
    <meta name="description" content="" />
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

    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a class="app-brand-link">
                            <!-- <img src="{{ asset('assets/img/favicon/Logo.png') }}" class="app-brand-logo demo me-1"
                                width="40"></img>
                            <img src="{{ asset('assets/img/favicon/Logo_text.png') }}" class="app-brand-text demo"
                                width="140"></img> -->
                            <img src="{{ asset('assets/img/favicon/Full_Logo.png') }}" class="app-brand-logo"></img>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-2">
                        <h4 class="mb-2">Confirm Your password 🔒</h4>
                        <p class="mb-4">Make sure your passwords same in both field !</p>

                        <form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal"
                            method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="{{ request()->route('token') }}">
                            <input type="hidden" name="email" value="{{ request()->route('email') }}">
                            <div class="mb-3 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password" class="form-control" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password" />
                                        <label for="password">Password</label>
                                        <div class="invalid-feedback" id="password-error"></div>
                                        <i class="mdi mdi-eye-off-outline password-icon toggle-password"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" id="password_confirmation" class="form-control"
                                            name="password_confirmation"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password_confirmation" />
                                        <label for="password">Confirm Password</label>
                                        <div class="invalid-feedback" id="password_confirmation-error"></div>
                                        <i class="mdi mdi-eye-off-outline password-icon toggle-password"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms-conditions"
                                        name="terms" />
                                    <label class="form-check-label" for="terms-conditions">
                                        I agree to
                                        <a href="javascript:void(0);">privacy policy & terms</a>
                                    </label>
                                </div>
                            </div>
                            <button class="btn btn-primary d-grid w-100">login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {

            $('.toggle-password').click(function() {
                var passwordField = $('#password');
                var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('mdi-eye-off-outline mdi-eye-outline');
            });

            // Initialize validation rules
            $("#commonform").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    token: {
                        required: true
                    }
                },
                messages: {
                    password: {
                        required: "Please enter a new password.",
                        minlength: "Password must be at least 6 characters long."
                    },
                    password_confirmation: {
                        required: "Please confirm your password.",
                        minlength: "Password confirmation must be at least 6 characters long.",
                        equalTo: "Passwords do not match."
                    },
                    email: {
                        required: "Please enter your email address.",
                        email: "Please enter a valid email address."
                    },
                    token: {
                        required: "Invalid token. Please try resetting your password again."
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
                    // Prepare post data
                    var postData = {
                        password: $("#password").val(),
                        password_confirmation: $("#password_confirmation").val(),
                        token: $("input[name='token']").val(),
                        email: $("input[name='email']").val()
                    };

                    var url = "{{ config('apiConstants.AUTH_URL.RESET_PASSWORD') }}";

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: postData,
                        success: function(response) {
                            if (response.status === 200) {
                                window.location.href = "{{ url('/') }}";
                            }
                        },
                        error: function(xhr) {
                            $('.form-control').removeClass('is-invalid');
                            $('.invalid-feedback').text('').addClass('d-none');
                            var response = xhr.responseJSON;
                            if (response.errors) {
                                $.each(response.errors, function(key, value) {
                                    var inputField = $('#' + key);
                                    var errorField = $('#' + key + '-error');
                                    inputField.addClass('is-invalid');
                                    errorField.text(value[0]).removeClass('d-none');
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
