<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\StockCard;
use App\Models\SaleTransactionModel;
use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\Transactions;
use Illuminate\Support\Facades\Auth;

class SaleTransaction extends Component
{
    public $items = [];
    public $transactionNumber = 1;
    public $cart = [];
    public $subtotal = 0;
    public $total = 0;
    public $discount = 0;
    public $discountPercentage = 0;
    public $amountTendered = 0;
    public $change = 0;

    public $searchTerm = '';
    public $searchResults = [];
    public $selectedItems = [];

    public $inventoryDetails = [];
    public $showInventory = false;

    public $barcode;

    public $oldBarcode;

    public $selectedItem;

    public $itemQuantity = 0;


    public $invoiceNumber;
    public function mount()
    {
        $this->items = Item::all();
        $this->fetchAllInventory();
        $this->restoreInventory();
        $this->invoiceNumber = $this->generateTransactionNumber();
    }

    public function fetchAllInventory()
    {
        $this->inventoryDetails = InventoryItem::with('item')->get();
    }

    public function toggleInventory()
    {
        $this->showInventory = !$this->showInventory;
        if ($this->showInventory) {
            $this->fetchAllInventory();
        }
    }



    public function updated($propertyName)
    {
        if (in_array($propertyName, ['barcode'])) {
            $this->scanProduct();
        }
    }
    public function scanProduct()
    {
        // Find all inventory records for the scanned product by barcode
        $item = Inventory::with('item')
            ->when($this->barcode, function ($query) {
                $query->whereHas('item', function ($q) {
                    $q->where('barcode', $this->barcode);
                });
            })
            ->first();

        $this->oldBarcode = $this->barcode;


        if (!$item) {
            session()->flash('error', 'Item not found.');
            sleep(1);
            $this->barcode = "";
            return;
        }

        // Sum qtyonhand for all related inventory records
        $totalQtyOnHand = Inventory::where('itemID', $item->itemID)->sum('qtyonhand');


        if ($totalQtyOnHand <= 0) {
            session()->flash('error', 'Item is out of stock.');
            $this->barcode = "";
            return;
        }

        // Check if item is already in the cart
        if (isset($this->cart[$item->itemID])) {
            if ($this->cart[$item->itemID]['quantity'] < $totalQtyOnHand) {
                $this->cart[$item->itemID]['quantity']++;
                $this->cart[$item->itemID]['subtotal'] = $this->cart[$item->itemID]['quantity'] * $this->cart[$item->itemID]['price'];
            } else {
                session()->flash('error', 'Not enough stock available.');
                $this->barcode = "";
                return;
            }
        } else {
            // Add new item to cart
            $this->cart[$item->itemID] = [
                'id' => $item->itemID,
                'name' => $item->item->itemName,
                'price' => $item->item->unitPrice,
                'quantity' => 1,
                'subtotal' => $item->item->unitPrice,
            ];
        }

        // Clear the barcode input field
        $this->barcode = "";

        // Recalculate totals for the cart
        $this->calculateTotals();
    }


    public function addToCart($itemId)
    {
        // Retrieve the inventory item with the related item
        $item = InventoryItem::with('item')
        ->where('itemID', $itemId) // Directly filter by itemID
        ->first();


        // Check if the item was found
        if (!$item) {
            session()->flash('error', 'Item not found.');
            sleep(1);
            $this->barcode = ""; // Clear barcode input
            return;
        }

        // Sum qtyonhand for all related inventory records
        $totalQtyOnHand = InventoryItem::where('itemID', $item->itemID)->sum('quantity');

        // Check if the item is out of stock
        if ($totalQtyOnHand <= 0) {
            session()->flash('error', 'Item is out of stock.');
            $this->barcode = ""; // Clear barcode input
            return;
        }

        // Check if the item is already in the cart
        if (isset($this->cart[$item->itemID])) {
            // Check if there's enough stock available to add to the cart
            if ($this->cart[$item->itemID]['quantity'] < $totalQtyOnHand) {
                $this->cart[$item->itemID]['quantity']++;
                $this->cart[$item->itemID]['subtotal'] = $this->cart[$item->itemID]['quantity'] * $this->cart[$item->item->itemID]['price'];
            } else {
                session()->flash('error', 'Not enough stock available.');
                $this->barcode = ""; // Clear barcode input
                return;
            }
        } else {
            // Add new item to the cart
            $this->cart[$item->itemID] = [
                'id' => $item->item->itemID,
                'name' => $item->item->itemName,
                'price' => $item->selling_price,
                'quantity' => 1,
                'subtotal' => $item->selling_price,
            ];
        }

        // Clear the barcode input field
        $this->barcode = "";

        // Recalculate totals for the cart
        $this->calculateTotals();
        $this->updateCartSubtotal();
    }

    public function removeFromCart($itemId)
    {
        foreach ($this->cart as $key => $item) {
            if ($item['id'] == $itemId) {
                unset($this->cart[$key]);
                break;
            }
        }


        $this->cart = array_values($this->cart);

        $this->calculateTotals();
    }

    public $temporaryInventoryDepletion = [];

    public function preparePrint()
    {
        $cartItems = urlencode(json_encode($this->cart));
        $totalSum = 0;
        $totalItem = 0;

        foreach ($this->cart as $cartItem) {
            $totalSum += $cartItem['price'] * $cartItem['quantity'];
            $totalItem += $cartItem['quantity'];
        }


        $salesTransaction = SaleTransactionModel::create([
            'transaction_number' => $this->invoiceNumber,
            'total_amount' => $totalSum,
            'amount_tendered' => $this->amountTendered,
            'change' => $this->change,
            'vat' => $totalSum * 0.12,
            'subtotal' => $this->subtotal,
            'total_items' => $totalItem,
            'employee_id' => Auth::user()->employee_id,
        ]);


        foreach ($this->cart as $cartItem) {
            $item = InventoryItem::where('itemID', $cartItem['id'])->first();
            if ($item) {
                // Decrease the quantity
                $item->quantity -= $cartItem['quantity'];
                $item->save();

                for ($i = 0; $i < $cartItem['quantity']; $i++) {
                    $this->addToCart($cartItem['id']);
                }

                StockCard::create([
                    'DateReceived' => now(),
                    'Type' => "Sales",
                    'Value' => $cartItem['price'] * $cartItem['quantity'], // Use individual item price to avoid subtotal issues
                    'Quantity' => $cartItem['quantity'],
                    'supplierItemID' => $cartItem['id'],
                    'inventory_item_id' => $item->inventory_item_id,
                    'Remarks' => "Sent",
                ]);

                $salesTransaction->transactions()->
                create([


                    'itemID' => $cartItem['id'],
                    'selling_price' => $cartItem['price'],
                    'quantity' => $cartItem['quantity'],
                    'date_created' => now(),
                    'total_sell' => $cartItem['price'] * $cartItem['quantity'],
                ]);

                $totalSum += $cartItem['price'] * $cartItem['quantity'];

                $totalItem += $cartItem['quantity'];


                $this->temporaryInventoryDepletion[$item->itemID] = $cartItem['quantity'];
            }
        }


        // Ensure subtotal is correct and not altered by the loop
        return redirect()->route('print-reciept', [

            'invoice' => $salesTransaction->transaction_number,

            $this->resetTransaction()
        ]);
    }

    private function generateTransactionNumber()
    {
        // Generate a unique transaction number
        return 'INV-' . now()->format('YmdHis');
    }


    protected function storeTransaction()
    {
        // Save the transaction details to the database
    //     SalesTransaction::create([
    //         'transaction_number' => $this->transactionNumber,
    // / You can customize this as needed
    //         'subtotal' => $this->subtotal,
    //         'total' => $this->total,
    //         'discount' => $this->discount,
    //         'amount_tendered' => $this->amountTendered,
    //         'change' => $this->change,
    //     ]);

        // Increment the transaction number for the next transaction
        $this->transactionNumber++;
        $this->resetTransaction();
    }

    public function restoreInventory()
    {
        foreach ($this->temporaryInventoryDepletion as $itemId => $quantity) {
            $item = Inventory::where('itemID', $itemId)->first();
            if ($item) {
                // Restore the quantity
                $item->qtyonhand += $quantity;
                $item->save();
            }
        }

        // Clear the temporary inventory depletion tracking
        $this->temporaryInventoryDepletion = [];
    }

    public function resetTransaction()
    {
        // Clear the cart
        $this->cart = [];

        // Reset any other related variables
        $this->cart = [];
        $this->subtotal = 0;
        $this->total = 0;
        $this->discount = 0;
        $this->discountPercentage = 0;
        $this->amountTendered = 0;
        $this->change = 0;

        // Optionally reset any session messages
        session()->flash('message', 'Transaction has been reset.');
    }

    public function selectItemToCart($itemId)
    {

        foreach ($this->cart as $key => $item) {
            if ($item['id'] == $itemId) {
                $this->selectedItem = $item;
                $this->itemQuantity = $item['quantity'];

                break;
            }
        }
    }


    public function updateQuantityItemCart()
    {
        foreach ($this->cart as $key => $cartItem) {
            if ($cartItem['id'] == $this->selectedItem['id']) {

                $inventoryItem = Inventory::with('item')
                    ->where('itemID', $this->selectedItem['id'])
                    ->first();

                if (!$inventoryItem) {
                    session()->flash('error', 'Item not found in inventory.');
                    return;
                }


                if ($this->itemQuantity <= $inventoryItem->qtyonhand) {

                    $this->cart[$key]['quantity'] = $this->itemQuantity;


                    $this->cart[$key]['subtotal'] = $this->cart[$key]['quantity'] * $this->cart[$key]['price'];


                    $this->calculateTotals();
                    $this->updateCartSubtotal();


                    session()->flash('message', 'Quantity updated successfully.');
                } else {

                    session()->flash('error', 'Not enough stock available.');
                }

                break;
            }
        }
    }






    public function updateQuantity($itemId, $quantity)
    {
        if (isset($this->cart[$itemId]) && $quantity > 0) {
            $this->cart[$itemId]['quantity'] = $quantity;
            $this->cart[$itemId]['subtotal'] = $this->cart[$itemId]['price'] * $quantity;
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {

        $this->subtotal = 0;

        // Calculate subtotal based on price and quantity for each cart item
        foreach ($this->cart as $cartItem) {
            $this->subtotal += $cartItem['price'] * $cartItem['quantity'];
        }
        $this->total = $this->subtotal - $this->discount;
        $this->change = $this->amountTendered - $this->total;
    }

    public function updateAmountTendered($amount)
    {
        $this->amountTendered = $amount;
        $this->calculateTotals();
    }

    public function updateCartSubtotal()
    {
        foreach ($this->cart as $index => $item) {
            // Calculate subtotal (price * quantity) and store it in the cart array
            $this->cart[$index]['subtotal'] = $item['price'] * $item['quantity'];
        }

        // Optionally call the method to calculate the grand total or other totals
        $this->calculateTotals();
    }


    public function render()
    {
        return view('livewire.cashier.sale-transaction');
    }
}
