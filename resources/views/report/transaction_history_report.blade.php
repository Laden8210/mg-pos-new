@extends('layout')
@section('title', 'Transaction History  Report')
@section('content')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2>Transaction History Report</h2>
        <div class="card mt-2">
            <div class="card-body">
                <div class="main-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-lg-1 col-md-6 col-12">
                            <a>
                                <img src="{{asset('img/MG.png')}}" alt="product">
                            </a>
                        </div>
                        <div class="col-lg-11 col-md-6 col-12">
                            <h5 class="app-brand-text menu-text fw-bolder">MG MINI MART</h5>
                            <p>Brgy. Tinongcop, Tantangan, South Cot. <br>Manager: Glo-Ann Panes</p>
                        </div>
                        
                    </div>
                    <form method="GET" action="{{ route('transaction_history_report') }}" id="dateFilterForm">
                        <div>
                            <input type="date" name="filter_date" value="{{ request('filter_date', now()->toDateString()) }}" class="form-control mb-2" onchange="document.getElementById('dateFilterForm').submit();">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  datanew">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>TRANSACTION ID</th>
                                <th>ITEM NAME</th>
                                <th>QUANTITY</th>
                                <th>SELLING PRICE</th>
                                <th>TOTAL PRICE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockCards as $stockCard)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($stockCard->DateReceived)->locale('en_PH')->isoFormat('MMMM D, YYYY') }}</td>
                                    <td>{{ $stockCard->StockCardID }}</td>
                                    <td>{{ $stockCard->item ? $stockCard->item->itemName : 'N/A' }}</td>
                                    <td>{{ $stockCard->Quantity }}</td>
                                    <td>{{ number_format($stockCard->item->sellingPrice ?? 0, 2) }}</td>
                                    <td>{{ number_format(($stockCard->item->sellingPrice ?? 0) * $stockCard->Quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="btn-group col-sm-2 mt-5">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                            onclick="window.location.href='{{ route('print-transaction-report') }}'">
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop