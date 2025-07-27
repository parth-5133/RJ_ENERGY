@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{$menuName}}</b></h5>
                </div>
                @if ($permissions['canAdd'])
                    <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                        onClick="fnAddEdit(this,'{{ url('/employee/attendance/request') }}', 0, 'Attendance Request')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Attendance Request
                    </button>
                @endif
            </div>
            <div class="col-12 d-flex align-items-center flex-wrap p-4">
                <div class="form-floating form-floating-outline col-md-2 col-12 me-4 mb-2">
                    <select class="form-select" id="filterMonth" aria-label="Default select example">
                        <option value="">All Months</option>
                        <option value="01">Jan</option>
                        <option value="02">Feb</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">Jun</option>
                        <option value="07">July</option>
                        <option value="08">Aug</option>
                        <option value="09">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>
                    <label for="filterMonth">Month</label>
                </div>

                <div class="form-floating form-floating-outline col-md-2 col-12 me-4 mb-2">
                    <select class="form-select" id="filterYear" aria-label="Default select example">
                        <!-- Options will be dynamically added here -->
                    </select>
                    <label for="filterYear">Year</label>
                </div>

                <a href="javascript:void(0);" id="searchButton"
                    class="btn btn-sm btn-primary waves-effect waves-light mb-2 me-2">
                    <i class="mdi mdi-magnify"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-sm btn-primary mb-2 waves-effect waves-light" id="reset">
                    <i class="mdi mdi-replay me-1"></i> Reset
                </a>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Production</th>
                            <th>Break</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#reset').last().on('click', function() {
                $('#filterMonth').val('');
                $('#filterYear').val('');

                setDefaultDateFilters();
                filterGrid();
            });

            populateYearDropdown('filterYear', -15, 0);

            setDefaultDateFilters();

            const dataTable = initializeDataTable();

            $('#searchButton').on('click', function() {
                dataTable.ajax.reload();
            });
        });

        function setDefaultDateFilters() {
            const currentDate = new Date();
            const currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
            const currentYear = currentDate.getFullYear();

            $('#filterMonth').val(currentMonth);
            $('#filterYear').val(currentYear);
        }

        function initializeDataTable() {
            return $("#grid").DataTable({
                responsive: true,
                autoWidth: true,
                serverSide: false,
                processing: true,
                order: [
                    [1, "asc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.EMPLOYEE_ATTENDANCE_LIST.EMPLOYEE_ATTENDANCE_LIST') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                    data: function(d) {
                        d.filterMonth = $("#filterMonth").val() || "";
                        d.filterYear = $("#filterYear").val() || "";
                    },
                    dataSrc: function(response) {
                        return response.data || [];
                    }
                },
                columns: [{
                        data: "date"
                    },
                    {
                        data: "firstCheckIn"
                    },
                    {
                        data: "lastCheckOut",
                        render: function(data) {
                            return data === "In Progress" ?
                                `<span class="badge rounded bg-label-primary">${data}</span>` :
                                data;
                        }
                    },
                    {
                        data: "production",
                        render: function(data) {
                            if (data === "In Progress") {
                                return `<span class="badge rounded bg-label-primary">${data}</span>`;
                            }
                            const totalMinutes = convertToMinutes(data);
                            return totalMinutes < 360 ?
                                `<span class="badge rounded bg-label-danger">${data}</span>` :
                                data;
                        }
                    },
                    {
                        data: "break",
                        render: function(data) {
                            return data === "No Break" ?
                                `<span class="badge rounded bg-label-info">${data}</span>` :
                                data;
                        }
                    }
                ]
            });
        }

        function convertToMinutes(timeString) {
            const regex = /(\d+)\s*hrs?\s*(\d+)?\s*mins?/i;
            const matches = timeString.match(regex);

            if (matches) {
                const hours = parseInt(matches[1]) || 0;
                const minutes = parseInt(matches[2]) || 0;
                return hours * 60 + minutes;
            }
            return 0;
        }

        function filterGrid() {
            const dataTable = $('#grid').DataTable();
            dataTable.ajax.reload();
        }
    </script>
@endsection
