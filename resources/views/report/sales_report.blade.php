@extends('layout')

@section('title', 'Sales Report')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h2>Sales Report</h2>
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
                        <form method="GET" action="{{ route('sales_report') }}" id="dateFilterForm">

                            <div>
                                <input type="date" name="start_date"
                                    value="{{ request('start_date', now()->toDateString()) }}" class="form-control mb-2"
                                    onchange="document.getElementById('dateFilterForm').submit();">
                            </div>
                            <div>
                                <input type="date" name="end_date"
                                    value="{{ request('filter_date', now()->toDateString()) }}" class="form-control mb-2"
                                    onchange="document.getElementById('dateFilterForm').submit();">
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
                                <tr>
                                    <th>INVOICE No.</th>
                                    <th>Date</th>
                                    <th>Item Sold</th>
                                    <th>Selling Price</th>

                                    <th>No. of Items Sold</th>

                                    <th>Total Sales</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saleTransaction as $sale)
                                <tr>
                                    <td>{{ $sale->transaction_number }}</td>
                                    <td>{{ $sale->transactions->first()->created_at->timezone('Asia/Manila')->format('F d, Y') }}
                                    </td>

                                    <td>
                                        @foreach ($sale->transactions as $transaction)
                                            {{ $transaction->item->itemName }}<br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($sale->transactions as $transaction)
                                            P {{ number_format($transaction->total_sell, 2) }}<br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($sale->transactions as $transaction)
                                            {{ $transaction->quantity }}<br>
                                        @endforeach
                                    </td>

                                    <td>

                                            P {{ number_format($sale->total_amount, 2) }}<br>

                                    </td>
                                </tr>


                            @endforeach


                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-end">
                        <div class="btn-group col-sm-2 mt-5">

                            <form method="GET" action="{{ route('print-sales-report') }}" target="_blank">

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
