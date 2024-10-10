<div>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h2>Sales Transaction</h2>
            <div class="card">
                <div class="card-body">
                    <h3>Counter</h3>

                    <div class="w-25 my-2">
                        <input type="text" autofocus id="barcode" placeholder="Scan here"
                            wire:model.live.debounce.1000ms="barcode" class="form-control barcode">
                    </div>

                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="table-responsive" wire:poll>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Sub-Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $cartItem)
                                    <tr>
                                        <td>{{ $cartItem['name'] }}</td>
                                        <td>P {{ number_format($cartItem['price'], 2) }}</td>
                                        <td>{{ $cartItem['quantity'] }}</td>
                                        <td>P {{ number_format($cartItem['subtotal'], 2) }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" type="button"
                                                wire:click="removeFromCart({{ $cartItem['id'] }})"><i class="fa fa-trash"
                                                    aria-hidden="true"></i></button>
                                            <button class="btn btn-secondary btn-sm" type="button"
                                                wire:click="selectItemToCart({{ $cartItem['id'] }})" data-bs-toggle="modal"
                                                data-bs-target="#editQuantity"><i class="fa fa-edit"
                                                    aria-hidden="true"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal fade " id="editQuantity" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="editQuantityLabel" aria-hidden="true" wire:ignore>
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="editQuantityLabel">Edit Quantity</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="updateQuantityItemCart">
                                        <div class="form-group"><label for="quantity">Quantity</label>
                                            <input type="number" wire:model="itemQuantity" class="form-control"
                                                id="quantity" placeholder="Enter Quantity" min="1">
                                        </div>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary"
                                            data-bs-dismiss="modal">Confirm</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>SubTotal</label>
                                            <input type="text" class="form-control"
                                                value="P {{ number_format($subtotal, 2) }}" readonly>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Total</label>
                                            <input type="text" class="form-control"
                                                value="P {{ number_format($total, 2) }}" readonly>
                                        </div>

                                        <div class="col-sm-6 mt-3">
                                            <label>Amount Tendered</label>
                                            <input type="number" wire:model="amountTendered"
                                                wire:change="updateAmountTendered($event.target.value)"
                                                class="form-control" placeholder="Enter Amount Tendered" min="0"
                                                step="0.01">
                                        </div>
                                        <div class="col-sm-6 mt-3">
                                            <label>Change</label>
                                            <input type="text" class="form-control"
                                                value="P {{ number_format($change, 2) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                        <h5>Transaction No: 0001</h5>
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-success btn-lg w-100"
                                        wire:click="preparePrint">Print</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button wire:click="toggleInventory" class="btn btn-primary">
                            {{ $showInventory ? 'Hide Inventory' : 'View Inventory' }}
                        </button>
                    </div>

                    @if ($showInventory)
                        <div class="mt-3">
                            <h4>Inventory</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Barcode</th>
                                            <th>Item ID</th>
                                            <th>Item Name</th>
                                            <th>Price</th>
                                            <th>Quantity Available</th>
                                            <th>Add to Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $groupedItems = $inventoryDetails->groupBy('itemID');
                                    @endphp

                                    @foreach ($groupedItems as $itemID => $items)
                                        @php
                                            // Calculate the total quantity and get the first item's details for display
                                            $totalQty = $items->sum('qtyonhand');
                                            $firstItem = $items->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $firstItem->item->barcode }}</td>
                                            <td>{{ $itemID }}</td>
                                            <td>{{ $firstItem->item->itemName }}</td>
                                            <td>P {{ number_format($firstItem->item->unitPrice, 2) }}</td>
                                            <td>{{ $totalQty }}</td>
                                            <td>
                                                <button wire:click="addToCart({{ $firstItem->item->itemID }})"
                                                    class="btn btn-success btn-sm">Add to Count</button>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // let inputSequence = '';  // Store the sequence of keys pressed
        // let lastKeyTime = Date.now();  // Track the time of the last keypress

        // // Define a function to detect barcode-like input
        // function isBarcode(input) {
        //     // Here you can define barcode rules, like length or specific prefix
        //     return input.length >= 14 && /^[0-9]+$/.test(input);  // Example: barcode is a numeric string of at least 8 digits
        // }

        // document.addEventListener('keydown', function(event) {
        //     const currentTime = Date.now();

        //     // If the time between keypresses is very small (e.g., less than 100 ms), assume it's part of a barcode
        //     if (currentTime - lastKeyTime < 200) {
        //         inputSequence += event.key;  // Accumulate key presses quickly
        //     } else {
        //         // Reset if the time between keypresses is too long (indicating manual typing)
        //         inputSequence = event.key;
        //     }

        //     lastKeyTime = currentTime;

        //     const barcodeInputs = document.getElementsByClassName('barcode');
        //         if (barcodeInputs.length > 0) {
        //             barcodeInputs[0].value = inputSequence; // Set the value of the first barcode input
        //         }

        //     // Check if the input matches a barcode-like format
        //     if (isBarcode(inputSequence)) {
        //         const barcodeInputs = document.getElementsByClassName('barcode');
        //         if (barcodeInputs.length > 0) {
        //             barcodeInputs[0].value = inputSequence; // Set the value of the first barcode input
        //         }
        //         console.log('Barcode detected:', inputSequence); // Log to console instead of alert
        //         inputSequence = '';  // Reset the input sequence after detection
        //     }
        // });


    </script>
</div>
