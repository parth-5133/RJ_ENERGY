@extends('layouts.layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- Notifications -->
                    <h5 class="card-header">Notification Settings</h5>
                    <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-nowrap fw-medium text-black">Notification Type</th>
                                    <th class="text-nowrap fw-medium text-center text-black">Email</th>
                                    <th class="text-nowrap fw-medium text-center text-black">In app</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-nowrap text-heading">Leave Request Notification</td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="leave_request-email">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="leave_request-browser">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap text-heading">Attendance Request Notification</td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="attendance_request-email">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="attendance_request-browser">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap text-heading">New Task Assignment Notification</td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="task_assignment-email">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="task_assignment-browser">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-nowrap text-heading">Task Status Update Notification</td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="task_status_update-email">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input" type="checkbox" id="task_status_update-browser">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="submit" id="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            loadNotificationSettings();

            function loadNotificationSettings() {
                var url = "{{ config('apiConstants.NOTIFICATION_SETTINGS_URLS.NOTIFICATION_SETTINGS') }}";

                fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                    if (response.status === 200) {
                        var settings = response.data; // Corrected here
                        settings.forEach(function(setting) {
                            $('#' + setting.type + '-email').prop('checked', Boolean(setting
                                .email_enabled));
                            $('#' + setting.type + '-browser').prop('checked', Boolean(setting
                                .browser_enabled));
                        });
                    } else {
                        console.log('Failed to retrieve notification settings.');
                    }
                });
            }

            $('#submit').on('click', function() {

                var postData = {
                    notifications: [{
                            type: 'leave_request',
                            email: $('#leave_request-email').is(':checked'),
                            browser: $('#leave_request-browser').is(':checked')
                        },
                        {
                            type: 'attendance_request',
                            email: $('#attendance_request-email').is(':checked'),
                            browser: $('#attendance_request-browser').is(':checked')
                        },
                        {
                            type: 'task_assignment',
                            email: $('#task_assignment-email').is(':checked'),
                            browser: $('#task_assignment-browser').is(':checked')
                        },
                        {
                            type: 'task_status_update',
                            email: $('#task_status_update-email').is(':checked'),
                            browser: $('#task_status_update-browser').is(':checked')
                        },
                    ]
                };
                var url =
                    "{{ config('apiConstants.NOTIFICATION_SETTINGS_URLS.NOTIFICATION_SETTINGS_UPDATE') }}";

                fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning", 'The record could not be processed.');
                    }
                });

            })
        });
    </script>
@endsection
