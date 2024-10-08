<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\StockCard;

class StockMovementReport extends Component
{
    public $stockId;
    public $itemId, $itemName, $companyName, $barcode, $expirationDate, $batch, $contactPerson;
    public $stockCards = [];
    public $items = []; // Declare the items variable


    public function mount()
    {
        // Fetch items from the database
        $this->items = Item::all(); // Adjust this based on your Item model
    }

    public function updatedItemId($value)
    {
        $item = Item::find($value); // Fetch item details based on ID

        if ($item) {
            // Set the values to display
            $this->itemName = $item->itemName;
            $this->companyName = $item->companyName; // Assuming company name is a field in Item
            $this->barcode = $item->barcode; // Assuming barcode is a field in Item
            $this->expirationDate = $item->expirationDate; // Assuming this field exists
            $this->batch = $item->batch; // Assuming this field exists

            // Fetch stock card details based on the selected item
            $this->stockCards = StockCard::where('itemID', $value)->get(); // Adjust based on your StockCard model
        } else {
            // Reset values if no item is found
            $this->resetItemDetails();
        }
    }
    public function resetItemDetails()
    {
        $this->itemName = '';
        $this->companyName = '';
        $this->barcode = '';
        $this->expirationDate = '';
        $this->batch = '';
        $this->stockCards = [];
    }
    
    public function updatedStockId($value)
    {
        // Fetch the selected inventory item based on stockId
        $inventory = Inventory::with(['item', 'supplier', 'stockCards'])
            ->where('inventoryId', $value)
            ->first();

        if ($inventory) {
            // Populate fields with inventory data
            $this->itemId = $inventory->itemID;
            $this->itemName = $inventory->item->itemName ?? 'N/A';
            $this->companyName = $inventory->supplier->companyName ?? 'N/A';
            $this->barcode = $inventory->item->barcode ?? 'N/A';
            $this->expirationDate = $inventory->expiry_date ?? 'N/A';
            $this->batch = $inventory->batch ?? 'N/A';
            $this->contactPerson = $inventory->supplier->contact_person ?? 'N/A';

            // Populate stock cards related to this inventory item
            $this->stockCards = $inventory->stockCards;
        } else {
            $this->resetFields();
        }
    }

    public function resetFields()
    {
        $this->itemId = '';
        $this->itemName = '';
        $this->companyName = '';
        $this->barcode = '';
        $this->expirationDate = '';
        $this->batch = '';
        $this->contactPerson = '';
        $this->stockCards = [];
    }

    public function render()
    {
        $stocks = Inventory::with('item')->get(); // Fetch stocks
        \Log::info('Stocks retrieved:', $stocks->toArray()); // Log the stocks

        return view('livewire.stock-movement-report', [
            'stocks' => $stocks,
        ]);
    }
}
