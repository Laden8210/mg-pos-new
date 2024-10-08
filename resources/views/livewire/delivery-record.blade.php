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
                                    <th>Delivery Id</th>
                                    <th>Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact No.</th>
                                    <th>Date Delivered</th>
                                    <th>Address</th>
                                    <th>Total Item</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->purchase_order_id }}</td>
                                        <td>{{ $delivery->supplier->CompanyName }}</td>
                                        <td>{{ $delivery->supplier->ContactPerson }}</td>
                                        <td>{{ $delivery->supplier->ContactNumber }}</td>
                                        <td>{{ $delivery->delivery_date }}</td>
                                        <td>{{ $delivery->supplier->Address }}</td>
                                        <td>{{ $delivery->quantity }}</td>
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
</div>
