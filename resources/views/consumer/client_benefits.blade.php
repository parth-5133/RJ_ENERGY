@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="container py-5">

                <!-- Benefits Section -->
                <h2 class="text-center fw-bold mb-4">Benefits</h2>
                <div class="row g-4 text-center">
                    <div class="col-md-4">
                        <div class="p-4 border rounded-3 shadow-sm bg-white h-100">
                            <div class="mb-3 fs-2 text-primary">
                                <i class="fas fa-home"></i>
                            </div>
                            <h5 class="text-primary">Subsidy for Residential Households</h5>
                            <p class="mb-2 fs-5 text-dark">₹30,000 <small class="text-muted">per kW</small></p>
                            <p class="text-muted mb-0">up to 2 kW</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 border rounded-3 shadow-sm bg-white h-100">
                            <div class="mb-3 fs-2 text-warning">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <p class="mb-2 fs-5 text-dark">₹18,000 <small class="text-muted">per kW</small></p>
                            <p class="text-muted mb-0">for additional capacity up to 3 kW</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 border rounded-3 shadow-sm bg-white h-100">
                            <div class="mb-3 fs-2 text-success">
                                <i class="fas fa-rupee-sign"></i>
                            </div>
                            <p class="mb-2 fs-5 text-dark">₹78,000</p>
                            <p class="text-muted mb-0">max subsidy capped at 3 kW</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 mb-5">
                    <small class="text-muted fst-italic">
                        For special states, an additional 10% subsidy is applicable per kW
                    </small>
                </div>

                <!-- Estimator Table -->
                <div class="border rounded-3 p-4 shadow-sm bg-light mb-5">
                    <h5 class="text-center fw-bold mb-3">Suitable Rooftop Solar Plant Capacity</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Average Monthly Electricity Consumption (Units)</th>
                                    <th>Suitable Rooftop Solar Plant Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>0–150</td>
                                    <td>1–2 kW</td>
                                </tr>
                                <tr>
                                    <td>150–300</td>
                                    <td>2–3 kW</td>
                                </tr>
                                <tr>
                                    <td>300+</td>
                                    <td>Above 3 kW</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
