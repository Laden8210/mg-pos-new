<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inventory;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseItem;
use App\Models\StockCard;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Item;

class InventoryManagement extends Component
{

    use WithPagination;

    public $search;
    public $selectedInventory;
    public $selectedItem = "";
    public $stockCardInventories;
    public $showStockCardModal = false;
    public $selectedItemAdjustment;
    public $quantity;
    public $remarks;
    public $adjustInventory = false;

    public $selectedItems = [];

    public $reorderCard = false;


    public $saleReturn = false;

    public $selectSupplier;
    public function mount()
    {
        $this->search = '';
        $this->selectedInventory = null;
    }

    public function updatedSelectedItem($value)
    {
        // This method will be called whenever selectedItem changes
        $this->stockCardInventories = StockCard::with(['inventoryItem.item']) // Load related item for each inventory item
            ->whereHas('inventoryItem', function ($query) use ($value) {
                $query->where('itemID', $value); // Filter based on itemID
            })
            ->get();
    }



    public function adjustInventory()
    {
        // Validate the incoming request
        $this->validate([
            'selectedItemAdjustment' => 'required|exists:inventory,inventoryId',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'required|string',
        ]);

        // Get the inventory item
        $inventory = Inventory::find($this->selectedItemAdjustment);



        // Adjust the inventory quantity based on remarks
        switch ($this->remarks) {
            case 'StockIn':
                $inventory->qtyonhand += $this->quantity;
                $stockCardType = 'StockIn';
                break;

            case 'StockOut':
                // Ensure sufficient quantity before subtracting
                if ($inventory->qtyonhand < $this->quantity) {
                    return response()->json(['error' => 'Insufficient inventory'], 400);
                }
                $inventory->qtyonhand -= $this->quantity;
                $stockCardType = 'StockOut';
                break;

            case 'Sales Return':
                $inventory->qtyonhand += $this->quantity;
                // Assuming the selling price is stored in the item relation
                $value = $inventory->item->sellingPrice * $this->quantity;
                $stockCardType = 'Sales Return';
                break;

            default:
                return response()->json(['error' => 'Invalid remark type'], 400);
        }

        // Save the adjusted inventory
        $inventory->save();

        // Create a new StockCard record
        StockCard::create([
            'DateReceived' => now(),
            'Type' => $stockCardType,
            'Value' => $stockCardType === 'Sales Return' ? $value : 0,
            'Quantity' => $this->quantity,
            'supplierItemID' => $inventory->supplierId,
            'inventoryId' => $inventory->inventoryId,
            'Remarks' => $this->remarks,
        ]);

        return response()->json(['success' => 'Inventory adjusted successfully.']);
    }

    public function render()
    {
        $supplies = PurchaseOrder::all();
        $supplier = Supplier::all();



        $inventories = InventoryItem::with('inventory', 'item')
            ->when($this->search, function ($query) {
                $query->where('itemID', $this->search);
            })
            ->join('inventory', 'inventory_items.inventoryId', '=', 'inventory.inventoryId') // Join inventories table
            ->selectRaw('itemID, SUM(quantity) as total_quantity, MIN(inventory.re_order_point) as re_order_point, inventory.inventoryId') // Use MIN to get the first re_order_point
            ->groupBy('itemID', 'inventory.inventoryId') // Group by itemID and inventoryId
            ->paginate(10);

            $reorder = InventoryItem::with('inventory', 'item')
            ->when($this->search, function ($query) {
                $query->where('itemID', $this->search);
            })
            ->join('inventory', 'inventory_items.inventoryId', '=', 'inventory.inventoryId') // Join inventories table
            ->selectRaw('
                inventory_items.itemID,
                SUM(inventory_items.quantity) as total_quantity,
                MIN(inventory.re_order_point) as re_order_point,
                inventory.inventoryId,
                inventory.SupplierId
            ') // Select fields with MIN for re_order_point
            ->where('inventory.SupplierId',$this->selectSupplier) // Filtering by SupplierId
            ->groupBy('inventory_items.itemID', 'inventory.inventoryId', 'inventory.SupplierId') // Group by necessary fields
            ->paginate(10);


        $items = Item::all();


        return view('livewire.inventory-management', [
            'inventories' => $inventories, // Pass inventory data
            'supplies' => $supplies, // Pass supplier data
            'supplier' => $supplier, // Pass supplier data
            'items' => $items, // Pass item data
            'stockCardInventories' => $this->stockCardInventories,
            'reorder' => $reorder
        ]);
    }



    public function updated($propertyName)
    {
        if (in_array($propertyName, ['barcode'])) {
            $this->scanProduct();
        }
    }


    public function checkReorderPoint($inventory)
    {

        $totalQuantity = $inventory->original_quantity;
        $currentQtyOnHand = $inventory->total_qtyonhand;
        $reorderThreshold = $totalQuantity * 0.3;

        if ($currentQtyOnHand <= $reorderThreshold) {
            session()->flash('reorderNotification', 'Low Stock: Item ' . $inventory->item->itemName . ' has reached its reorder point. Only ' . $currentQtyOnHand . ' left.');
            $inventory->status = 'Not Yet';
        } else {
            $inventory->status = ''; // Adjust this value as needed
        }
        $inventory->save();
    }

    protected function generateUniquePurchaseNumber()
    {
        do {
            // Generate a new purchase number
            $purchaseNumber = 'PO-' . strtoupper(uniqid());
        } while (PurchaseOrder::where('purchase_number', $purchaseNumber)->exists());

        return $purchaseNumber;
    }

    public function confirmReorder($inventoryId)
    {
        $inventory = Inventory::with('inventoryItem', 'inventoryItem.item', 'purchaseOrders')
            ->find($inventoryId);

        $item = InventoryItem::with(['purchaseItem', 'purchaseItem.purchaseOrders'])
            ->where('inventoryId', $inventoryId)
            ->orderBy('inventory_item_id', 'desc') // Ensure 'inventory_item_id' is the correct column
            ->first();


        $purchaseOrder = PurchaseOrder::where('SupplierId', $inventory->SupplierId)

            ->latest('purchase_order_id')
            ->first();


        if ($inventory) {

            if ($purchaseOrder->status == 'Completed' || $purchaseOrder->status == 'Cancelled') {
                $purchaseOrder = PurchaseOrder::create([
                    'SupplierId' => $purchaseOrder->SupplierId,
                    'order_date' => now(),
                    'delivery_date' => now()->addDays(7),
                    'status' => 'Pending',
                    'purchase_number' => $this->generateUniquePurchaseNumber(),
                    'quantity' => $inventory->original_quantity,
                    'total_price' => $inventory->original_quantity * $item->unit_price
                ]);
            } else {
                $purchaseOrder->quantity += $inventory->original_quantity;
                $purchaseOrder->total_price += $inventory->original_quantity * $item->unit_price;
                $purchaseOrder->save();
            }




            PurchaseItem::create([
                'purchase_order_id' => $purchaseOrder->purchase_order_id,
                'itemID' => $item->itemID,
                'quantity' => $inventory->original_quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $inventory->original_quantity * $item->unit_price
            ]);

            // $purchaseOrder->items()->create([
            //     'itemID' => $inventory->itemID,
            //     'quantity' => $inventory->original_quantity,
            //     'unit_price' => $inventory->item->unitPrice,
            //     'total_price' => $inventory->original_quantity * $inventory->item->unitPrice,

            // ]);


            $inventory->status = 'Re-ordered';


            $inventory->save();

            session()->flash('message-status', 'Re-order has been confirmed successfully for Item: ' . $item->item->itemName);
        } else {
            session()->flash('message-status', 'Inventory item not found');
        }
    }

    protected function updatePurchaseOrder($inventory)
    {
        // if ($inventory->inventoryItems > 0) {
        //     $purchaseOrder = PurchaseOrder::create([
        //         'SupplierId' => $inventory->itemID,
        //         'order_date' => now(),
        //         'delivery_date' => now()->addDays(7),
        //         'status' => 'Pending'
        //     ]);

        //     PurchaseItem::create([
        //         'purchase_order_id' => $purchaseOrder->purchase_order_id,
        //         'itemID' => $inventory->itemID,
        //         'quantity' => $inventory->qtyonhand,
        //         'unit_price' => $inventory->item->unitPrice,
        //         'total_price' => $inventory->qtyonhand * $inventory->item->unitPrice
        //     ]);

        //     // Log to StockCard (Quantity In)
        //     StockCard::create([
        //         'inventoryId' => $inventory->inventoryId,
        //         'DateReceived' => now(),
        //         'QuantityIn' => $inventory->qtyonhand,
        //         'QuantityOut' => 0,
        //         'Type' => 'Order',
        //         'ValueIn' => $inventory->qtyonhand * $inventory->item->unitPrice,
        //         'Remarks' => 'Received'
        //     ]);
        // }
    }

    protected function createPurchaseOrder($inventory)
    {
        // if ($inventory->qtyonhand > 0) {
        //     $purchaseOrder = PurchaseOrder::create([
        //         'SupplierId' => $inventory->itemID,
        //         'order_date' => now(),
        //         'delivery_date' => now()->addDays(7),
        //         'status' => 'Pending',
        //     ]);

        //     PurchaseItem::create([
        //         'purchase_order_id' => $purchaseOrder->purchase_order_id,
        //         'itemID' => $inventory->itemID,
        //         'quantity' => $inventory->qtyonhand,
        //         'unit_price' => $inventory->item->unitPrice,
        //         'total_price' => $inventory->qtyonhand * $inventory->item->unitPrice,
        //     ]);
        // }
    }

    public function cancelReorder($inventoryId)
    {
        $inventory = Inventory::find($inventoryId);
        if ($inventory) {
            // Logic to cancel reorder if needed
            $inventory->status = null; // Adjust as necessary
            $inventory->save();
        }
    }

    public function editInventory($inventoryId)
    {
        $this->selectedInventory = Inventory::find($inventoryId);
    }
    // public function updatedSelectedItems($value) {}
    // public function viewStockCard() {}

    public function logSaleTransaction($inventoryId, $soldQuantity)
    {
        $inventory = Inventory::find($inventoryId);

        if ($inventory) {
            // Update quantity on hand
            $inventory->qtyonhand -= $soldQuantity;
            $inventory->save();

            // Log to StockCard (Quantity Out)
            StockCard::create([
                'inventoryId' => $inventory->inventoryId,
                'DateReceived' => now(),
                'QuantityIn' => 0,
                'QuantityOut' => $soldQuantity,
                'Type' => 'Sales',
                'ValueOut' => $soldQuantity * $inventory->item->unitPrice,
                'Remarks' => 'Send'
            ]);
        }
    }

    public function showReorderCard()
    {
        $this->reorderCard = true;
    }

    public function closeReorderCard()
    {
        $this->reorderCard = false;
    }

    public function closeStockModal()
    {
        $this->showStockCardModal = false;
        $this->adjustInventory = false;
        $this->saleReturn = false;
    }

    public function viewStockCard()
    {
        $this->showStockCardModal = true;
    }


    public function viewSaleReturn()
    {
        $this->saleReturn = true;
    }

    public function viewAdjustItem()
    {
        $this->adjustInventory = true;
    }

    public function saveSaleReturn()
    {
        // Fetch the inventory items associated with the selected item adjustment
        $inventoryItems = InventoryItem::with('purchaseItem.purchaseOrders')
            ->where('itemID', $this->selectedItemAdjustment)
            ->where('quantity', '>', 0)
            ->get();

        // Check if there are any inventory items available
        if ($inventoryItems->isEmpty()) {
            session()->flash('message-status', 'Inventory item not found or has zero quantity.');
            return;
        }

        $totalAvailableQuantity = $inventoryItems->sum('quantity');

        if ($this->quantity <= 0) {
            session()->flash('message-status', 'Invalid quantity');
            return;
        }

        if ($this->remarks == null) {
            session()->flash('message-status', 'Please select a remark');
            return;
        }

        // Validate if the total requested quantity exceeds the available quantity
        if ($this->quantity > $totalAvailableQuantity) {
            session()->flash('message-status', 'Requested quantity exceeds available inventory.');
            return;
        }

        $remainingQuantity = $this->quantity;
        $totalValue = 0;
        $supplierId = null;

        foreach ($inventoryItems as $inventory) {

            if ($inventory->quantity >= $remainingQuantity) {

                $inventory->quantity -= $remainingQuantity;
                $totalValue += $inventory->item->selling_price * $remainingQuantity;
                $supplierId = optional($inventory->purchaseItem->first()->purchaseOrders->first())->SupplierId;
                $inventory->save();

                break;
            } else {

                $totalValue += $inventory->item->selling_price * $inventory->quantity;
                $remainingQuantity -= $inventory->quantity;
                $supplierId = optional($inventory->purchaseItem->first()->purchaseOrders->first())->SupplierId;
                $inventory->quantity = 0;
                $inventory->save();
            }
        }


        StockCard::create([
            'DateReceived' => now(),
            'Type' => 'Sales Return',
            'Value' => $totalValue,
            'Quantity' => $this->quantity - $remainingQuantity,
            'supplierItemID' => $supplierId,
            'inventory_item_id' => $inventory->inventory_item_id,
            'Remarks' => $this->remarks,
        ]);


        if ($remainingQuantity > 0) {
            session()->flash('message-status', 'Sales Return has been partially processed. Remaining: ' . $remainingQuantity);
        } else {
            session()->flash('message-status', 'Sales Return has been updated successfully');
        }

        $this->showStockCardModal = false;
    }


    public function saveUpdate()
    {
        $this->validate([
            'quantity' => 'required|numeric|min:1',
            'remarks' => 'required'
        ]);


        $inventory = InventoryItem::with('purchaseItem.purchaseOrders')
            ->where('itemID', $this->selectedItemAdjustment)
            ->where('quantity', '>', 0)
            ->first();


        switch ($this->remarks) {
            case 'StockIn':
                $inventory->quantity += $this->quantity;
                $stockCardType = 'StockIn';
                $value = $inventory->item->sellingPrice * $this->quantity;
                break;

            case 'StockOut':
                // // Ensure sufficient quantity before subtracting

                $inventory->quantity -= $this->quantity;
                $stockCardType = 'StockOut';
                $value = $inventory->item->sellingPrice * $this->quantity;
                break;

            case 'Sales Return':
                $inventory->quantity += $this->quantity;
                // Assuming the selling price is stored in the item relation
                $value = $inventory->item->sellingPrice * $this->quantity;
                $stockCardType = 'SalesReturn';
                break;

            default:
                return response()->json(['error' => 'Invalid remark type'], 400);
        }

        // Save the adjusted inventory
        $inventory->save();
        $supplierId = optional($inventory->purchaseItem->first()->purchaseOrders->first())->SupplierId;
        StockCard::create([
            'DateReceived' => now(),
            'Type' => $stockCardType,
            'Value' => $value,
            'Quantity' => $this->quantity,
            'supplierItemID' => $supplierId,
            'inventory_item_id' => $inventory->inventoryId,

            'Remarks' => $this->remarks,
        ]);


        $this->showStockCardModal = false;

        session()->flash('message-status', 'Stock Card has been updated successfully');
    }

    public function saveBulkOrder()
    {


        foreach ($this->selectedItems as $inventoryId) {
            // Find the inventory record by its ID
            $inventory = Inventory::with('inventoryItem', 'inventoryItem.item', 'purchaseOrders')
                ->find($inventoryId); // Use the current ID from selectedItems

            if (!$inventory) {
                session()->flash('message-status', 'Inventory item not found for ID: ' . $inventoryId);
                continue; // Skip this iteration if inventory not found
            }

            // Fetch the latest InventoryItem associated with this inventory
            $item = InventoryItem::with(['purchaseItem', 'purchaseItem.purchaseOrders'])
                ->where('inventoryId', $inventoryId)
                ->orderBy('inventory_item_id', 'desc') // Ensure 'inventory_item_id' is the correct column
                ->first();

            // Fetch the latest PurchaseOrder for this Supplier
            $purchaseOrder = PurchaseOrder::where('SupplierId', $inventory->SupplierId)
                ->latest('purchase_order_id')
                ->first();

            // Logic to handle the purchase order creation/updating
            if ($purchaseOrder) {
                if ($purchaseOrder->status == 'Completed' || $purchaseOrder->status == 'Cancelled') {
                    // Create a new purchase order
                    $purchaseOrder = PurchaseOrder::create([
                        'SupplierId' => $purchaseOrder->SupplierId,
                        'order_date' => now(),
                        'delivery_date' => now()->addDays(7),
                        'status' => 'Pending',
                        'purchase_number' => $this->generateUniquePurchaseNumber(),
                        'quantity' => $inventory->original_quantity,
                        'total_price' => $inventory->original_quantity * $item->unit_price
                    ]);
                } else {
                    // Update existing purchase order
                    $purchaseOrder->quantity += $inventory->original_quantity;
                    $purchaseOrder->total_price += $inventory->original_quantity * $item->unit_price;
                    $purchaseOrder->save();
                }

                // Create a new PurchaseItem
                PurchaseItem::create([
                    'purchase_order_id' => $purchaseOrder->purchase_order_id,
                    'itemID' => $item->itemID,
                    'quantity' => $inventory->original_quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $inventory->original_quantity * $item->unit_price
                ]);

                // Update inventory status
                $inventory->status = 'Re-ordered';
                $inventory->save();

                // Flash a success message
                session()->flash('message-status', 'Re-order has been confirmed successfully for Item: ' . $item->item->itemName);
            } else {
                session()->flash('message-status', 'No purchase orders found for Supplier ID: ' . $inventory->SupplierId);
            }
        }
    }
}
