@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y ">
        <!-- Back Button -->
        <a href="{{ route('projects') }}" class="btn btn-primary waves-effect waves-light text-white mb-2">
            <i class="mdi mdi-arrow-left"></i> Back
        </a>
        <div class="row">
            <div class="col-xxl-3 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Project Details</h5>
                        <div class="list-group mb-4">
                            <div class="list-group-item">
                                <span>Client</span>
                                <p class="mb-0 fw-medium">{{ $Data['projectData']['client_name'] }}</p>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Project Total Cost</span>
                                    <p class="mb-0 fw-medium">N/A</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Hours of Work</span>
                                    <p class="mb-0 fw-medium">{{ $Data['projectData']['total_hours_of_work'] }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Created on</span>
                                    <p class="mb-0 fw-medium">{{ $Data['projectData']['created_at_formatted'] }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Started on</span>
                                    <p class="mb-0 fw-medium">{{ $Data['projectData']['start_date_formatted'] }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Due Date</span>
                                    <p class="mb-0 fw-medium">{{ $Data['projectData']['end_date_formatted'] }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Created by</span>
                                    <p class="mb-0 fw-medium">{{ $Data['projectData']['created_name'] }}</p>
                                </div>
                            </div>
                        </div>
                        <h5 class="mb-3">Tasks Details</h5>
                        <div class="bg-label-success p-2 rounded">
                            <span class="mb-2 text-dark">Tasks Done</span>
                            <h4 class="mb-0">{{ $completedTasksCount }} / <span>{{ $totalTasks }}</span></h4>
                            <div class="progress mb-4" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped" role="progressbar"
                                    style="width: {{ $totalTasks > 0 ? ($completedTasksCount / $totalTasks) * 100 : 0 }}%"
                                    aria-valuenow="{{ $totalTasks > 0 ? ($completedTasksCount / $totalTasks) * 100 : 0 }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <p class="mb-0 text-dark">
                                {{ $totalTasks > 0 ? round(($completedTasksCount / $totalTasks) * 100) : 0 }}% Completed
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-9 col-xl-8">
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="bg-body rounded p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="flex-shrink-0 me-2">
                                    <img src="https://smarthr.dreamstechnologies.com/laravel/template/public/build/img/social/project-01.svg"
                                        alt="Img">
                                </a>
                                <div>
                                    <h5 class="mb-0">
                                        <a class="text-black" href="javascript:void(0)">
                                            {{ $Data['projectData']['project_name'] }}
                                        </a>
                                    </h5>
                                    <p class="text-dark mb-0">Project ID :
                                        <span class="text-primary">{{ $Data['projectData']['project_id'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-sm-3">
                                <p class="d-flex align-items-center mb-3">
                                    <i class="mdi mdi-list-status me-2 p-1 bg-label-primary rounded"></i>
                                    Priority
                                </p>
                            </div>
                            <div class="col-sm-9">
                                @php
                                    $priority = $Data['projectData']['priority'];
                                    $priorityLabel =
                                        $priority == 1
                                            ? 'High'
                                            : ($priority == 2
                                                ? 'Medium'
                                                : ($priority == 3
                                                    ? 'Low'
                                                    : 'Not Set'));
                                    $priorityClass =
                                        $priority == 1
                                            ? 'bg-label-danger'
                                            : ($priority == 2
                                                ? 'bg-label-info'
                                                : ($priority == 3
                                                    ? 'bg-label-primary'
                                                    : 'bg-label-warning'));
                                @endphp

                                <span class="badge rounded {{ $priorityClass }} mb-3">{{ $priorityLabel }}</span>
                            </div>
                            <div class="col-sm-3">
                                <p class="d-flex align-items-center mb-3">
                                    <i class="mdi mdi-account-group-outline me-2 p-1 bg-label-info rounded"></i>
                                    Team
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <div class="d-flex align-items-center mb-3">
                                    @foreach ($Data['projectTeam'] as $teamMember)
                                        <div class="bg-light px-4 rounded d-flex align-items-center me-2">
                                            <h6 class="mb-0"><a href="#"
                                                    class="text-black">{{ $teamMember['team_member_name'] }}</a></h6>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <p class="d-flex align-items-center mb-3"><i
                                        class="mdi mdi-shield-account-outline me-2 p-1 bg-label-success rounded"></i>
                                    Team Lead
                                </p>
                            </div>
                            <div class="col-sm-9">
                                <div class="d-flex align-items-center mb-3">
                                    @foreach ($Data['projectTeamLeader'] as $teamLeader)
                                        <div class="bg-light px-4 rounded d-flex align-items-center me-2">
                                            <h6 class="mb-0"><a href="#"
                                                    class="text-black">{{ $teamLeader['team_leader_name'] }}</a></h6>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3 mt-4">
                                    <h6 class="mb-1">Description</h6>
                                    <p>{!! Str::of($Data['projectData']['description'])->stripTags('<b><strong><u><s>') !!}</p>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
                                <div class="bg-label-success p-3 rounded d-flex align-items-center justify-content-between">
                                    <p class="fw-medium mb-0">Time Spent on this project
                                    </p>
                                    <h5 class="text-black fw-bold mb-0">60/120 <span>Hrs</span>
                                    </h5>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="card mb-6 rounded">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">Files</h5>
                        <div class="d-flex align-items-center gap-4">
                            <a onClick="fnAddEdit(this, '{{ url('/projects/documents/upload') }}', {{ $Data['projectData']['id'] }}, 'Upload Files')"
                                class="btn btn-primary waves-effect waves-light text-white">
                                <i class="mdi mdi-plus me-1"></i>Add New
                            </a>
                            <i class="mdi mdi-chevron-down me-1" id="toggle-open" style="display: none;"></i>
                            <i class="mdi mdi-chevron-up me-1" id="toggle-close"></i>
                        </div>
                    </div>
                    <div class="card-body mt-6" id="file-section">
                        <div class="row">
                            @foreach ($projectFilesData as $file)
                                <div class="col-sm-4">
                                    <div class="card shadow-none border rounded mb-4">
                                        <div class="card-body">
                                            <div
                                                class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0)">
                                                        <i
                                                            class="mdi mdi-file-document-outline me-2 p-2 bg-label-info rounded"></i>
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-bold mb-0">{{ $file['file_display_name'] }}</h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ Storage::url($file['relative_path']) }}" target="_blank"
                                                        class="btn btn-sm btn-text-secondary rounded-pill btn-icon"
                                                        download>
                                                        <i class="mdi mdi-tray-arrow-down"></i>
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                        onclick="fnShowConfirmDeleteDialog('{{ $file['file_display_name'] }}', fnDeleteRecord, {{ $file['id'] }}, '{{ config('apiConstants.PROJECTS_URLS.PROJECTS_DELETE_DOCUMENT') }}', '#grid')"
                                                        class="btn btn-sm btn-text-secondary rounded-pill btn-icon">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-medium mb-0">
                                                    {{ \Carbon\Carbon::parse($file['created_at'])->format('d M Y, h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card mb-6 rounded">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">Tasks</h5>
                        <div class="d-flex align-items-center gap-4">
                            <a onClick="fnAddEdit(this, '{{ url('/tasks/create') }}', 0, 'Add New Tasks',true,1)"
                                class="btn btn-primary waves-effect waves-light text-white">
                                <i class="mdi mdi-plus me-1"></i> New Task
                            </a>
                            <i class="mdi mdi-chevron-down me-1" id="toggle-open_second" style="display: none;"></i>
                            <i class="mdi mdi-chevron-up me-1" id="toggle-close_second"></i>
                        </div>
                    </div>
                    <div class="card-body mt-6" id="file-section_second">
                        <div class="list-group">
                            @foreach ($tasksData as $task)
                                <div class="list-group-item border rounded mb-2 p-2">
                                    <div class="row align-items-center row-gap-3">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center flex-wrap row-gap-3">
                                                <h6 class="mb-0">
                                                    {{ $task['title'] }}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div
                                                class="d-flex align-items-center justify-content-md-end flex-wrap row-gap-3">
                                                <span
                                                    class="badge rounded {{ $task['status_name'] == 'In Progress' ? 'bg-label-primary' : ($task['status_name'] == 'Completed' ? 'bg-label-success' : ($task['status_name'] == 'Pending' ? 'bg-label-info' : ($task['status_name'] == 'On Hold' ? 'bg-label-danger' : 'bg-label-secondary'))) }} me-2">
                                                    <i class="mdi mdi-circle-medium"></i>
                                                    {{ $task['status_name'] }}
                                                </span>
                                                <div class="d-flex align-items-center">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown">
                                                            <i class="mdi mdi-dots-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                                onclick="fnAddEdit(this,'{{ url('/tasks/create') }}',{{ $task['id'] }}, 'Edit Task',true,1)"><i
                                                                    class="mdi mdi-pencil-outline me-1"></i> Edit</a>
                                                            <a class="dropdown-item"
                                                                onclick="fnShowConfirmDeleteDialog('{{ $task['title'] }}',fnDeleteRecord,{{ $task['id'] }},'{{ config('apiConstants.PROJECTS_URLS.PROJECTS_DELETE_TASK') }}','#grid')">
                                                                <i class="mdi mdi-trash-can-outline me-1"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function toggleSection(toggleOpenId, toggleCloseId, sectionId) {
                $(toggleOpenId).click(function() {
                    $(sectionId).slideDown();
                    $(toggleOpenId).hide();
                    $(toggleCloseId).show();
                });

                $(toggleCloseId).click(function() {
                    $(sectionId).slideUp();
                    $(toggleOpenId).show();
                    $(toggleCloseId).hide();
                });
            }
            toggleSection('#toggle-open', '#toggle-close', '#file-section');
            toggleSection('#toggle-open_second', '#toggle-close_second', '#file-section_second');
        });
    </script>
@endsection
