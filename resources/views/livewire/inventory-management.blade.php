<div>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h2>Inventory Management</h2>
            <div class="card">
                <div class="card-body">
                    <div class="table-top"></div>

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session()->has('reorderNotification'))
                        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header">
                                    <i class="fas fa-exclamation-circle me-2"
                                        style="font-size: 1.5rem; color: #d9534f;"></i>
                                    <strong class="me-auto text-danger">Low Stock Alert</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                                        aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    {{ session('reorderNotification') }}
                                </div>
                            </div>
                        </div>
                        <script>
                            setTimeout(function() {
                                const toastElement = document.querySelector('.toast');
                                const bsToast = new bootstrap.Toast(toastElement);
                                bsToast.hide();
                            }, 3000); // 3000 milliseconds = 3 seconds
                        </script>
                    @endif

                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>

                                <th class="text-center">Item Name</th>

                                <th class="text-center">Description</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">QTY on Hand</th>

                                <th class="text-center">Reorder Point</th>
                                <th class="text-center">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventories as $inventory)
                                    <tr>


                                        <td class="text-center">
                                            {{ $inventory->item ? $inventory->item->itemName : 'N/A' }}
                                        </td>
                                        @php

                                            $companyName = '';
                                            foreach ($supplier as $sup) {
                                                if ($sup->SupplierId == $inventory->SupplierId) {
                                                    $companyName = $sup->CompanyName;
                                                    break;
                                                }
                                            }
                                        @endphp


                                        <td class="text-center">
                                            {{ $inventory->item ? $inventory->item->description : 'N/A' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $inventory->item ? $inventory->item->itemCategory : 'N/A' }}
                                        </td>
                                        <td class="text-center">{{ $inventory->total_quantity }}</td>


                                        <td class="text-center">
                                            @php
                                                // Calculate the reorder point dynamically based on 40% of the qtyonhand
                                                $reorderPoint = $inventory->re_order_point * 0.3;

                                            @endphp

                                            @if ($inventory->total_quantity <= $reorderPoint)
                                                <span class="badge bg-danger">Critical level</span>
                                            @else
                                                <span class="badge bg-success">Sufficient Stock</span>
                                            @endif

                                        </td>
                                        <td class="text-center">

                                            @if ($inventory->total_qtyonhand <= $reorderPoint)
                                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton{{ $inventory->inventoryId }}"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton{{ $inventory->inventoryId }}">
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="confirmReorder({{ $inventory->inventoryId }})">Re-order</a>
                                                    </li>

                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click.prevent="cancelReorder({{ $inventory->inventoryId }})">Cancel</a>
                                                    </li>
                                                </ul>
                                            @else
                                                {{ $inventory->status }}
                                                <span>No Action Needed</span>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No inventories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $inventories->links('pagination::bootstrap-4') }} <!-- Pagination links -->
                    </div>
                    <div class="button-group mt-4">

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            style="margin-right: 10px;" data-bs-target="#adjustCardModal"
                            wire:click.prevent="viewAdjustItem">Adjust
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            style="margin-right: 10px;" data-bs-target="#stockCardModal"
                            wire:click.prevent="viewStockCard">View Stock
                            Card</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            style="margin-right: 10px;" data-bs-target="#stockCardModal"
                            wire:click.prevent="showReorderCard">Create Reorder</button>


                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#stockCardModal" wire:click.prevent="viewSaleReturn">Sale Return</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @if ($adjustInventory)


        <div class="modal fade show" id="adjustCardModal" tabindex="-1" aria-labelledby="stockCardModalLabel"
            aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-sm">
                <form wire:submit.prevent="saveUpdate">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="stockCardModalLabel">Adjust Item</h5>

                            <button type="button" class="btn-close" wire:click="closeStockModal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="" id="" class="form-select "
                                    wire:model="selectedItemAdjustment">

                                    <option value="">Select Item</option>
                                    @foreach ($inventories as $inventory)
                                        <option value="{{ $inventory->item->itemID }}">
                                            {{ $inventory->item->itemName }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div>
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" wire:model="quantity">
                            </div>

                            <div>
                                <label for="remarks" class="form-label">Remarks</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="StockIn" name="remarks"
                                        id="flexCheckDefault" wire:model="remarks">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Stock In
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="StockOut" name="remarks"
                                        id="flexCheckDefault" wire:model="remarks">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Stock Out
                                    </label>
                                </div>

                            </div>



                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="closeStockModal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endif


    @if ($saleReturn)


        <div class="modal fade show" id="adjustCardModal" tabindex="-1" aria-labelledby="stockCardModalLabel"
            aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-sm">
                <form wire:submit.prevent="saveSaleReturn">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="stockCardModalLabel">Sale Return</h5>

                            <button type="button" class="btn-close" wire:click="closeStockModal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <select name="" id="" class="form-select "
                                    wire:model="selectedItemAdjustment">

                                    <option value="">Select Item</option>
                                    @foreach ($inventories as $inventory)
                                        <option value="{{ $inventory->item->itemID }}">
                                            {{ $inventory->item->itemName }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div>
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" wire:model="quantity">
                            </div>

                            <div>
                                <label for="remarks" class="form-label">Remarks</label>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Expired" name="remarks"
                                        id="flexCheckDefault" wire:model="remarks">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Expired
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" value="Defect" name="remarks"
                                        id="flexCheckDefault" wire:model="remarks">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Defect
                                    </label>
                                </div>
                            </div>



                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="closeStockModal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endif


    @if ($reorderCard)
        <div class="modal fade show" id="stockCardModal" tabindex="-1" aria-labelledby="stockCardModalLabel"
            aria-hidden="true" style="display: block;">

            <form wire:submit.prevent="saveBulkOrder">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="stockCardModalLabel">Inventory Management > Reorder Item</h5>

                            <button type="button" class="btn-close" wire:click="closeReorderCard"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="container-fluid">
                                <div class="form-group w-25 d-flex">
                                    <select name="selectedSupplier" id="" class="form-select "
                                        wire:model.live="selectSupplier">

                                        <option value="">Select Supplier</option>
                                        @foreach ($supplier as $sup)
                                            <option value="{{ $sup->SupplierId }}">
                                                {{ $sup->CompanyName }}
                                            </option>
                                        @endforeach
                                    </select>


                                </div>

                                <div class="table-responsive">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>
                                                    Select Item
                                                </th>
                                                <th>Item Name</th>
                                                <th>Supplier</th>
                                                <th>Remaining Quantity</th>


                                                <th>Reorder Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($reorder as $inventory)
                                                @if ($inventory->total_quantity <= $inventory->re_order_point)
                                                    <tr>
                                                        <td class="text-center">
                                                            <input type="checkbox" wire:model="selectedItems"
                                                                value="{{ $inventory->inventoryId }}">
                                                        </td>

                                                        <td class="text-center">
                                                            {{ $inventory->item ? $inventory->item->itemName : 'N/A' }}
                                                        </td>
                                                        @php

                                                            $companyName = '';
                                                            foreach ($supplier as $sup) {
                                                                if ($sup->SupplierId == $inventory->SupplierId) {
                                                                    $companyName = $sup->CompanyName;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        <td>{{ $companyName }}</td>
                                                        <td class="text-center">{{ $inventory->total_quantity }}</td>

                                                        <td class="text-center">{{ $inventory->re_order_point }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="closeReorderCard">Close</button>

                            <button type="submit" class="btn btn-primary">Reorder</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    @if ($showStockCardModal)
        <div class="modal fade show" id="stockCardModal" tabindex="-1" aria-labelledby="stockCardModalLabel"
            aria-hidden="true" style="display: block;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="stockCardModalLabel">Inventory Management > Stock
                            Card</h5>

                        <button type="button" class="btn-close" wire:click="closeStockModal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="container-fluid">
                            <div class="form-group w-25 d-flex">
                                <select name="selectedItem" id="" class="form-select "
                                    wire:model.live="selectedItem">

                                    <option value="">Select Item</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->itemID }}">
                                            {{ $item->itemName }}
                                        </option>
                                    @endforeach
                                </select>


                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>

                                            <th>Date</th>
                                            <th>Quantity In</th>
                                            <th>Value In</th>
                                            <th>Quantity Out</th>
                                            <th>Value Out</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($stockCardInventories && $stockCardInventories->isNotEmpty())
                                            @foreach ($stockCardInventories as $stockCard)
                                                @php
                                                    // Initialize quantities for each stock card
                                                    $valueIn = 0;
                                                    $valueOut = 0;
                                                    $totalQuantityIn = 0;
                                                    $totalQuantityOut = 0;

                                                    if (
                                                        $stockCard->Remarks === 'Received' ||
                                                        $stockCard->Remarks === 'StockIn'
                                                    ) {
                                                        $totalQuantityIn += $stockCard->Quantity;
                                                        $valueIn += $stockCard->Value;
                                                    } else {
                                                        $totalQuantityOut += $stockCard->Quantity;
                                                        $valueOut += $stockCard->Value;
                                                    }

                                                @endphp



                                                <tr>
                                                    <td>{{ $stockCard->inventoryItem->item->itemName }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($stockCard->DateReceived)->format('m/d/Y') }}
                                                    </td>
                                                    <td>{{ $totalQuantityIn }}</td>
                                                    <td>₱ {{ number_format($valueIn, 2) }}</td>
                                                    <!-- Format currency -->
                                                    <td>{{ $totalQuantityOut }}</td>
                                                    <td>₱ {{ number_format($valueOut, 2) }}</td>
                                                    <!-- Format currency -->
                                                    <td>{{ $stockCard->Remarks }}</td>
                                                    <!-- This should display the last remarks; adjust as necessary -->
                                                </tr>
                                            @endforeach
                                        @endif


                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeStockModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('message-status'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="fas fa-info-circle me-2" style="font-size: 1.5rem;"></i>
                    <strong class="me-auto">Order Status</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('message-status') }}
                </div>
            </div>
        </div>

        <script>
            // Auto-hide the toast after 2 seconds
            var toastElement = document.querySelector('.toast');
            var toast = new bootstrap.Toast(toastElement); // Initialize Bootstrap toast
            setTimeout(function() {
                toast.hide(); // Use Bootstrap method to hide the toast
            }, 2000);
        </script>
    @endif


</div>
