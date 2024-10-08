@extends('layout')
@section('title', 'Item Management')
@section('content')

    @livewire('item-management')

    <!-- addingModal -->

    <!-- vatModal -->

    <!-- addVatableItemModal -->
    <div class="modal fade" id="addVatableItemModal" tabindex="-1" aria-labelledby="addVatableItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVatableItemModalLabel">New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row w-100">
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" placeholder="Enter SKU" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Item Name</label>
                                <input type="text" placeholder="Enter Item Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" placeholder="Enter Description" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Category</label>
                                <select class="select">
                                    <option>Select Category</option>
                                    <option>Beverages</option>
                                    <option>Snacks</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Barcode</label>
                                <input type="text" placeholder="Enter Barcode" class="form-control">

                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Supplier</label>
                                <select class="select">
                                    <option>Select Supplier</option>
                                    <option>Kcc Mall of Marbel</option>
                                    <option>San Miguel Corporation</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Unit Price</label>
                                <input type="text" placeholder="P 0.00" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Selling Price</label>
                                <input type="text" placeholder="P 0.00" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Date Added</label>
                                <input type="text" placeholder="" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Expiration</label>
                                <input type="text" placeholder="" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Critical Level</label>
                                <input type="text" placeholder="0" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Vatable Item</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vatableItem"
                                            id="vatableItemYes" value="yes">
                                        <label class="form-check-label" for="vatableItemYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vatableItem"
                                            id="vatableItemNo" value="no">
                                        <label class="form-check-label" for="vatableItemNo">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Add other necessary fields as required -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="switchToVatModal()"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="switchToVatModal()">Add Item</button>
                </div>
            </div>
        </div>
    </div>

@stop
