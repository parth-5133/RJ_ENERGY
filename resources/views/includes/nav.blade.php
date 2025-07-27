<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-6">
            <a class="app-brand-link" href="{{ url('/dashboard') }}">
                <img src="{{ is_string($company_logo) ? url($company_logo) : asset('assets/img/favicon/Full_Logo.png') }}"
                    class="app-brand-logo" style="width: auto;height: 40px;">

            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="mdi mdi-menu d-xl-block align-middle mdi-24px"></i>
            </a>
        </div>

        <div class="layout-menu-toggle navbar-nav align-items-xl-center d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="mdi mdi-menu mdi-24px"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                @if ($role_code == config('roles.SUPERADMIN'))
                    <div class="me-2 d-none d-md-block">
                        <label for="companies" class="text-primary ms-1 me-8 text-nowrap">Current Company</label>
                        <select class="form-select custom-dropdown" name="companies" id="companies">
                            @foreach ($companies as $company)
                                <option value="{{ $company['id'] }}"
                                    {{ $company['id'] == $companyId ? 'selected' : '' }}>
                                    {{ $company['legal_name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @elseif ($role_code == config('roles.EMPLOYEE') || $role_code == config('roles.ADMIN'))
                    <div class="me-2 d-none d-md-block">
                        <label for="companies" class="text-primary ms-1 me-8 text-nowrap">Current Company</label>
                        <select class="form-select custom-dropdown" disabled>
                            <option selected>{{ $company_name }}</option>
                        </select>
                    </div>
                @endif
                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 bg-white">
                    <a class="nav-link btn btn-text-secondary rounded btn-icon dropdown-toggle hide-arrow waves-effect waves-light"
                        href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <i class="mdi mdi-bell-outline mdi-24px"></i>
                        @if ($notifications->count() > 0 && $notifications->filter(fn($notification) => $notification['read'] == 0)->count() > 0)
                            <span
                                class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h6 class="mb-0 me-2">Notifications</h6>
                                <span
                                    class="badge rounded bg-label-primary me-auto">{{ $notifications->filter(fn($notification) => $notification['read'] == 0)->count() }}
                                    New
                                </span>
                                <div class="d-flex align-items-center">
                                    @if ($notifications->count() > 0)
                                        <a href="javascript:void(0)"
                                            class="btn btn-text-success rounded btn-icon dropdown-notifications-all waves-effect waves-light"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            aria-label="Mark all as read" data-bs-original-title="Mark all as read"
                                            onclick="markAllAsRead()">
                                            <i id="notification-icon"
                                                class="mdi {{ $notifications->filter(fn($notification) => $notification['read'] == 0)->count() > 0 ? 'mdi-email-outline' : 'mdi-email-open-outline' }} mdi-24px text-success"></i>
                                        </a>
                                    @endif
                                    @if ($notifications->count() > 0)
                                        <a href="javascript:void(0)"
                                            class="btn btn-text-danger rounded btn-icon dropdown-notifications-all waves-effect waves-light"
                                            data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete All"
                                            data-bs-original-title="Delete All" onclick="delateNotification(0)">
                                            <i id="notification-trash-icon"
                                                class="mdi mdi-trash-can-outline mdi-24px text-danger"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container ps">
                            <ul class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    <li
                                        class="list-group-item list-group-item-action dropdown-notifications-item waves-effect">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <img src="../assets/img/avatars/1.png" alt=""
                                                        class="w-px-40 h-auto rounded-circle">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1"
                                                onclick="markAllAsRead({{ $notification['id'] }})">
                                                <h6 class="small mb-1">{{ $notification['title'] }}</h6>
                                                <small
                                                    class="mb-1 d-block text-body">{{ $notification['message'] }}</small>
                                                <div class="d-flex justify-content-between align-items-center py-3">
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($notification['created_at'])->format('d/m/Y H:i') }}</small>
                                                    @if ($notification['has_view_button'] == 1)
                                                        <a href="{{ url('/kanban/view') }}"
                                                            class="rounded waves-effect waves-light"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            aria-label="View Task"
                                                            data-bs-original-title="View Task">View
                                                            Task</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                                <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                    onclick="delateNotification({{ $notification['id'] }})">
                                                    <span class="mdi mdi-close"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </li>
                <!--/ Notification -->

                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                        data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <div class="avatar avatar-online">
                                    <img src="{{ isset($profile_img) && $profile_img ? asset('storage/profile_images/' . $profile_img) : asset('assets/img/avatars/1.png') }}"
                                        alt="Profile Image" class="w-px-40 h-auto rounded" />
                                </div>
                            </div>
                            <div class="flex-grow-1 me-2">
                                <h6 class="mb-0">{{ $name }}</h6>
                                <small class="text-muted">{{ $role_name }}</small>
                            </div>
                            <div>
                                <i class="mdi mdi-chevron-down"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-1 py-2">
                        <li>
                            <a class="dropdown-item pb-2 mb-1" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2 pe-1">
                                        <div class="avatar avatar-online">
                                            <img src="{{ isset($profile_img) && $profile_img ? asset('storage/profile_images/' . $profile_img) : asset('assets/img/avatars/1.png') }}"
                                                alt="Profile Image" class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $name }}</h6>
                                        <small class="text-muted"></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <!-- My Profile Button -->
                            <a class="dropdown-item myProfileLink">
                                <i class="mdi mdi-account-outline me-1 mdi-20px"></i>
                                <span class="align-middle">My Profile</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                onClick="fnAddEdit(this,'{{ url('change/password') }}', 0, 'Change Password')">
                                <i class="mdi mdi-lock-outline me-1 mdi-20px"></i>
                                <span class="align-middle">Change Password</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <div class="d-grid px-4 pt-2 pb-1">
                                <a class="btn d-flex waves-effect waves-light" style="background-color: #f7dfff"
                                    onclick="logout()" target="_blank">
                                    <i class="mdi mdi-logout text-danger"></i>
                                    <small class="align-middle ms-1 text-danger">Logout</small>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Pusher -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        const rolecode = localStorage.getItem("user_data")

        const uuid = getIdFromToken();
        if (uuid) {
            const profileUrl = `{{ url('/profile') }}?id=${uuid}`;
            $(".myProfileLink").attr("href", profileUrl);
        } else {
            console.error("UUID not found, unable to set profile link.");
        }

        var selectedYear = $('#globalYearFilter').val();
        if (selectedYear) {
            callYearApi(selectedYear, false);
        }

        var selectedCompanies = $('#companies').val();
        if (selectedCompanies) {
            callCompaniesApi(selectedCompanies, false);
        }

    });

    $(document).on('change', '#globalYearFilter', function() {
        var selectedYear = $(this).val();
        callYearApi(selectedYear, true);
    });

    $(document).on('change', '#companies', function() {
        var selectedCompanies = $(this).val();
        callCompaniesApi(selectedCompanies, true);
    });

    function logout() {

        setCookie("access_token", null, -1);
        setCookie("user_data", null, -1);

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.AUTH_URL.LOGOUT') }}", null, false, false, function(response) {
            if (response.status === 200) {
                console.log('User logged out.');
                window.location.href = '/';
            } else {
                ShowMsg("bg-warning", 'Failed to logged out.');
            }
        });
    }

    function markAllAsRead(id) {
        var requestData = {
            id: id
        };

        fnCallAjaxHttpPostEvent("{{ config('apiConstants.NOTIFICATION_URLS.NOTIFICATION_READ') }}", requestData, false,
            false,
            function(response) {
                if (response.status === 200) {
                    const icon = $("#notification-icon");
                    if (icon.length) {
                        icon.removeClass("mdi-email-outline").addClass("mdi-email-open-outline");
                    }
                    if (response.returnValue == 1) {} else {
                        ShowMsg("bg-success", "All notifications marked as read.");
                    }
                    refreshNavbar();
                } else {
                    ShowMsg("bg-warning", 'Failed to mark all as read.');
                }
            });
    }

    function delateNotification(id) {
        var Name = "Notification";
        var id = id;

        fnShowConfirmDeleteDialog(Name, fnDeleteRecord, id,
            "{{ config('apiConstants.NOTIFICATION_URLS.NOTIFICATION_DELETE') }}");
    }

    function callYearApi(yearId, isReload) {
        var requestData = {
            year: yearId
        };

        fnCallAjaxHttpPostEvent("{{ config('apiConstants.YEARS_URLS.YEARS') }}",
            requestData, false, false,
            function(response) {
                if (response.success === true) {
                    if (isReload === true) {
                        location.reload();
                    }
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
    }

    function callCompaniesApi(companyId, isReload) {
        var requestData = {
            companiesId: companyId
        };

        fnCallAjaxHttpPostEvent("{{ config('apiConstants.YEARS_URLS.COMPANIES') }}",
            requestData, false, false,
            function(response) {
                if (response.success === true) {
                    if (isReload === true) {
                        location.reload();
                    }
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
    }

    async function refreshNavbar() {
        fetch('{{ url('/navbar') }}')
            .then(response => {
                if (response.ok) return response.text();
                throw new Error('Failed to fetch navbar');
            })
            .then(html => {
                const navElement = document.querySelector('nav.layout-navbar');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const newNavElement = tempDiv.querySelector('nav.layout-navbar');

                if (newNavElement) {
                    navElement.outerHTML = newNavElement.outerHTML;

                    const scripts = Array.from(newNavElement.querySelectorAll('script'));
                    scripts.forEach(script => {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                        document.body.removeChild(newScript);
                    });
                    console.log("Navbar refreshed after notification action");
                }
            })
            .catch(error => console.error("Failed to refresh navbar:", error));
    }

    setInterval(refreshNavbar, 30000);
</script>
<script>
    // Enable Pusher logging - remove in production
    Pusher.logToConsole = true;

    // Initialize Pusher
    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        forceTLS: true
    });

    // Subscribe to channel
    const channel = pusher.subscribe('Notifications.{{ auth()->user()->id }}');

    // Load notification sound
    const notificationSound = new Audio('/sounds/notify.wav');

    // Listen for message.sent event
    channel.bind('message.sent', function(data) {
        toastr.info(data.message, 'New Message', {
            closeButton: true,
            positionClass: "toast-bottom-right",
            timeOut: 0,
            extendedTimeOut: 0
        });

        // Play notification sound
        notificationSound.play().catch(e => {
            console.warn('Notification sound play prevented:', e);
        });
    });
</script>
