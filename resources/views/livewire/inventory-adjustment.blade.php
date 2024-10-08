@extends('layout')
@section('title', 'Inventory Adjustment')
@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <h1>Inventory Adjustment</h1>
        <div class="card">
            <div class="card-body">
                <livewire:inventory-adjustment />

                <div class="table-top">
                    <div class="search-set">
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <img src="{{asset('img/icons/search-whites.svg')}}" alt="img">
                                <span><img src="{{asset('img/icons/closes.svg')}}" alt="img"></span>
                            </a>
                        </div>
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="{{asset('img/icons/search-white.svg')}}"
                                    alt="img"></a>
                        </div>
                    </div>
                </div>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h2>Inventory Adjustment</h2>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table datanew">
                                        <thead>
                                            <tr>
                                                <th>Inventory ID</th>
                                                <th>Item Name</th>
                                                <th>Current Quantity</th>
                                                <th>Adjust Quantity</th>
                                                <th>Adjustment Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($inventories as $inventory)
                                                <tr>
                                                    <td>{{ $inventory->inventoryId }}</td>
                                                    <td>{{ $inventory->item->itemName }}</td>
                                                    <td>{{ $inventory->qtyonhand }}</td>
                                                    <td>
                                                        <input type="number"
                                                            wire:model.defer="adjustments.{{ $inventory->inventoryId }}.adjustQty"
                                                            placeholder="Adjust quantity">
                                                    </td>
                                                    <td>
                                                        <select
                                                            wire:model.defer="adjustments.{{ $inventory->inventoryId }}.adjustType">
                                                            <option value="increase">Increase</option>
                                                            <option value="decrease">Decrease</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button
                                                            wire:click="applyAdjustment({{ $inventory->inventoryId }})">Apply</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination Links -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $inventories->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#inventoryAdjustmentModal" wire:click.prevent="inventoryAdjustment">Inventory
                    Adjustment</button>
            </div>

        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="inventoryAdjustmentModal" tabindex="-1" aria-labelledby="inventoryAdjustmentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryAdjustmentModalLabel">inventory Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <!-- Item Details Section -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="itemId" class="form-label">Item ID</label>
                            <input type="text" class="form-control" id="itemId" value="001502" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="itemName" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="itemName" value="Sting" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName"
                                value="Pepsi-Cola Products Philippines, Inc. (PCPPI)" readonly>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control" id="barcode" value="025365894234" readonly>
                        </div>

                        <div class="col-md-3">
                            <label for="batch" class="form-label">Batch</label>
                            <input type="text" class="form-control" id="batch" value="1" readonly>
                        </div>

                        <div class="col-md-3">
                            <label for="contactPerson" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" id="ContactPerson" value="Mr. Leornero Dasican"
                                readonly>
                        </div>

                        <div class="col-md-3">
                            <label for="qtyonhand" class="form-label" QTY on hand</label>
                                <input type="text" class="form-control" id="qtyonhand" value="null">
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    @stop