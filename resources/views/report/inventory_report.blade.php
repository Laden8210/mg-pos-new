@extends('layout')

@section('title', 'Inventory Report')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Report Title -->
            <h2>Inventory Report</h2>

            <!-- Header with Logo and Business Information -->
            <div class="card mt-2">
                <div class="card-body">
                    <div class="main-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Logo Section -->
                            <div class="col-lg-1 col-md-6 col-12">
                                <a>
                                    <img src="{{ asset('img/MG.png') }}" alt="product" class="logo">
                                </a>
                            </div>
                            <!-- Business Information Section -->
                            <div class="col-lg-11 col-md-6 col-12">
                                <h5 class="app-brand-text menu-text fw-bolder">MG MINI MART</h5>
                                <p>Brgy. Tinongcop, Tantangan, South Cot. <br>Manager: Glo-Ann Panes</p>
                            </div>
                            <!-- Date Picker & Month Filter -->

                        </div>
                        <form method="GET" action="{{ route('inventory_report') }}" id="dateFilterForm">
                            <div class="mb-2 w-50 d-flex justify-content-start gap-2">

                                <div class="form-group">
                                    <label for="" class="form-label">Start Date</label>
                                    <input type="date" name="start_date"
                                        value="{{ request('start_date', now()->toDateString()) }}"
                                        class="form-control w-full"
                                        onchange="document.getElementById('dateFilterForm').submit();">
                                </div>

                                <div class="form-group">
                                    <label for="" class="form-label">End Date</label>
                                    <input type="date" name="end_date"
                                        value="{{ request('end_date', now()->toDateString()) }}" class="form-control w-full"
                                        onchange="document.getElementById('dateFilterForm').submit();">

                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Inventory Report Table -->
            <div class="card mt-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>Inventory ID</th>
                                    <th>Batch</th>
                                    <th>Item Name</th>
                                    <th>Supplier Name</th>
                                    <th>Beginning Inventory</th>
                                    <th>Item Purchased</th>
                                    <th>Category</th>
                                    <th>Item Sold</th>
                                    <th>Ending Sold</th>
                                    <th>Reorder Point</th>
                                    <!-- <th>Status</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salseStockCard as $inventory)
                                    <tr>
                                        <td>{{ $inventory->inventoryId }}</td>
                                        <td>{{ $inventory->batch }}</td>
                                        <td>{{ $inventory->item->itemName ?? 'N/A' }}</td>
                                        <!-- Accessing itemName via relationship -->
                                        <td>{{ $inventory->supplier->CompanyName ?? 'N/A' }}</td>
                                        <!-- Accessing CompanyName via relationship -->
                                        <td>{{ $inventory->original_quantity ?? 'N/A' }}</td>
                                        <!-- Accessing description via relationship -->
                                        <td>{{ $inventory->qtyonhand ?? 'N/A' }}</td>
                                        <!-- Accessing itemCategory via relationship -->
                                        <td>{{ $inventory->item->description }}</td>
                                        <td>{{ \Carbon\Carbon::parse($inventory->date_received)->format('F j, Y') }}</td>
                                        <td>{{ number_format($inventory->original_quantity - $inventory->qtyonhand, 0) }}</td>
                                        <td>{{ number_format($inventory->reorder_point, 0) }}</td>
                                        <!-- <td>{{ $inventory->status }}</td> -->
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <!-- Print Button Section -->
                    <div class="row justify-content-end">
                        <div class="btn-group col-sm-2 mt-5">

                            <form method="GET" action="{{ route('print-inventory-report') }}" target="_blank">

                                <input type="hidden" name="start_date"
                                    value="{{ request('start_date', now()->toDateString()) }}" class="form-control">
                                <input type="hidden" name="end_date"
                                    value="{{ request('end_date', now()->toDateString()) }}" class="form-control">
                                <button class="btn btn-success">Print</button>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
