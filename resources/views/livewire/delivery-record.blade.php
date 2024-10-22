<div>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h1>Delivery Record</h1>

            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <img src="{{ asset('img/icons/search-whites.svg') }}" alt="img">
                                    <span><img src="{{ asset('img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="search-input">

                                    <input type="text" class="form-control" placeholder="Search"
                                        wire:model.live.debounce.300ms = "search">
                                </div>

                                <div class="search-input">
                                    <select class="form-select" wire:model.live="status">
                                        <option value="">Select Status</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="table-responsive">


                        <table class="table  datanew">
                            <thead>
                                <tr>
                                    <th>Purchase Order</th>
                                    <th>Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact No.</th>
                                    <th>Date Delivered</th>
                                    <th>Address</th>
                                    <th>Total Item</th>
                                    <th>View</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->purchase_number }}</td>
                                        <td>{{ $delivery->supplier->CompanyName }}</td>
                                        <td>{{ $delivery->supplier->ContactPerson }}</td>
                                        <td>{{ $delivery->supplier->ContactNumber }}</td>
                                        <td>{{ \Carbon\Carbon::parse($delivery->delivery_date)->setTimezone('Asia/Manila')->format('F j, Y') }}</td>
                                        <td>{{ $delivery->supplier->Address }}</td>
                                        <td>{{ $delivery->quantity }}</td>
                                        <td>
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                wire:click="viewOrderDetails({{ $delivery->purchase_order_id }})"
                                                data-bs-target="#listModal">View Item</button>

                                                <a href="{{ route('print-purchase-order', $delivery->purchase_number) }}" target="_blank" class="btn btn-success text-white">
                                                    View Receipt
                                                </a>

                                        </td>


                                        <td>
                                            <span
                                                class="badges {{ $delivery->status === 'Completed' ? 'bg-lightgreen' : 'bg-lightred' }}">
                                                {{ $delivery->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="listModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>

                                <th>Selling Price</th>

                                <th>Received Date</th>
                                <th>Expiration Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orderDetails)
                                @foreach ($orderDetails as $order)
                                    <tr>
                                        <td>{{ $order->item->itemName }}</td>
                                        <td>{{ $order->inventoryItem->quantity ?? '0' }}</td>
                                        <td>{{ $order->inventoryItem->unit_price ?? '0.00'}}</td>

                                        <td>{{ $order->inventoryItem->selling_price ?? '0.00' }}</td>
                                        <td>{{ $order->inventoryItem->received_date ?? '' }}</td>
                                        <td>{{ $order->inventoryItem->expiry_date ?? '' }}</td>
                                        <td>
                                            <button class="btn btn-sm fs-10" data-bs-target="#editItem"
                                                data-bs-toggle="modal"
                                                wire:click="editItem({{ $order->purchase_item_id }})">
                                                <i class="fa fa-edit
                                                "
                                                    aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row w-100">
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>MPUJ Body Number</label>
                                <input type="text" placeholder="Enter MPUJ Body Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Plate Number</label>
                                <input type="text" placeholder="Enter Plate Number">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Seat Capacity</label>
                                <input type="text" placeholder="Enter Seat Capacity">
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" placeholder="Enter Model">
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-6 col-12">
                            <div class="form-group">
                                <label>Role</label>
                                <select class="select">
                                    <option>Select Status</option>
                                    <option>Active</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label> Avatar</label>
                                <div class="image-upload">
                                    <input type="file">
                                    <div class="image-uploads">
                                        <img src="{{ asset('img/icons/upload.svg') }}" alt="img">
                                        <h4>Drag and drop a file to upload</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateItem">
                    <div class="modal-body">
                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>

                        @endif
                        <div class="form-group">
                            <label for="">Item Name</label>
                            <input type="text" class="form-control" readonly wire:model="itemEditName">

                        </div>

                        <div class="from-group">
                            <label for="">Quantity</label>
                            <input type="number" class="form-control" wire:model="itemEditQuantity">
                        </div>

                        <div>
                            <label for="">Price</label>
                            <input type="number" class="form-control" wire:model="itemEditPrice">
                        </div>

                        <div>
                            <label for="">Expiration Date</label>
                            <input type="date" class="form-control" wire:model="itemExpirationDate">
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
