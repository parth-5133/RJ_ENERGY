<!DOCTYPE html>

<html lang="en" class="light-style layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | RJ Energy</title>
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
    <section class="background-radial-gradient overflow-hidden">
        <div
            class="container px-4 py-5 px-md-5 text-center text-lg-start d-flex align-items-center justify-content-center">
            <div class="row justify-content-center align-items-center">
                <div class="col-xxl-5 col-md-6 mb-5 mb-xxl-0 text-xxl-start text-lg-center">
                    <h1 class="display-5 fw-bold ls-tight" style="color: #fff">
                        Welcome to <br>
                        <span style="color: hsl(286, 100%, 73%)">RJ Energy !!</span>
                    </h1>
                    <p class="mb-4 opacity-70" style="color: #fff">
                        Sign-in to access your dashboard
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
                            <div class="card-body pt-5 p-0">
                                <form id="formAuthentication" class="mb-3" method="POST"
                                    action="{{ route('loginPost') }}">
                                    @csrf
                                    <!-- Email field -->
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="Enter your email or username"
                                            autofocus value="{{ old('email') }}" />
                                        <label for="email">Email or Username</label>
                                        <span class="text-danger" id="email-error"></span>
                                        <!-- Error message container -->
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password field with toggle visibility -->
                                    <div class="mb-3">
                                        <div class="form-password-toggle">
                                            <div class="input-group input-group-merge">
                                                <div class="form-floating form-floating-outline">
                                                    <input type="password" id="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password"
                                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                        aria-describedby="password" value="{{ old('password') }}" />
                                                    <label for="password">Password</label>
                                                    <div>
                                                        <i
                                                            class="mdi mdi-eye-off-outline password-icon toggle-password"></i>
                                                    </div>
                                                    <span class="text-danger" id="password-error"></span>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remember me and forgot password -->
                                    <div class="mb-3 d-flex justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember-me" />
                                            <label class="form-check-label" for="remember-me"> Remember Me </label>
                                        </div>
                                        <a href="{{ Route('forgotPassword') }}" class="float-end mb-1">
                                            <span>Forgot Password?</span>
                                        </a>
                                    </div>

                                    <!-- Submit button -->
                                    <div class="mb-3">
                                        <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                                    </div>
                                    <!-- Registration link -->
                                    <div class="text-center mt-2">
                                        <span>Don't have an account?</span>
                                        <a href="{{ route('register') }}" class="ms-1 text-primary">Register here</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>


    @if (session()->has('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('.toggle-password').click(function() {
                var passwordField = $('#password');
                var type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                $(this).toggleClass('mdi-eye-off-outline mdi-eye-outline');
            });

            $("#formAuthentication").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        maxlength: 50,
                    },
                    password: {
                        required: true,
                        maxlength: 50,
                    },
                },
                messages: {
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address",
                        maxlength: "Email cannot be more than 50 characters",
                    },
                    password: {
                        required: "Password is required",
                        maxlength: "Password cannot be more than 50 characters",
                    },
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
                    form.submit();
                }
            });
        });
    </script>


</body>

</html>
