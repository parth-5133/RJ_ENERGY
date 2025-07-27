@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{ $menuName }}</b></h5>
                </div>
                @if ($permissions['canAdd'])
                    <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                        onClick="fnAddEdit(this,'{{ url('menu/create') }}', 0, 'Add Menu')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Menu
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="table-responsive text-nowrap">
                <table id="grid" class="table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Navigation URL</th>
                            <th>Order</th>
                            <th>Sequence</th>
                            <th>Status</th>
                            <th>Modified By</th>
                            <th>Modified Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            @if (is_null($menu['parent_menu_id']) || $menu['parent_menu_id'] === 0)
                                <tr class="parent-menu" data-id="{{ $menu['id'] }}">
                                    <td>
                                        @if (collect($menus)->where('parent_menu_id', $menu['id'])->isNotEmpty())
                                            <!-- Plus/Minus Toggle Button -->
                                            <button class="btn btn-sm btn-text-success rounded btn-icon toggle-collapse"
                                                style="background-color: #cfffd4 !important; color:#00890e !important;" type="button"
                                                title="Toggle Children" onClick="showChildMenu('{{ $menu['id'] }}')">
                                                <i class="mdi mdi-plus"></i>
                                            </button>
                                        @endif
                                        @if ($permissions['canEdit'])
                                            <button class="btn btn-sm btn-text-primary rounded btn-icon item-edit"
                                                type="button" data-tooltiptoggle="tooltip" data-placement="top"
                                                style="background-color: #f6ddff !important; color: #891ab4 !important;"
                                                onClick="fnAddEdit(this, '{{ url('menu/create') }}', {{ $menu['id'] }}, 'Edit Menu')"
                                                title="Edit">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </button>
                                        @endif
                                        @if ($permissions['canDelete'])
                                            <button class="btn btn-sm btn-text-danger rounded-3 btn-icon item-edit"
                                                type="button" style="background-color: #ffd7d7 !important;color: #ff0000 !important;"
                                                onClick="fnShowConfirmDeleteDialog('{{ $menu['name'] }}', fnDeleteRecord, {{ $menu['id'] }}, '{{ config('apiConstants.MENU_API_URLS.MENU_DELETE') }}')"
                                                title="Delete">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        @endif
                                    </td>
                                    @if ($permissions['canDelete'])
                                        <td>
                                            @if ($permissions['canEdit'])
                                                <a href="javascript:void(0);"
                                                    onclick="fnAddEdit(this, '{{ url('menu/create') }}', {{ $menu['id'] }}, 'Edit Menu')"
                                                    class="user-name" data-id="{{ $menu['id'] }}">
                                                    {{ $menu['name'] }}
                                                </a>
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $menu['access_code'] }}</td>
                                    <td>{{ $menu['navigation_url'] }}</td>
                                    <td>{{ $menu['display_order'] }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button
                                                class="btn btn-sm btn-text-secondary rounded-3 btn-icon bg-primary text-white me-1"
                                                onclick="changeOrder({{ $menu['id'] }}, 'up')">
                                                <i class="mdi mdi-arrow-up"></i>
                                            </button>
                                            <button
                                                class="btn btn-sm btn-text-secondary rounded-3 btn-icon bg-primary text-white"
                                                onclick="changeOrder({{ $menu['id'] }}, 'down')">
                                                <i class="mdi mdi-arrow-down"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><span
                                            class="badge rounded-3 {{ $menu['is_active'] === 1 ? 'bg-label-success' : 'bg-label-danger' }}">
                                            {{ $menu['is_active'] === 1 ? 'Active' : 'Inactive' }}
                                        </span></td>
                                    <td>{{ $menu['updated_name'] }}</td>
                                    <td>{{ $menu['updated_at_formatted'] }}</td>

                                </tr>
                                <!-- Child menus -->
                                @foreach ($menus as $childMenu)
                                    @if ($childMenu['parent_menu_id'] === $menu['id'])
                                        <tr class="child-menu" data-parent-id="{{ $menu['id'] }}" style="display: none;">
                                            <td>
                                                @if ($permissions['canEdit'])
                                                    <button
                                                        class="btn btn-sm btn-text-primary rounded btn-icon item-edit"
                                                        type="button" data-tooltiptoggle="tooltip" data-placement="top"
                                                        style="background-color: #f6ddff !important; color: #891ab4 !important;"
                                                        onClick="fnAddEdit(this, '{{ url('menu/create') }}', {{ $childMenu['id'] }}, 'Edit Menu')"
                                                        title="Edit">
                                                        <i class="mdi mdi-pencil-outline"></i>
                                                    </button>
                                                @endif
                                                @if ($permissions['canDelete'])
                                                    <button
                                                        class="btn btn-sm btn-text-danger rounded-3 btn-icon item-edit"
                                                        type="button" style="background-color: #ffd7d7 !important;color: #ff0000 !important;"
                                                        onClick="fnShowConfirmDeleteDialog('{{ $childMenu['name'] }}', fnDeleteRecord, {{ $childMenu['id'] }}, '{{ config('apiConstants.MENU_API_URLS.MENU_DELETE') }}')"
                                                        title="Delete">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            @if ($permissions['canDelete'])
                                                <td>
                                                    @if ($permissions['canEdit'])
                                                        <a href="javascript:void(0);"
                                                            onclick="fnAddEdit(this, '{{ url('menu/create') }}', {{ $childMenu['id'] }}, 'Edit Menu')"
                                                            class="user-name" data-id="{{ $childMenu['id'] }}">
                                                            {{ $childMenu['name'] }}
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $childMenu['access_code'] }}</td>
                                            <td>{{ $childMenu['navigation_url'] }}</td>
                                            <td>{{ $childMenu['display_order'] }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button
                                                        class="btn btn-sm btn-text-secondary rounded-3 btn-icon bg-primary text-white me-1"
                                                        onclick="changeOrder({{ $childMenu['id'] }}, 'up')">
                                                        <i class="mdi mdi-arrow-up"></i>
                                                    </button>
                                                    <button
                                                        class="btn btn-sm btn-text-secondary rounded-3 btn-icon bg-primary text-white"
                                                        onclick="changeOrder({{ $childMenu['id'] }}, 'down')">
                                                        <i class="mdi mdi-arrow-down"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td><span
                                                    class="badge rounded-3 {{ $childMenu['is_active'] === 1 ? 'bg-label-success' : 'bg-label-danger' }}">
                                                    {{ $childMenu['is_active'] === 1 ? 'Active' : 'Inactive' }}
                                                </span></td>
                                            <td>{{ $childMenu['updated_name'] }}</td>
                                            <td>{{ $childMenu['updated_at_formatted'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function showChildMenu(id) {
            const $childRows = $(`.child-menu[data-parent-id='${id}']`);

            $childRows.each(function() {
                const $row = $(this);
                $row.toggle();
            });

            const $toggleButton = $(`.parent-menu[data-id='${id}'] .toggle-collapse i`);
            if ($toggleButton.length) {
                if ($toggleButton.hasClass('mdi-plus')) {
                    $toggleButton.removeClass('mdi-plus').addClass('mdi-minus');
                } else {
                    $toggleButton.removeClass('mdi-minus').addClass('mdi-plus');
                }
            }
        }

        function changeOrder(id, direction) {
            var postData = {
                id: id,
                direction: direction,
            };
            var url = "{{ config('apiConstants.MENU_API_URLS.MENU_REORDER') }}";

            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                if (response.status === 200) {
                    location.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    </script>
@endsection
