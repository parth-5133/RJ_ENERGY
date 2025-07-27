<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="menuId" value="{{ $menuId ?? '' }}">

    <!-- Role Name -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="name" id="name" maxlength="255"
            placeholder="Menu Name" />
        <label for="name">Menu Name <span style="color:red">*</span></label>
        <span class="text-danger" id="name-error"></span>
    </div>

    <!-- Access Code -->
    @if (empty($menuId))
        <div class="form-floating form-floating-outline mb-4">
            <input type="text" class="form-control" name="access_code" id="access_code" maxlength="50"
                placeholder="Access Code" />
            <label for="access_code">Access Code <span style="color:red">*</span></label>
            <span class="text-danger" id="access_code-error"></span>
        </div>


        <!-- Navigation URL -->
        <div class="form-floating form-floating-outline mb-4">
            <input type="text" class="form-control" name="navigation_url" id="navigation_url" maxlength="255"
                placeholder="Navigation URL" />
            <label for="navigation_url">Navigation URL</label>
            <span class="text-danger" id="navigation_url-error"></span>
        </div>
    @endif

    <!-- Display in Menu -->
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-select" name="display_in_menu" id="display_in_menu">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <label for="display_in_menu">Display in Menu <span style="color:red">*</span></label>
        <span class="text-danger" id="display_in_menu-error"></span>
    </div>

    <!-- Parent Menu ID -->
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-select" name="parent_menu_id" id="parent_menu_id">
        </select>
        <label for="parent_menu_id">Parent Menu ID</label>
        <span class="text-danger" id="parent_menu_id-error"></span>
    </div>

    <!-- Menu Icon -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="menu_icon" id="menu_icon" maxlength="255"
            placeholder="Menu Icon" />
        <label for="menu_icon">Menu Icon</label>
        <span class="text-danger" id="menu_icon-error"></span>
    </div>

    <!-- Menu Class -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="menu_class" id="menu_class" maxlength="255"
            placeholder="Menu Class" />
        <label for="menu_class">Menu Class</label>
        <span class="text-danger" id="menu_class-error"></span>
    </div>

    <!-- Is Active -->
    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" />
        <label class="form-check-label" for="is_active"> Active </label>
        <span class="text-danger" id="is_active-error"></span>
    </div>

    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas"><span
                class="tf-icons mdi mdi-cancel me-1"></span> Cancel</button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
        </button>
    </div>
</form>

<script type="text/javascript">
    var menuId = $("#menuId").val(); // Get the menu ID from the hidden input
    $(document).ready(function() {

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.MENU_API_URLS.MENU') }}", null, true, true, function(
            response) {
            if (response.status === 200 && response.data) {
                var $parentMenuDropdown = $("#parent_menu_id");
                $parentMenuDropdown.empty(); // Clear existing options
                $parentMenuDropdown.append(new Option('Select Parent Menu',
                0)); // Default option with value 0

                // Group menus by parent_menu_id
                let parentMenus = response.data.filter(menu => menu.parent_menu_id == 0);
                let childMenus = response.data.filter(menu => menu.parent_menu_id != 0);

                // Populate parent menus (in bold) and their children
                parentMenus.forEach(function(parent) {
                    var parentOption = new Option(parent.name, parent.id);
                    parentOption.style.fontWeight = 'bold'; // Display parent in bold
                    $parentMenuDropdown.append(parentOption);

                    // Find children of the current parent and append them with a formatted style
                    childMenus.forEach(function(child) {
                        if (child.parent_menu_id == parent.id) {
                            // Customize the prefix and formatting for child menus
                            var childOption = new Option('  ' + child.name, child.id);
                            childOption.style.paddingLeft =
                            '20px'; // Add padding for visual hierarchy
                            childOption.style.fontWeight =
                            'normal'; // Ensure child menus are not bold
                            $parentMenuDropdown.append(childOption);
                        }
                    });
                });

                // Existing code for form population
                if (menuId > 0) {
                    fnCallAjaxHttpGetEvent("{{ config('apiConstants.MENU_API_URLS.MENU_VIEW') }}", {
                        menuId: menuId
                    }, true, true, function(response) {
                        if (response.status === 200 && response.data) {
                            // Populate form fields with data from the response
                            $("#name").val(response.data.name);
                            $("#access_code").val(response.data.access_code);
                            $("#navigation_url").val(response.data.navigation_url);
                            $("#display_in_menu").val(response.data.display_in_menu);
                            $("#parent_menu_id").val(response.data.parent_menu_id);
                            $("#menu_icon").val(response.data.menu_icon);
                            $("#menu_class").val(response.data.menu_class);
                            $("#is_active").prop('checked', response.data.is_active);

                            // Check if parent_menu_id is 0, if so, select 'Select Parent Menu'
                            if (response.data.parent_menu_id === 0) {
                                $("#parent_menu_id").val('0');
                            }
                        } else {
                            console.error('Failed to retrieve menu data.');
                        }
                    });
                }
            } else {
                console.error('Failed to retrieve menu list.');
            }
        });
    });

    $(document).ready(function() {
        // Initialize jQuery Validation
        $("#commonform").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
                access_code: {
                    required: true,
                    maxlength: 20
                },
                display_in_menu: {
                    required: true
                },
                parent_menu_id: {
                    required: false, // Optional if allowed to be empty
                    digits: true // Ensure it's a number if provided
                },
                menu_icon: {
                    maxlength: 100 // Assuming icon names should have a length limit
                },
                menu_class: {
                    maxlength: 50 // Assuming class names should have a length limit
                }
            },
            messages: {
                name: {
                    required: "Menu name is required",
                    maxlength: "Menu name cannot exceed 50 characters"
                },
                access_code: {
                    required: "Access code is required",
                    maxlength: "Access code cannot exceed 20 characters"
                },
                display_in_menu: {
                    required: "Please select whether to display in the menu"
                },
                parent_menu_id: {
                    digits: "Please enter a valid menu ID"
                },
                menu_icon: {
                    maxlength: "Menu icon name cannot exceed 100 characters"
                },
                menu_class: {
                    maxlength: "Menu class name cannot exceed 50 characters"
                }
            },
            errorPlacement: function(error, element) {
                var errorId = element.attr("name") +
                    "-error";
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
                event.preventDefault();
                var postData = {
                    menuId: $("#menuId").val(),
                    name: $("#name").val(),
                    access_code: $("#access_code").val(),
                    navigation_url: $("#navigation_url").val(),
                    display_in_menu: $("#display_in_menu").val(),
                    parent_menu_id: $("#parent_menu_id").val() || 0,
                    menu_icon: $("#menu_icon").val(),
                    menu_class: $("#menu_class").val(),
                    is_active: $("#is_active").is(':checked')
                };

                var url = menuId > 0 ? "{{ config('apiConstants.MENU_API_URLS.MENU_UPDATE') }}" :
                    "{{ config('apiConstants.MENU_API_URLS.MENU_STORE') }}";

                // AJAX call
                fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        bootstrap.Offcanvas.getInstance(document.getElementById(
                                'commonOffcanvas'))
                            .hide();
                        location.reload();
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning", 'The record could not be processed.');
                    }
                });
            }
        });
    });
</script>
