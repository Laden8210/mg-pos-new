@extends('layout')
@section('title', 'Purchase Order List Report')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h2>Purchase Order List Report</h2>
            <div class="card mt-2">
                <div class="card-body">
                    <div class="main-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="col-lg-1 col-md-6 col-12">
                                <a>
                                    <img src="{{ asset('img/MG.png') }}" alt="product">
                                </a>
                            </div>
                            <div class="col-lg-11 col-md-6 col-12">
                                <h5 class="app-brand-text menu-text fw-bolder">MG MINI MART</h5>
                                <p>Brgy. Tinongcop, Tantangan, South Cot. <br>Manager: Glo-Ann Panes</p>
                            </div>
                        </div>
                        <form method="GET" action="{{ route('order_list_report') }}" id="dateFilterForm">
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
            <div class="card mt-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <th>ORDER NUMBER</th>
                                <th>SUPPLIER NAME</th>
                                <th>ORDER DATE</th>
                                <th>ITEM NAME</th>
                                <th>TOTAL ITEM</th>
                                <th>UNIT PRICE</th>
                                <th>TOTAL COST</th>
                                <th>DELIVER DATE</th>
                                <th>QTY REORDER</th>
                            </thead>
                            <tbody>
                                @foreach ($purchasedOrders as $purchaseOrder)
                                    @foreach ($purchaseOrder->purchaseItems as $purchaseItem)
                                        <tr>
                                            <td>{{ $purchaseItem->purchase_item_id }}</td>
                                            <td>{{ $purchaseOrder->supplier->CompanyName }}</td>
                                            <td>{{ $purchaseOrder->order_date }}</td>
                                            <td>{{ $purchaseItem->item->itemName }}</td>
                                            <td>{{ $purchaseItem->quantity }}</td>
                                            <td>{{ $purchaseItem->item->unitPrice }}</td>
                                            <td>{{ $purchaseItem->total_price }}</td>
                                            <td>{{ $purchaseOrder->delivery_date }}</td>
                                            <td>{{ $purchaseItem->inventory->original_quantity ?? '' }}</td>
                                            {{-- <td>{{  }}</td>
                                        <td>{{  }}</td> --}}
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-end">
                        <div class="btn-group col-sm-2 mt-5">


                            <form method="GET" action="{{ route('print-order-list-report') }}" target="_blank">

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
