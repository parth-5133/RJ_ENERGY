@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row gy-4 gx-4">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="widget-round">
                                <div class="bg-round">
                                    <i class="mdi mdi-account-outline mdi-24px"></i>
                                    <div class="half-circle">
                                        <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="card-info">
                                <h6 class="mb-0 text-truncate">Total No of Company</h6>
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">
                                        <span>15</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="widget-round">
                                <div class="bg-round">
                                    <i class="mdi mdi-account-outline mdi-24px"></i>
                                    <div class="half-circle">
                                        <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="card-info">
                                <h6 class="mb-0 text-truncate">No of New Company</h6>
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">
                                        <span>10</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="widget-round">
                                <div class="bg-round">
                                    <i class="mdi mdi-account-outline mdi-24px"></i>
                                    <div class="half-circle">
                                        <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="card-info">
                                <h6 class="mb-0 text-truncate">No of Lost Company</h6>
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">
                                        <span>1</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-100">
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Company Overview</h5>
                        </div>
                        <div class="card-body">
                            <div id="chart" class="custom-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            loadHeaderData();
        });

        function loadHeaderData() {
            const url = `{{ config('apiConstants.SUPER_ADMIN_DASHBOARD.COMPANY_STATUS_OVERVIEW') }}`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    var active = response.data.active;
                    var inactive = response.data.inactive;
                    var lost = response.data.lost;

                    var options = {
                        series: [active, inactive, lost],
                        chart: {
                            type: 'donut',
                            width: '100%',
                            height: '320px',
                        },
                        labels: ['Active', 'Inactive', 'Lost'],
                        colors: ['#28a745', '#ffc107', '#dc3545'],
                        plotOptions: {
                            pie: {
                                startAngle: -90,
                                endAngle: 270,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        fill: {
                            type: 'gradient',
                        },
                        legend: {
                            position: 'bottom',
                            formatter: function(val, opts) {
                                return val + " - " + opts.w.globals.series[opts.seriesIndex];
                            }
                        },
                        responsive: [{
                            breakpoint: 992,
                            options: {
                                chart: {
                                    height: 280,
                                },
                            },
                        }, {
                            breakpoint: 576,
                            options: {
                                chart: {
                                    height: 240
                                },
                            }
                        }]
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                } else {
                    console.log('Failed to retrieve Data.');
                }
            });
        }
    </script>
@endsection
