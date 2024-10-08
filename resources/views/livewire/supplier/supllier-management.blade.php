<div>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h1>Supplier Information</h1>
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
                            <div class="search-input">
                                <input type="text" class="form-control" placeholder="Search"
                                    wire:model.live.debounce.300ms ="search">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table  datanew">
                            <thead>
                                <tr>


                                    <th>Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact No.</th>
                                    <th>Address</th>
                                    <th>Status</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->CompanyName }}</td>
                                        <td>{{ $supplier->ContactPerson }}</td>
                                        <td>{{ $supplier->ContactNumber }}</td>
                                        <td>{{ $supplier->Address }}</td>

                                        <td>

                                            <span
                                                class="badges {{ $supplier->Status == 1 ? 'bg-lightgreen' : 'bg-lightyellow' }}">{{ $supplier->Status == 0 ? 'Inactive' : 'Active' }}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                data-bs-target="#updateModal" data-bs-toggle="modal"
                                                wire:click="selectSupplier({{ $supplier->SupplierId }})">
                                                <i class="fa fa-edit me-1" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Add New Supplier Button -->
                    <div class="d-flex justify-content-end mt-3">
                        <nav>
                            {{ $suppliers->links('vendor.pagination.bootstrap-5') }}
                        </nav>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">Add New Supplier</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="save">
                    <div class="modal-body">

                        @if (session()->has('message-create'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        <div class="row w-100">

                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" placeholder="Enter Company Name" wire:model="CompanyName">
                                    @if ($errors->has('CompanyName'))
                                        <span class="text-danger">{{ $errors->first('CompanyName') }}</span>
                                    @endif
                                </div>


                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input type="text" placeholder="Enter Contact Person" wire:model="ContactPerson">
                                    @if ($errors->has('ContactPerson'))
                                        <span class="text-danger">{{ $errors->first('ContactPerson') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" placeholder="Enter Contact Number" wire:model="ContactNumber">
                                    @if ($errors->has('ContactNumber'))
                                        <span class="text-danger">{{ $errors->first('ContactNumber') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-lg-12 col-sm-6 col-12">
                                <div>
                                    <label>Status</label>
                                    <select class="form-select" wire:model="Status">
                                        <option>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @if ($errors->has('Status'))
                                        <span class="text-danger">{{ $errors->first('Status') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" placeholder="Enter Address" wire:model="Address">
                                    @if ($errors->has('Address'))
                                        <span class="text-danger">{{ $errors->first('Address') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">

                                <div class="grid grid-cols-2 ">
                                    <div class="form-group">
                                        <label>Search Item</label>
                                        <input type="text" placeholder="Search Item"
                                            wire:model.live.debounce.300ms="itemSearch">
                                    </div>

                                </div>
                                <div class="form-group row p-2 " style="max-height: 200px; overflow: auto">
                                    <label>Items</label>
                                    @foreach ($items as $item)
                                        <div class="form-check col-6">
                                            <input class="form-check-input" type="checkbox" value="{{ $item->itemID }}"
                                                id="flexCheckChecked{{ $item->itemID }}" wire:model="selectedItem">
                                            <label class="form-check-label" for="flexCheckChecked{{ $item->itemID }}">
                                                {{ $item->itemName }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="clearForm">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Update Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="updateSupplier">

                    <div class="modal-body">

                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        <div class="row w-100">

                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" placeholder="Enter Company Name" wire:model="CompanyName">
                                    @if ($errors->has('CompanyName'))
                                        <span class="text-danger">{{ $errors->first('CompanyName') }}</span>
                                    @endif
                                </div>


                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input type="text" placeholder="Enter Contact Person"
                                        wire:model="ContactPerson">
                                    @if ($errors->has('ContactPerson'))
                                        <span class="text-danger">{{ $errors->first('ContactPerson') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" placeholder="Enter Contact Number"
                                        wire:model="ContactNumber">
                                    @if ($errors->has('ContactNumber'))
                                        <span class="text-danger">{{ $errors->first('ContactNumber') }}</span>
                                    @endif
                                </div>

                            </div>

                            <div class="col-lg-12 col-sm-6 col-12">
                                <div>
                                    <label>Status</label>
                                    <select class="form-select" wire:model="Status">
                                        <option>Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @if ($errors->has('Status'))
                                        <span class="text-danger">{{ $errors->first('Status') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" placeholder="Enter Address" wire:model="Address">
                                    @if ($errors->has('Address'))
                                        <span class="text-danger">{{ $errors->first('Address') }}</span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12 col-sm-6 col-12">

                                <div class="grid grid-cols-2 ">
                                    <div class="form-group">
                                        <label>Search Item</label>
                                        <input type="text" placeholder="Search Item"
                                            wire:model.live.debounce.300ms="itemSearch">
                                    </div>

                                </div>
                                <div class="form-group row p-2 " style="max-height: 200px; overflow: auto">
                                    <label>Items</label>
                                    @foreach ($items as $item)
                                        <div class="form-check col-6">
                                            <input class="form-check-input" type="checkbox"
                                                value="{{ $item->itemID }}" id="flexCheckChecked{{ $item->itemID }}"
                                                wire:model="selectedItem">
                                            <label class="form-check-label"
                                                for="flexCheckChecked{{ $item->itemID }}">
                                                {{ $item->itemName }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
