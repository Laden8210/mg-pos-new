@extends('layout')
@section('title', 'Sales Return Report')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2>Sales Return Report</h2>
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
                    <form method="GET" action="{{ route('sales_return_report') }}" id="dateFilterForm">
                        <div>
                            <input type="date" name="start_date" value="{{ request('start_date', now()->toDateString()) }}" class="form-control mb-2" onchange="document.getElementById('dateFilterForm').submit();">
                        </div>
                        <div>
                            <input type="date" name="end_date" value="{{ request('end_date', now()->toDateString()) }}" class="form-control mb-2" onchange="document.getElementById('dateFilterForm').submit();">
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
                        <th>RETURN ID</th>
                        <th>RETURN DATE</th>

                        <th>RETURNED ITEM</th>
                        <th>QUANTITY</th>
                        <th>REASON</th>
                        <th>PRICE</th>
                        <th>STATUS</th>


                    </thead>
                    <tbody>
                    @foreach($stockCards as $stockCard)
                        <tr>
                            <td>R00{{ $stockCard->StockCardID }}</td>
                            <td>{{ \Carbon\Carbon::parse($stockCard->DateReceived)->format('m-d-Y   ') }}</td>

                            <td>{{ $stockCard->inventoryItem ? $stockCard->inventoryItem->item->itemName : 'N/A' }}</td>
                            <td>{{ $stockCard->Quantity }}</td>
                            <td>{{ $stockCard->Remarks}}</td>
                            <td>{{ number_format($stockCard->Value ?? 0, 2)}}</td><!--TOTAL AMOUNT HERE-->
                            <td>Returned</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                </div>
                <div class="row justify-content-end">
                    <div class="btn-group col-sm-2 mt-5">


                        <form method="GET" action="{{ route('print-sales-return-report') }}"  target="_blank">

                            <input type="hidden" name="start_date" value="{{ request('start_date', now()->toDateString()) }}"
                            class="form-control">
                            <input type="hidden" name="end_date" value="{{ request('end_date', now()->toDateString()) }}"
                            class="form-control" >
                            <button class="btn btn-success">Print</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
