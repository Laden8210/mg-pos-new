@extends('layout')
@section('title', 'Reorder List Report')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2>Reorder List Report</h2>
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

                    <form method="GET" action="{{ route('reorder_list_report') }}" id="dateFilterForm">
                        <div class="mb-2 w-50 d-flex justify-content-start gap-2">

                          <div class="form-group">
                              <label for="" class="form-label">Start Date</label>
                              <input type="date"  name="start_date" value="{{ request('start_date', now()->toDateString()) }}"
                              class="form-control w-full" onchange="document.getElementById('dateFilterForm').submit();">
                          </div>

                          <div class="form-group">
                              <label for="" class="form-label">End Date</label>
                              <input type="date"  name="end_date" value="{{ request('end_date', now()->toDateString()) }}"
                              class="form-control w-full" onchange="document.getElementById('dateFilterForm').submit();">

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
                            <th>ITEM NUMBER</th>
                            <th>ITEM NAME</th>
                            <th>SUPPLIER NAME</th>
                            <th>QTY ON HAND</th>
                            <th>REORDER POINT</th>
                            <th>ORIGINAL QUANTITY</th>
                            <th>LAST ORDER</th>
                            <th>UNIT PRICE</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            @foreach($reorderItems as $item)
                                <tr>
                                    <td>{{ $item->batch }}</td>

                                    <td>{{ $item->item->itemName ?? 'N/A' }}</td> <!-- Access item name -->
                                    <td>{{ $item->supplier->CompanyName ?? 'N/A' }}</td> <!-- Access supplier name -->

                                    <td>{{ $item->qtyonhand }}</td> <!-- Quantity on hand -->
                                    <td>{{ $item->reorder_point }}</td><!-- Reorder point -->
                                    <td>{{$item->original_quantity}}</td><!--ORIGINAL QUANTITY HERE-->
                                    <td>{{$item->date_received}}</td><!--UNIT PRICE HERE-->
                                    <td>{{$item->item->unitPrice}}</td>
                                    <td>
                                        @php

                                            $threshold = $item->reorder_point;
                                            $qty = $item->qtyonhand;

                                            if($qty <= $threshold){
                                                echo "Reorder";
                                            }else{
                                                echo "Sufficient";
                                            }
                                        @endphp

                                    </td><!--TOTAL COST HERE-->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="btn-group col-sm-2 mt-5">

                        <form method="GET" action="{{ route('print-reorder-list-report') }}" target="_blank">

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
