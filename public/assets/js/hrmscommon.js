$(document).ready(function () {
    $("body").on("change", 'input[type="text"]', function () {
        $(this).val($(this).val().trim());
    });
    $("body").on("change", 'textarea[type="text"]', function () {
        $(this).val($(this).val().trim());
    });
});

function GetEditDeleteButton(
    hasPermission,
    url,
    type,
    id,
    title,
    isLargePopup = false
) {
    if (hasPermission && url !== "" && type === "Edit")
        return (
            "<button class='btn btn-sm btn-text-primary rounded btn-icon item-edit' type='button' style='background-color: #f6ddff !important; color: #891ab4 !important;' onclick='fnAddEdit(this,`" +
            url +
            "`," +
            id +
            ",`" +
            title +
            "`," +
            isLargePopup +
            ")' data-tooltiptoggle='tooltip' data-placement='top' title='Edit'><i class='mdi mdi-pencil-outline'></i></button>"
        );
    if (hasPermission && url !== "" && type === "Delete")
        return (
            '<button class="btn btn-sm btn-text-danger rounded btn-icon item-edit" type="button" style="background-color: #ffd7d7 !important;color: #ff0000 !important;" data-tooltiptoggle="tooltip" data-placement="top" onclick="' +
            url +
            '" title="Delete"><i class="mdi mdi-trash-can-outline"></i></button>'
        );

    return "";
}

function GetEditLinkButton(title, hasPermission, url, isOpenPopup) {
    if (hasPermission === "True" && url !== "" && isOpenPopup)
        return (
            "<a href='" +
            url +
            "' data-ajax='true' data-ajax-begin='OnBeginGet' data-ajax-complete='OnCompleteGet' data-ajax-success='OnSuccessGet' data-ajax-method='GET' data-ajax-mode='replace' data-ajax-update='#modalAddContainer' data-toggle='modal' data-target='#modalAddCommonPopup' data-tooltiptoggle='tooltip' data-placement='top' title='" +
            title +
            "'>" +
            title +
            "</a>"
        );
    else if (hasPermission === "True" && url !== "" && !isOpenPopup)
        return (
            '<a href="' +
            url +
            '" data-tooltiptoggle="tooltip" data-placement="top" title="' +
            title +
            '">' +
            title +
            "</a>"
        );
    else if (hasPermission === "False")
        return (
            "<a class='disabled' disabled href='javascript:void(0);'>" +
            title +
            "</a>"
        );

    return "";
}

function fnAddEdit(
    button = false,
    url,
    id,
    title,
    isLargePopup = false,
    params = null
) {
    if (button) {
        button.disabled = true;
    }
    postData = { params_id: params };

    var fullUrl = url + "?id=" + id;

    fnCallAjaxHttpGetEvent(fullUrl, postData, true, false, function (response) {
        // Handle the response
        if (isLargePopup === true) {
            $("#commonOffcanvas").css("width", "70%");
        } else {
            $("#commonOffcanvas").css("width", "400px");
        }
        $("#offcanvas-body").html(response);
        $("#offcanvas-header").html(title);
        new bootstrap.Offcanvas(
            document.getElementById("commonOffcanvas")
        ).show();

        button.disabled = false;
    });
}
function fnAddEditTask(button = false, url, id, title, isLargePopup = false) {
    if (button) {
        button.disabled = true;
    }

    var fullUrl = url + "?id=" + id;

    fnCallAjaxHttpGetEvent(fullUrl, null, true, false, function (response) {
        // Handle the response
        if (isLargePopup) {
            $("#commonOffcanvas").css("width", "70%");
        }
        $("#offcanvas-body").html(response);
        $("#offcanvas-header").html($("#taskTitle").val());
        new bootstrap.Offcanvas(
            document.getElementById("commonOffcanvas")
        ).show();

        button.disabled = false;
    });
}

function ShowMsg(type, msg) {
    const toastrMethod =
        {
            "bg-success": toastr.success,
            "bg-info": toastr.info,
            "bg-warning": toastr.warning,
            "bg-danger": toastr.error,
        }[type] || toastr.info;

    const timeOutValue =
        type === "bg-warning" || type === "bg-danger" ? 5000 : 2000;

    toastrMethod(msg, "", {
        timeOut: timeOutValue,
        closeButton: true,
        progressBar: true,
    });
}

var ACTIVE_REQ_CNT = 0;
var SHOW_SESSION_TIMEOUT_MSG = true;
function fnAddGlobalAjaxSuccessHandler() {
    $.ajaxSetup({
        // Disable caching of AJAX responses
        cache: false,
        beforeSend: function (xhr, settings) {
            if (
                "success" in settings &&
                typeof settings.success == "function"
            ) {
                settings.success = fnGlobalAjaxSuccessHandler(settings.success);
            }
        },
    });
}

function fnGlobalAjaxSuccessHandler(callback) {
    return function (response, textStatus, xhr) {
        if (
            fnScanResponseForSessionTimeOut(xhr) ||
            (response && response.IsLogOut)
        ) {
            fnSessionTimeOutHandler();
        } else {
            callback.apply(this, arguments);
        }
    };
}

function fnScanResponseForSessionTimeOut(xhr) {
    var contentType = xhr.getResponseHeader("Content-Type");
    var regex = new RegExp("formAuthentication");
    var isSessionTimeOut =
        contentType &&
        contentType.indexOf("text/html") !== -1 &&
        regex.exec(xhr.responseText) !== null;
    return isSessionTimeOut;
}

function fnSessionTimeOutHandler() {
    if (SHOW_SESSION_TIMEOUT_MSG) {
        SHOW_SESSION_TIMEOUT_MSG = false;
        fnHideWaitImage();
        $.confirm({
            title: "Session Expired",
            content: $("#sessionTimeOutTemplate")
                .html()
                .replace(/id="sessionTimeOutTemplate"/g, ""),
            type: "red",
            typeAnimated: true,
            animation: "zoom",
            closeAnimation: "scale",
            buttons: {
                loginAgain: {
                    text: "Login",
                    btnClass: "bg-gradient-teal btn-block",
                    action: function () {
                        window.location.href = "/";
                    },
                },
            },
        });
    }
}

function fnCallAjaxHttpGetEvent(
    url,
    param,
    isAsync,
    showLoader,
    successCallback
) {
    var args = Array.prototype.slice.call(arguments).slice(5);
    return $.ajax({
        async: isAsync,
        type: "GET",
        headers: {
            Authorization: "Bearer " + getCookie("access_token"),
        },
        contentType: "application/json",
        url: url,
        cache: false,
        data: param,
        beforeSend: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT++;
                fnShowWaitImage();
            }
        },
        success: function (data, textStatus, jqXHR) {
            if (
                fnScanResponseForSessionTimeOut(jqXHR) ||
                (data && data.IsLogOut)
            ) {
                fnSessionTimeOutHandler();
            } else {
                var callbackArgs = [];
                callbackArgs.push(data);
                callbackArgs = callbackArgs.concat(args);
                try {
                    successCallback.apply(this, callbackArgs);
                } catch (ex) {
                    console.error(ex);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 401) {
                window.location.href = "/401";
            } else {
                console.error("An error occurred:", textStatus + errorThrown);
            }
        },
        complete: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT--;
                ACTIVE_REQ_CNT === 0 && fnHideWaitImage();
            }
        },
    });
}

function fnCallAjaxHttpPostEvent(
    url,
    postData,
    isAsync,
    showLoader,
    successCallback
) {
    var args = Array.prototype.slice.call(arguments).slice(5);
    return $.ajax({
        async: isAsync,
        type: "POST",
        contentType: "application/json; charset=utf-8",
        url: url,
        headers: {
            Authorization: "Bearer " + getCookie("access_token"),
        },
        cache: false,
        data: JSON.stringify(postData),
        beforeSend: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT++;
                fnShowWaitImage();
            }
        },
        success: function (data, textStatus, jqXHR) {
            if (fnScanResponseForSessionTimeOut(jqXHR)) {
                fnSessionTimeOutHandler();
            } else {
                var callbackArgs = [];
                callbackArgs.push(data);
                callbackArgs = callbackArgs.concat(args);
                try {
                    successCallback.apply(this, callbackArgs);
                } catch (ex) {
                    console.error(ex);
                }
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            $(".form-control").removeClass("is-invalid");
            $(".invalid-feedback").text(""); // Clear previous errors

            var response = xhr.responseJSON;

            // If response.errors is "Validation errors", process the validation messages
            if (response.errors === "Validation errors") {
                $.each(response.message, function (key, value) {
                    var inputField = $("#" + key);
                    var errorField = $("#" + key + "-error");

                    inputField.addClass("is-invalid");
                    errorField.text(value).removeClass("d-none");
                });
            } else if (response.errors === null && response.message) {
                ShowMsg("bg-danger", response.message);
            } else {
                ShowMsg(
                    "bg-danger",
                    "An unexpected error occurred. Please try again."
                );
            }
        },
        failure: function (response) {
            console.error(response);
        },
        complete: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT--;
                ACTIVE_REQ_CNT === 0 && fnHideWaitImage();
            }
        },
    });
}

function fnCallAjaxHttpPostEventWithoutJSON(
    url,
    postData,
    isAsync,
    showLoader,
    successCallback
) {
    var args = Array.prototype.slice.call(arguments).slice(5);
    return $.ajax({
        async: isAsync,
        type: "POST",
        url: url,
        headers: {
            Authorization: "Bearer " + getCookie("access_token"),
        },
        cache: false,
        contentType: false,
        processData: false,
        data: postData,
        beforeSend: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT++;
                fnShowWaitImage();
            }
        },
        success: function (data, textStatus, jqXHR) {
            if (fnScanResponseForSessionTimeOut(jqXHR)) {
                fnSessionTimeOutHandler();
            } else {
                var callbackArgs = [];
                callbackArgs.push(data);
                callbackArgs = callbackArgs.concat(args);
                try {
                    successCallback.apply(this, callbackArgs);
                } catch (ex) {
                    console.error(ex);
                }
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            $(".form-control").removeClass("is-invalid");
            $(".invalid-feedback").text(""); // Clear previous errors

            var response = xhr.responseJSON;

            if (response.errors) {
                var errorMessages = [];
                $.each(response.message, function (key, value) {
                    var inputField = $("#" + key);
                    var errorField = $("#" + key + "-error");

                    inputField.addClass("is-invalid");
                    errorField.text(value).removeClass("d-none");
                });

                // Display error messages using the ShowMsg function
                if (errorMessages.length) {
                    ShowMsg("bg-danger", errorMessages.join("<br>"));
                }
            } else if (response.message) {
                ShowMsg("bg-danger", response.message);
            }
        },
        failure: function (response) {
            console.error(response);
        },
        complete: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT--;
                ACTIVE_REQ_CNT === 0 && fnHideWaitImage();
            }
        },
    });
}

function fnCallAjaxHttpDeleteEvent(
    url,
    postData,
    isAsync,
    showLoader,
    successCallback
) {
    var args = Array.prototype.slice.call(arguments).slice(5);
    return $.ajax({
        async: isAsync,
        type: "DELETE",
        contentType: "application/json; charset=utf-8",
        url: url,
        headers: {
            Authorization: "Bearer " + getCookie("access_token"),
        },
        cache: false,
        data: JSON.stringify(postData),
        beforeSend: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT++;
                fnShowWaitImage();
            }
        },
        success: function (data, textStatus, jqXHR) {
            if (fnScanResponseForSessionTimeOut(jqXHR)) {
                fnSessionTimeOutHandler();
            } else {
                var callbackArgs = [];
                callbackArgs.push(data);
                callbackArgs = callbackArgs.concat(args);
                try {
                    successCallback.apply(this, callbackArgs);
                } catch (ex) {
                    console.error(ex);
                }
            }
        },
        failure: function (response) {
            console.error(response);
        },
        complete: function () {
            if (showLoader) {
                ACTIVE_REQ_CNT--;
                ACTIVE_REQ_CNT === 0 && fnHideWaitImage();
            }
        },
    });
}

function fnShowWaitImage() {
    document.getElementById("preloader").style.display = "block";
}

function fnHideWaitImage() {
    document.getElementById("preloader").style.display = "none";
}

$(document).ajaxError(function myErrorHandler(
    event,
    xhr,
    ajaxOptions,
    thrownError
) {
    if (xhr.status == 401) {
        window.location.href = "/";
    } else if (xhr.status === 403) {
        // alert("Your session has expired. Please log in again.");
        fnSessionTimeOutHandler();
    }
});

function fnShowConfirmDeleteDialog(name, fnCallback) {
    if (name == "" || name == null || name == undefined) name = "Record";
    var args = Array.prototype.slice.call(arguments).slice(2);
    bootbox.confirm({
        backdrop: true,
        title: "Confirmation",
        message:
            "You can't restore after delete. Are you sure want to permanently delete this <b>" +
            name +
            "</b>?",
        buttons: {
            cancel: {
                className:
                    "btn rounded-pill btn-outline-gray waves-effect waves-light",
                label: "Cancel",
            },
            confirm: {
                className:
                    "btn rounded-pill waves-effect waves-light bg-danger text-white",
                label: "Delete",
            },
        },
        callback: function (result) {
            var arguments = [];
            arguments.push(result);
            arguments = arguments.concat(args);
            fnCallback.apply(this, arguments);
        },
    });
}

function fnDeleteRecord(result, Id, url, grdId) {
    if (result === true) {
        fnCallAjaxHttpPostEvent(
            url + "/" + Id,
            {},
            true,
            false,
            function (data) {
                if (data !== undefined && data.AccessDenied) {
                    location.href = "/";
                    return;
                }

                if (data.status === 200 || data.success) {
                    if (data.returnValue === 10) {
                        refreshNavbar();
                    }
                    if (data.returnValue === 11) {
                        refreshNavbar();
                        const icon = $(
                            "#notification-icon, #notification-trash-icon"
                        );
                        if (icon.length) {
                            icon.hide();
                        }
                    }

                    if (data.returnValue === 1) {
                        // Reload the entire page if returnValue is 1
                        location.reload();
                    } else if (data.data.project_id !== null) {
                        $("#grid").DataTable().ajax.reload();
                        ShowMsg("bg-success", data.message);
                        KanbanData(data.data.project_id);
                        return;
                    } else {
                        // Reload the DataTable if returnValue is null or not 1
                        $("#grid").DataTable().ajax.reload();
                    }

                    ShowMsg("bg-success", data.message);
                } else {
                    ShowMsg("bg-danger", data.message);
                }
            }
        );
    }
}

function getIdFromToken() {
    const token = getCookie("access_token");
    if (!token) {
        console.error("No JWT token found.");
        return null;
    }
    try {
        const decodedToken = jwt_decode(token);

        return decodedToken?.uuid ?? null;
    } catch (error) {
        console.error("Failed to decode JWT token:", error);
        return null;
    }
}

(function ($) {
    $.fn.inputFilter = function (callback, errMsg) {
        return this.on(
            "input keydown keyup mousedown mouseup select contextmenu drop focusout",
            function (e) {
                if (callback(this.value)) {
                    // Accepted value
                    if (
                        ["keydown", "mousedown", "focusout"].indexOf(e.type) >=
                        0
                    ) {
                        $(this).removeClass("input-error");
                        this.setCustomValidity("");
                    }
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    // Rejected value - restore the previous one
                    $(this).addClass("input-error");
                    this.setCustomValidity(errMsg);
                    this.reportValidity();
                    this.value = this.oldValue;
                    this.setSelectionRange(
                        this.oldSelectionStart,
                        this.oldSelectionEnd
                    );
                } else {
                    // Rejected value - nothing to restore
                    this.value = "";
                }
            }
        );
    };
})(jQuery);

function populateYearDropdown(selectId, startOffset, endOffset) {
    // Get the current year
    var currentYear = new Date().getFullYear();
    var startYear = currentYear + startOffset; // startOffset can be negative for years before current year
    var endYear = currentYear + endOffset; // endOffset can be positive for years after current year

    $("#" + selectId).empty();

    for (var year = startYear; year <= endYear; year++) {
        $("#" + selectId).append(
            '<option value="' + year + '">' + year + "</option>"
        );
    }
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(name) {
    const cookies = document.cookie.split(";");
    for (let cookie of cookies) {
        cookie = cookie.trim();
        if (cookie.startsWith(name + "=")) {
            return cookie.substring(name.length + 1);
        }
    }
    return null;
}

function loadEmployeeDropdown(
    dropdownId,
    apiUrl,
    includeAll = false,
    excludeAdmin = false
) {
    // Use the provided API URL
    fnCallAjaxHttpGetEvent(apiUrl, null, true, true, function (response) {
        if (response.status === 200 && response.data) {
            var $teamMembersDropdown = $("#" + dropdownId);
            $teamMembersDropdown.empty();

            // Add default option
            if (includeAll) {
                $teamMembersDropdown.append(new Option("All", "0"));
            } else {
                $teamMembersDropdown.append(
                    new Option("Select Employee Name", "")
                );
            }

            // Filter non-clients (and optionally non-admins)
            var filteredUsers = response.data.filter(function (data) {
                if (excludeAdmin) {
                    return (
                        data.role_code !== "CLIENT" &&
                        data.role_code !== "ADMIN"
                    );
                }
                return data.role_code !== "CLIENT";
            });

            // Add options to dropdown
            filteredUsers.forEach(function (data) {
                $teamMembersDropdown.append(new Option(data.name, data.id));
            });
        } else {
            console.error("Failed to retrieve user list.");
        }
    });
}
