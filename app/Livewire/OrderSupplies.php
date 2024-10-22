<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\PurchaseOrder;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\StockCard;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PurchaseItem;
use App\Models\InventoryItem;

class OrderSupplies extends Component
{

    public $selectedItems = [];
    public $itemSelection;
    public $itemPrice;
    public $itemQuantity;

    public $totalPrice;
    public $totalQuantity;

    public $selectedEditItem;

    public $itemEditName;
    public $itemEditID;
    public $itemEditQuantity;


    public $itemEditPrice;
    public $itemEditTotalPrice;

    public $orderDetails;

    public $supplierSelection;

    public $filterSupplier;
    public $filterDate;

    public $orginal_quantity;
    public $supplierId;
    public $search = ''; // Search property



    public function mount()
    {
        $this->supplierId = null; // Initialize or set a default value if needed
    }

    public function render()
    {
        return view('livewire.order-supplies', [
            'suppliers' => Supplier::search($this->search)->paginate(10), // Use the search scope and paginate
            'items' => Item::all(),
            'orders' => PurchaseOrder::where('status', 'Pending')
                ->orderBy('purchase_order_id', 'desc')
                ->get()
        ]);
    }
    public function createOrder()
    {
        // Validate inputs
        $this->validate(
            [
                'supplierSelection' => 'required',
                'totalPrice' => 'required|numeric|min:1',
                'totalQuantity' => 'required|numeric|min:1'
            ]
        );

        // Create a new PurchaseOrder instance
        $order = new PurchaseOrder();

        // Generate a unique purchase number
        $order->purchase_number = $this->generateUniquePurchaseNumber();

        // Set other order properties
        $order->SupplierId = $this->supplierSelection;
        $order->total_price = $this->totalPrice;
        $order->quantity = $this->totalQuantity;
        $order->status = 'Pending';
        $order->order_date = now();

        // Save the order
        $order->save();



        // Associate items with the order
        foreach ($this->selectedItems as $item) {
            $order->items()->create([
                'itemID' => $item['item_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],

            ]);
        }

        // Reset form fields
        $this->reset('selectedItems', 'itemSelection', 'itemQuantity', 'totalPrice', 'totalQuantity', 'supplierSelection');

        // Flash success message
        session()->flash('message', 'Order has been created successfully');

        return redirect()->route('print-purchase-order', $order->purchase_number);
    }

    // Method to generate a unique purchase number
    protected function generateUniquePurchaseNumber()
    {
        do {
            // Generate a new purchase number
            $purchaseNumber = 'PO-' . strtoupper(uniqid());
        } while (PurchaseOrder::where('purchase_number', $purchaseNumber)->exists());

        return $purchaseNumber;
    }

    public function addItem()
    {

        $this->validate(
            [
                'itemSelection' => 'required',
                'itemQuantity' => 'required|numeric|min:1'
            ]
        );
        $item = Item::find($this->itemSelection);

        $itemExists = false;


        foreach ($this->selectedItems as &$selectedItem) {
            if ($selectedItem['item_id'] == $item->itemID) {
                $selectedItem['quantity'] += $this->itemQuantity;
                $selectedItem['total_price'] = (int) $this->itemPrice * (int) $selectedItem['quantity'];
                $selectedItem['unit_price'] = $this->itemPrice;
                $itemExists = true;
                break;
            }
        }




        if (!$itemExists) {
            $this->selectedItems[] = [
                'item_id' => $item->itemID,
                'item_name' => $item->itemName,
                'unit_price' => $this->itemPrice,
                'quantity' => $this->itemQuantity,
                'total_price' => (int) $this->itemPrice * (int) $this->itemQuantity
            ];
        }



        $this->totalPrice = $this->summationPrice();
        $this->totalQuantity = $this->summationQuantity();


        $this->reset('itemSelection', 'itemQuantity', 'itemPrice');
    }


    public function updateItem()
    {
        $this->validate(
            [
                'itemEditQuantity' => 'required|numeric|min:1'
            ]
        );

        foreach ($this->selectedItems as $key => $item) {
            if ($item['item_id'] == $this->itemEditID) {
                $this->selectedItems[$key]['quantity'] = $this->itemEditQuantity;
                $this->selectedItems[$key]['total_price'] = (int) $this->selectedItems[$key]['unit_price'] * (int) $this->itemEditQuantity;
            }
        }

        $this->totalPrice = $this->summationPrice();
        $this->totalQuantity = $this->summationQuantity();
        $this->reset('itemEditName', 'itemEditID', 'itemEditQuantity');
    }

    public function editItem($itemId)
    {
        foreach ($this->selectedItems as $item) {
            if ($item['item_id'] == $itemId) {
                $this->itemEditName = $item['item_name'];
                $this->itemEditID = $item['item_id'];
                $this->itemEditQuantity = $item['quantity'];
            }
        }
    }

    public function removeItem($item_id)
    {
        foreach ($this->selectedItems as $key => $item) {
            if ($item['item_id'] == $item_id) {
                unset($this->selectedItems[$key]);
            }
        }
        $this->totalPrice = $this->summationPrice();
        $this->totalQuantity = $this->summationQuantity();
    }

    public function summationPrice()
    {
        $total = 0;
        foreach ($this->selectedItems as $item) {
            $total += $item['total_price'];
        }
        return $total;
    }
    public function summationQuantity()
    {
        $total = 0;
        foreach ($this->selectedItems as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }

    public function selectOrder($orderId)
    {
        $order = PurchaseOrder::find($orderId);

        $this->orderDetails = $order->item;
    }

    public function completeOrder($orderId)
    {
        $order = PurchaseOrder::find($orderId);
        if (!$order) {
            session()->flash('message-status', 'Order not found.');
            return;
        }

        $order->status = 'Completed';
        $order->delivery_date = now();



        $items = PurchaseItem::where('purchase_order_id', $orderId)->get();



        // Debugging: Dump the order details
        foreach ($items as $item) {
            // Check if the item already exists in the inventory
            $existingInventoryItem = InventoryItem::where('itemID', $item->itemID)->first();

            if ($existingInventoryItem) {


                $inventoryID =  $existingInventoryItem->inventory->inventoryId;

                $inventory = Inventory::find($inventoryID);


                $inventoryItem =  $inventory->inventoryItem()->create([
                    'itemID' => $item->itemID,
                    'quantity' => $item->quantity,
                    'received_date' => now(),
                    'unit_price' => $item->unit_price,
                    'purchase_item_id' => $item->purchase_item_id,
                    'selling_price' => $item->unit_price * 1.2
                ]);


                StockCard::create([
                    'inventory_item_id' => $inventoryItem->inventory_item_id,
                    'DateReceived' => now(),
                    'Quantity' => $item->quantity,
                    'Type' => 'Order',
                    'Value' => $item->quantity * $item->unit_price,
                    'Remarks' => 'Received',
                    'supplierItemID' => $order->SupplierId,
                    'purchase_item_id' => $inventoryItem->purchase_item_id
                ]);
            } else {
                // If the item is not recorded in the inventory, create a new inventory and inventory item record
                $inventory = Inventory::create([
                    'original_quantity' => $item->quantity,
                    're_order_point' => $item->quantity,
                    'SupplierId' => $order->SupplierId,
                    'status' => ''
                ]);

                // Create the corresponding inventory item
                $inventoryItem = $inventory->inventoryItem()->create([
                    'itemID' => $item->itemID,
                    'quantity' => $item->quantity,
                    'received_date' => now(),
                    'unit_price' => $item->unit_price,
                    'purchase_item_id' => $item->purchase_item_id,
                    'selling_price' => $item->unit_price * 1.2
                ]);


                $item->inventoryId = $inventory->inventoryId;
                $item->save();


                StockCard::create([
                    'inventory_item_id' => $inventoryItem->inventory_item_id,
                    'DateReceived' => now(),
                    'Quantity' => $item->quantity,
                    'Type' => 'Order',
                    'Value' => $item->quantity * $item->unit_price,
                    'Remarks' => 'Received',
                    'supplierItemID' => $order->SupplierId,
                    'purchase_item_id' => $inventoryItem->purchase_item_id
                ]);
            }



        }


        $order->save();
        session()->flash('message-status', 'Order has been completed and inventory updated successfully.');
    }


    public function cancelOrder($orderId)
    {
        $order = PurchaseOrder::find($orderId);
        $order->status = 'Cancelled';
        $order->save();
        session()->flash('message-status', 'Order has been cancelled successfully');
    }


    public function viewOrderDetails($orderId)
    {

        $order = PurchaseOrder::find($orderId)->with('items')->first();

        $purchaseItem = PurchaseItem::where('purchase_order_id', $orderId)->get();
        $this->orderDetails = $purchaseItem ;


    }

    public function editItemOrder($itemId, $orderId)
    {

        $this->selectedEditItem = PurchaseItem::where('purchase_item_id', $itemId)
        ->where('purchase_order_id', $orderId)
        ->first();
        $this->itemEditName = $this->selectedEditItem->item->itemName;
        $this->itemEditQuantity = $this->selectedEditItem->quantity;

        $this->itemEditPrice = $this->selectedEditItem->unit_price;
    }


    public function updateItemOrder()
    {
        $this->validate([
            'itemEditQuantity' => 'required|numeric|min:1',
            'itemEditPrice' => 'required|numeric|min:1',
        ]);

        // validate quantity

        if ($this->itemEditQuantity > $this->selectedEditItem->quantity) {
            session()->flash('error', 'Quantity cannot be more than order item.');
            return;
        }

        $purchaseOrder = PurchaseOrder::find($this->selectedEditItem->purchase_order_id);

        $purchaseOrder->total_price = $purchaseOrder->total_price -  ($this->itemEditQuantity * $this->itemEditPrice);
        $purchaseOrder->quantity = $purchaseOrder->quantity - $this->selectedEditItem->quantity + $this->itemEditQuantity;
        $purchaseOrder->save();


        $this->selectedEditItem->update([
            'quantity' => $this->itemEditQuantity,
            'unit_price' => $this->itemEditPrice,
        ]);

        session()->flash('message', 'Item and inventory updated successfully.');


    }
}
