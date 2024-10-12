@extends('layout')
@section('title', 'Stock Movement  Report')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2>Stock Movement Report</h2>
        <div class="card mt-2">
            <div class="card-body">
                <div class="main-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-lg-1 col-md-6 col-12">
                            <a>
                                <img src="{{asset('img/MG.png')}}" alt="product">
                            </a>
                        </div>

                    </div>
                    <form method="GET" action="{{ route('stock_movement_report') }}" id="dateFilterForm">

                        <div>
                            <input type="date" name="start_date" value="{{ request('start_date', now()->toDateString()) }}" class="form-control mb-2" onchange="document.getElementById('dateFilterForm').submit();">
                        </div>
                        <div>
                            <input type="date" name="end_date" value="{{ request('filter_date', now()->toDateString()) }}" class="form-control mb-2" onchange="document.getElementById('dateFilterForm').submit();">
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
                            <th>ITEM NUMBER</th>
                            <th>ITEM NAME</th>
                            <th>OPENING STOCK</th>
                            <th>STOCK IN</th>
                            <th>STOCK OUT</th>
                            <th>SALES RETURN</th>
                            <th>CLOSING STOCK</th>
                            <th>STOCK MOVEMENT</th>
                        </thead>
                        <tbody>

                            @foreach ($stockCards as $stockCard)
                            <tr>
                                <td>{{ $stockCard->item->itemID }}</td>
                                <td>{{ $stockCard->item->itemName }}</td>
                                <td>{{ $stockCard->Quantity }}</td>
                                <td>

                                    @php
                                        if ($stockCard->Remarks == 'Sent') {
                                            echo $stockCard->Quantity;
                                        } else {
                                            echo 0;
                                        }
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        if ($stockCard->Remarks == 'Received') {
                                            echo $stockCard->Quantity;
                                        }else if ($stockCard->Remarks == 'Sales Return') {
                                            echo $stockCard->Quantity;
                                        }
                                         else {
                                            echo 0;
                                        }
                                    @endphp

                                </td>
                                <td>
                                    @php
                                        if ($stockCard->Remarks == 'Sales Return') {
                                            echo $stockCard->Quantity;
                                        } else {
                                            echo 0;
                                        }
                                    @endphp
                                </td>
                                <td>{{ $stockCard->inventory->qtyonhand  + $stockCard->Quantity }}</td>
                                <td>

                                    @if ($stockCard->inventory->qtyonhand < $stockCard->Quantity)
                                        <span style="color: red;">Negative</span>

                                    @else
                                        <span style="color:green">Postive</span>
                                        @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="btn-group col-sm-2 mt-5">


                        <form method="GET" action="{{ route('print-stock_movement_report') }}"  target="_blank">

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
