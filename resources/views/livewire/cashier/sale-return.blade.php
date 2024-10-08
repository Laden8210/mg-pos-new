<div>  <!-- This is the root HTML tag -->
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h1>Sale Return</h1>
            <div class="card">
                <div class="card-header">
                    <div class="search-set">
                        <div class="search-path"></div>
                        <div class="search-input">
                            <input type="text" class="form-control" placeholder="Search"
                                   wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                    <div class="ml-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#transactionNumberModal">Transaction Number</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Transaction No.</th>
                                    <th>SKU</th>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>QTY Returned</th>
                                    <th>Supplier</th>
                                    <th>Date Returned (dd/mm/yy)</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->transaction_no }}</td>
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->qty_returned }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>{{ $item->date_returned }}</td>
                                    <td>{{ $item->reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Find Transaction Modal-->
        <div class="modal fade" id="transactionNumberModal" tabindex="-1" aria-labelledby="transactionNumberLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionNumberModalLabel">Transaction Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <form class="form-inline">
                                    <input class="form-control mr-sm-2" type="search" placeholder="Search"
                                           aria-label="Search">
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Item No.</th>
                                            <th>Item Name</th>
                                            <th>Description</th>
                                            <th>Barcode</th>
                                            <th>QTY</th>
                                            <th>Selling Price</th>
                                            <th>Sub-Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input" value=""
                                                       id="flexCheckDefault">
                                            </td>
                                            <td>{{ $item->item_no }}</td>
                                            <td>{{ $item->item_name }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->barcode }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ $item->selling_price }}</td>
                                            <td>{{ $item->sub_total }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#salesReturnModal">
                            <button type="button" class="btn btn-primary" style="margin-right: 10px;">Confirm</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="salesReturnModal" tabindex="-1" aria-labelledby="salesReturnModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salesReturnModalLabel">Return Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="database/mgpos.php" enctype="multipart/form-data">
                        <div class="row w-100">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Transaction Number</label>
                                    <input type="text" name="transaction_number" placeholder="123-24155436-320" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Item Name</label>
                                    <input type="text" name="item_name" placeholder="Kopiko Blanca Twin Pack" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" name="description" placeholder="3in1 Coffee" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>QTY Returned</label>
                                    <input type="text" name="qty_returned" placeholder="Enter QTY Returned" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <input type="text" name="supplier" placeholder="KCC Mall of Marbel" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Date Returned</label>
                                    <input type="text" name="date_returned" placeholder="15-03-2024" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <input type="text" name="reason" placeholder="Enter Reason" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Return Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
