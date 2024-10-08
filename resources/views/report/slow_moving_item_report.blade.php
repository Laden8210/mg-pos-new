@extends('layout')
@section('title', 'Slow-Moving Items  Report')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h2>Slow-Moving Items Report</h2>
        <div class="card mt-2">
            <div class="card-body">
                <div class="main-content">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="col-lg-1 col-md-6 col-12">
                            <a>
                                <img src="{{asset('img/MG.png')}}" alt="product">
                            </a>
                        </div>
                        <div class="col-log-11 col-md-6 col-12">
                            <h5 class="app-brand-text menu-text fw-bolder">MG MINI MART</h5>
                            <p>Brgy. Tinongcop, Tantangan, South Cot. <br>Manager: Glo-Ann Panes</p>
                        </div>
                        <div>
                            <h3>Time Period</h3>
                            <input type="date" value="2024-10-02" class="form-control mb-2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  datanew">
                        <thead>
                            <tr>
                                <th>ITEM ID</th>
                                <th>ITEM NAME</th>
                                <th>SELLING PRICE</th>
                                <th>UNIT SOLD</th>
                                <th>REVENUE</th>
                                <th>BEGINNING INVENTORY</th>
                                <th>STOCK ON HAND</th>
                                <th>TURNOVER RATE</th>
                                <th>REORDER POINT</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-end">
                    <div class="btn-group col-sm-2 mt-5">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop