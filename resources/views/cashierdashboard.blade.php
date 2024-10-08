@extends('cashierlayout')
@section('title', 'Cashier Dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2 class="mb-4">Dashboard</h2>

        {{-- <div class="row mb-4">
            <div class="col-lg-6 col-md-12 mb-4"> <!-- Changed to col-lg-6 for both cards -->
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-start justify-content-between">
                            <span class="badge bg-label-warning rounded-pill">Sale Return</span>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>

                            </div>
                        </h5>
                        <p class="card-text">Total Sale Returns: <strong></strong></p>
                        <a href="/salereturn" class="btn btn-outline-warning">View Details</a>
                    </div>
                </div>
            </div> --}}

            <div class="col-lg-6 col-md-12 mb-4"> <!-- Ensure the second card has the same column width -->
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title d-flex align-items-start justify-content-between">
                            <span class="badge bg-label-success rounded-pill">Sale Transaction</span>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt2">
                                    <a class="dropdown-item" href="#">View More</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </h5>
                        <p class="card-text">Total Sale Transactions: <strong></strong></p>
                        <a href="#" class="btn btn-outline-success">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
