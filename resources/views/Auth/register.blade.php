<!DOCTYPE html>
<html lang="en" class="light-style layout-compact layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Register | RJ Energy</title>

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

                                <form id="formRegister" method="POST" action="{{ route('registerPost') }}">
                                    @csrf

                                    <!-- Role Selection -->
                                    <div class="form-floating form-floating-outline mb-3">
                                        <select class="form-select @error('role') is-invalid @enderror" id="role"
                                            name="role">
                                            <option value="">Select role</option>
                                            <option value="1">
                                                Administrator</option>
                                            <option value="2">
                                                Employee</option>
                                            <option value="3">
                                                Customer</option>
                                            <option value="4">
                                                Accountant</option>
                                        </select>
                                        <label for="role">Register As</label>
                                        <span class="text-danger" id="role-error"></span>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="Enter email"
                                            value="{{ old('email') }}" />
                                        <label for="email">Email</label>
                                        <span class="text-danger" id="email-error"></span>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Aadhaar (Optional) -->
                                    <div class="form-floating form-floating-outline mb-3">
                                        <input type="text"
                                            class="form-control @error('aadhaar') is-invalid @enderror" id="aadhaar"
                                            name="aadhaar" placeholder="Aadhaar Number" maxlength="12"
                                            value="{{ old('aadhaar') }}" />
                                        <label for="aadhaar">Aadhaar Number (Optional)</label>
                                        <span class="text-danger" id="aadhaar-error"></span>
                                        @error('aadhaar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3 position-relative">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" placeholder="Enter password" />
                                            <label for="password">Password</label>
                                            <i class="mdi mdi-eye-off-outline password-icon toggle-password position-absolute"
                                                style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                            <span class="text-danger" id="password-error"></span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3 position-relative">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" class="form-control" id="confirm_password"
                                                name="confirm_password" placeholder="Confirm password" />
                                            <label for="confirm_password">Confirm Password</label>
                                            <i class="mdi mdi-eye-off-outline password-icon toggle-password position-absolute"
                                                style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                                            <span class="text-danger" id="confirm_password-error"></span>
                                        </div>
                                    </div>

                                    <!-- Submit -->
                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100" type="submit">Register</button>
                                    </div>

                                    <!-- Already have account -->
                                    <div class="text-center mt-2">
                                        <span>Already have an account?</span>
                                        <a href="{{ route('login') }}" class="ms-1 text-primary">Login here</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <!-- JS Includes -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    @if (session()->has('error'))
        <script>
            toastr.error("{{ session('error') }}");
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Password Toggle
            $('.toggle-password').click(function() {
                const input = $(this).siblings('input');
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                $(this).toggleClass('mdi-eye-outline mdi-eye-off-outline');
            });

            // Validation
            $('#formRegister').validate({
                rules: {
                    role: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 50
                    },
                    aadhaar: {
                        digits: true,
                        minlength: 12,
                        maxlength: 12
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        equalTo: '#password'
                    }
                },
                messages: {
                    role: "Please select a role",
                    email: "Please enter a valid email address",
                    aadhaar: {
                        digits: "Enter only digits",
                        minlength: "Aadhaar must be 12 digits",
                        maxlength: "Aadhaar must be 12 digits"
                    },
                    password: {
                        required: "Password is required",
                        minlength: "Password must be at least 6 characters"
                    },
                    confirm_password: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    }
                },
                errorPlacement: function(error, element) {
                    let id = element.attr('id') + '-error';
                    $('#' + id).text(error.text());
                    element.addClass('is-invalid');
                },
                success: function(label, element) {
                    let id = $(element).attr('id') + '-error';
                    $('#' + id).text('');
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>
