@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"></h5>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Menu Name</th>
                            <th>All</th>
                            <th>View</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="menuTableBody">
                        <!-- Dynamic content will be appended here -->
                    </tbody>
                </table>
            </div>
            <!-- Buttons Section -->
            <div class="d-flex justify-content-center p-3">
                <button class="btn rounded-pill btn-outline-secondary me-2" type="button" data-bs-dismiss="offcanvas"
                    onclick="window.location.href='{{ url('role') }}';">
                    <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
                </button>

                <button type="submit" class="btn rounded-pill btn-success" id="submitButton">
                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span>Submit
                </button>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let menuData = null;
        let rolePermissionsData = null;
        let roleId = null;

        $(document).ready(function() {
            // Extract role_id from the URL and assign it to the global variable
            roleId = window.location.pathname.split('/').pop();

            // Fetch role permissions data
            fnCallAjaxHttpGetEvent("{{ config('apiConstants.API_URLS.PERMISSIONS_VIEW') }}/" +
                roleId, null,
                true,
                true,
                function(response) {
                    rolePermissionsData = response.data;
                    checkAndRenderMenuTable();
                    Renderrolename(rolePermissionsData);
                });

            // Fetch menu data
            fnCallAjaxHttpGetEvent("{{ config('apiConstants.MENU_API_URLS.MENU') }}", null, true,
                true,
                function(
                    response) {
                    menuData = response.data;
                    checkAndRenderMenuTable();
                });
        });

        // Handle form submission to save role permissions
        $("#submitButton").on("click", function() {
            let postData = [];
            // Loop through menus and gather permission data
            $('#menuTableBody tr').each(function() {
                let menuId = $(this).find('input[name^="all_"]').attr('name')
                    ?.split('_')[1];
                if (menuId) {
                    let canAdd = $(this).find(`input[name="add_${menuId}"]`).is(':checked') ? 1 : 0;
                    let canView = $(this).find(`input[name="view_${menuId}"]`).is(':checked') ? 1 : 0;
                    let canEdit = $(this).find(`input[name="edit_${menuId}"]`).is(':checked') ? 1 : 0;
                    let canDelete = $(this).find(`input[name="delete_${menuId}"]`).is(':checked') ? 1 : 0;

                    postData.push({
                        role_id: roleId,
                        menu_id: menuId,
                        canAdd: canAdd,
                        canView: canView,
                        canEdit: canEdit,
                        canDelete: canDelete
                    });
                }
            });

            // Send AJAX POST request to store data in the role_permission_mapping table
            fnCallAjaxHttpPostEvent("{{ config('apiConstants.API_URLS.ROLES_PERMISSIONS') }}", postData,
                true,
                true,
                function(response) {
                    if (response.status === 200) {
                        ShowMsg("bg-success", response.message);
                        window.location.href = '/role';
                    } else {
                        ShowMsg("bg-warning", 'The record could not be processed.');
                    }
                });
        });

        // Ensure that both menu and role permissions data are available before rendering the table
        function checkAndRenderMenuTable() {
            if (menuData && rolePermissionsData) {
                renderMenuTable(menuData, rolePermissionsData);
            }
        }

        function renderMenuTable(menus, rolePermissions) {
            let menuTableBody = $('#menuTableBody');
            menuTableBody.empty(); // Clear existing content

            // Filter parent and child menus
            let parentMenus = menus.filter(menu => menu.parent_menu_id === 0);
            let childMenus = menus.filter(menu => menu.parent_menu_id !== 0);

            // Create a map of role permissions for easier lookup
            const rolePermissionMap = {};
            rolePermissions.forEach(rp => {
                rolePermissionMap[`${rp.menu_id}`] = {
                    canAdd: rp.canAdd,
                    canView: rp.canView,
                    canEdit: rp.canEdit,
                    canDelete: rp.canDelete
                };
            });

            // Loop through parent menus and render them along with their child menus (or directly with permissions if no children)
            parentMenus.forEach(parent => {
                // Filter out child menus for the current parent
                let parentChildren = childMenus.filter(child => child.parent_menu_id === parent.id);

                if (parentChildren.length === 0) {
                    // Parent has no children, so render checkboxes for the parent menu itself
                    const rolePerm = rolePermissionMap[parent.id] || {};

                    menuTableBody.append(`
                <tr class="table-primary">
                    <td><strong>${parent.name}</strong></td>
                    <td><input type="checkbox" class="form-check-input" name="all_${parent.id}" onchange="toggleAll(this, ${parent.id})" ${rolePerm.canAdd && rolePerm.canView && rolePerm.canEdit && rolePerm.canDelete ? 'checked' : ''}></td>
                    <td><input type="checkbox" class="form-check-input" name="view_${parent.id}" ${rolePerm.canView ? 'checked' : ''}></td>
                    <td><input type="checkbox" class="form-check-input" name="add_${parent.id}" ${rolePerm.canAdd ? 'checked' : ''}></td>
                    <td><input type="checkbox" class="form-check-input" name="edit_${parent.id}" ${rolePerm.canEdit ? 'checked' : ''}></td>
                    <td><input type="checkbox" class="form-check-input" name="delete_${parent.id}" ${rolePerm.canDelete ? 'checked' : ''}></td>
                </tr>
            `);
                } else {
                    // Parent has children, render the parent row without checkboxes
                    menuTableBody.append(`
                <tr class="table-primary">
                    <td><strong>${parent.name}</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            `);

                    // Add Child Menus under Parent Menu with checkboxes
                    parentChildren.forEach(child => {
                        const rolePerm = rolePermissionMap[child.id] || {};

                        menuTableBody.append(`
                    <tr>
                        <td class="ps-4">${child.name}</td>
                        <td>
                            <input type="checkbox" class="form-check-input" name="all_${child.id}" onchange="toggleAll(this, ${child.id})" ${rolePerm.canAdd && rolePerm.canView && rolePerm.canEdit && rolePerm.canDelete ? 'checked' : ''}>
                        </td>
                        <td><input type="checkbox" class="form-check-input" name="view_${child.id}" ${rolePerm.canView ? 'checked' : ''}></td>
                        <td><input type="checkbox" class="form-check-input" name="add_${child.id}" ${rolePerm.canAdd ? 'checked' : ''}></td>
                        <td><input type="checkbox" class="form-check-input" name="edit_${child.id}" ${rolePerm.canEdit ? 'checked' : ''}></td>
                        <td><input type="checkbox" class="form-check-input" name="delete_${child.id}" ${rolePerm.canDelete ? 'checked' : ''}></td>
                    </tr>
                `);
                    });
                }
            });
        }
        // Toggle all permissions (view, add, edit, delete) when "All" checkbox is checked
        function toggleAll(checkbox, menuId) {
            const checked = checkbox.checked;
            $(`input[name="view_${menuId}"], input[name="add_${menuId}"], input[name="edit_${menuId}"], input[name="delete_${menuId}"]`)
                .prop('checked', checked);
        }

        function Renderrolename(rolePermissionsData) {
            if (rolePermissionsData.length > 0) {
                const roleName = rolePermissionsData[0].role_name;
                // Render the role name dynamically in the HTML
                document.querySelector('.card-title').innerHTML =
                    `<b>Menu Role Mapping: ${roleName}</b>`;
            }
        }
    </script>
@endsection
