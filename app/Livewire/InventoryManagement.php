<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseItem;
use App\Models\StockCard;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

    public function mount()
    {
        $this->search = '';
        $this->selectedInventory = null;
    }

    public function updatedSelectedItem($value)
    {
        // This method will be called whenever selectedItem changes
        $this->stockCardInventories = StockCard::whereHas('inventory', function ($query) use ($value) {
                $query->where('itemID', $value);
            })->with('inventory')->get();
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

        dd($inventory);

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

        // Load related models with `with()`, aggregate fields with `selectRaw()`
        $inventories = Inventory::with(['item', 'purchaseItems', 'purchaseOrders', 'supplierItem', 'supplier']) // Load relationships
            ->selectRaw('inventoryId, batch, itemID, qtyonhand as total_qtyonhand, original_quantity as total_original_quantity, SupplierId, expiry_date') // Aggregate inventory fields
            ->when($this->search, function ($query) {
                $query->whereHas('purchaseOrders', function ($query) {
                    $query->where('SupplierName', 'like', '%' . $this->search . '%');
                });
            })
            ->where('qtyonhand', '>', 0) // Filter out items with zero quantity
            ->paginate(10);

        foreach ($inventories as $inventory) {
            $this->checkReorderPoint($inventory); // Process reorder point check
        }
        $items = Inventory::with('item')
            ->groupBy('itemID')
            ->selectRaw('itemID, MAX(inventoryId) as inventoryId')
            ->get();





        return view('livewire.inventory-management', [
            'inventories' => $inventories, // Pass inventory data
            'supplies' => $supplies, // Pass supplier data
            'supplier' => $supplier, // Pass supplier data
            'items' => $items, // Pass items data
            'stockCardInventories' => $this->stockCardInventories, // Pass stock card inventories
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
        $inventory = Inventory::find($inventoryId);

        if ($inventory) {


            // if ($purchaseOrder) {
            //     $this->updatePurchaseOrder($purchaseOrder, $inventory);
            // } else {
            //     $this->createPurchaseOrder($inventory);
            // }

            $purchaseOrder = PurchaseOrder::create([
                'SupplierId' => $inventory->SupplierId,
                'order_date' => now(),
                'delivery_date' => now()->addDays(7),
                'status' => 'Pending',
                'purchase_number' => $this->generateUniquePurchaseNumber(),
                'quantity' => $inventory->original_quantity,
                'total_price' => $inventory->original_quantity * $inventory->item->unitPrice
            ]);


            PurchaseItem::create([
                'purchase_order_id' => $purchaseOrder->purchase_order_id,
                'itemID' => $inventory->itemID,
                'quantity' => $inventory->original_quantity,
                'unit_price' => $inventory->item->unitPrice,
                'total_price' => $inventory->original_quantity * $inventory->item->unitPrice
            ]);

            // $purchaseOrder->items()->create([
            //     'itemID' => $inventory->itemID,
            //     'quantity' => $inventory->original_quantity,
            //     'unit_price' => $inventory->item->unitPrice,
            //     'total_price' => $inventory->original_quantity * $inventory->item->unitPrice,

            // ]);


            $inventory->status = 'Re-ordered';


            $inventory->save();

            session()->flash('message-status', 'Re-order has been confirmed successfully for Item: ' . $inventory->item->itemName);
        } else {
            session()->flash('message-status', 'Inventory item not found');
        }
    }

    protected function updatePurchaseOrder($inventory)
    {
        if ($inventory->qtyonhand > 0) {
            $purchaseOrder = PurchaseOrder::create([
                'SupplierId' => $inventory->itemID,
                'order_date' => now(),
                'delivery_date' => now()->addDays(7),
                'status' => 'Pending'
            ]);

            PurchaseItem::create([
                'purchase_order_id' => $purchaseOrder->purchase_order_id,
                'itemID' => $inventory->itemID,
                'quantity' => $inventory->qtyonhand,
                'unit_price' => $inventory->item->unitPrice,
                'total_price' => $inventory->qtyonhand * $inventory->item->unitPrice
            ]);

            // Log to StockCard (Quantity In)
            StockCard::create([
                'inventoryId' => $inventory->inventoryId,
                'DateReceived' => now(),
                'QuantityIn' => $inventory->qtyonhand,
                'QuantityOut' => 0,
                'Type' => 'Order',
                'ValueIn' => $inventory->qtyonhand * $inventory->item->unitPrice,
                'Remarks' => 'Received'
            ]);
        }
    }

    protected function createPurchaseOrder($inventory)
    {
        if ($inventory->qtyonhand > 0) {
            $purchaseOrder = PurchaseOrder::create([
                'SupplierId' => $inventory->itemID,
                'order_date' => now(),
                'delivery_date' => now()->addDays(7),
                'status' => 'Pending',
            ]);

            PurchaseItem::create([
                'purchase_order_id' => $purchaseOrder->purchase_order_id,
                'itemID' => $inventory->itemID,
                'quantity' => $inventory->qtyonhand,
                'unit_price' => $inventory->item->unitPrice,
                'total_price' => $inventory->qtyonhand * $inventory->item->unitPrice,
            ]);
        }
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

    public function closeStockModal()
    {
        $this->showStockCardModal = false;
        $this->adjustInventory = false;
    }

    public function viewStockCard()
    {
        $this->showStockCardModal = true;
    }

    public function viewAdjustItem()
    {
        $this->adjustInventory = true;
    }

    public function saveUpdate()
    {
        $this->validate([
            'quantity' => 'required|numeric|min:1',
            'remarks' => 'required'
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
                $stockCardType = 'SalesReturn';
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
            'Value' => $stockCardType === 'SalesReturn' ? $value : 0,
            'Quantity' => $this->quantity,
            'supplierItemID' => $inventory->SupplierId,
            'inventoryId' => $inventory->inventoryId,
            'Remarks' => $this->remarks,
        ]);


        $this->showStockCardModal = false;

        session()->flash('message-status', 'Stock Card has been updated successfully');
    }

    

}
