<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\StockCard;
use PDO;

class ItemManagement extends Component
{

    public $name;
    public $category;
    public $barcode;
    public $description;
    public $unitPrice;
    public $isVatable;
    public $search = '';
    public $selectedItem;
    public $vatable = [];

    public $supplier; // For Supplier data


    public $items; // This will hold all items

    public function render()
    {
        return view(
            'livewire.item-management',
            [
                'items' => Item::search($this->search)->paginate(10),
                'vatable' => Item::where('isVatable', true)->get()
            ]
        );
    }
    public function save()
    {

       $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'barcode' => 'nullable|string|size:13',
            'description' => 'nullable|string|max:500',
            'unitPrice' => 'required|numeric|min:0',
            'isVatable' => 'boolean'
        ], [
            // Custom validation messages...
        ]);


        Item::create([
            'itemName' => $this->name,
            'itemCategory' => $this->category,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'unitPrice' => $this->unitPrice,
            'sellingPrice' => $this->unitPrice * 1.2,
            'status' => 'Active',
            'isVatable' => $this->isVatable
        ]);


        // Reset fields
        $this->reset(['name', 'category', 'barcode', 'description', 'unitPrice', 'supplier']);
        session()->flash('message', 'Item successfully added.');
    }

    public function selectItem($id)
    {
        $item = Item::find($id);

        // Ensure the item exists before trying to access its properties
        if ($item) {
            $this->selectedItem = $item; // Store the selected item
            $this->name = $item->itemName; // Populate the item name
            $this->category = $item->itemCategory; // Populate the category
            $this->barcode = $item->barcode; // Populate the barcode
            $this->description = $item->description; // Populate the description
            $this->unitPrice = $item->unitPrice; // Populate the unit price
            // Optionally, you could also add the isVatable property if needed
            $this->isVatable = $item->isVatable;
        } else {
            // Handle the case where the item is not found
            session()->flash('error', 'Item not found.'); // Example of error handling
        }
    }


    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'barcode' => 'required|string|size:12',
            'description' => 'nullable|string|max:500',
            'unitPrice' => 'required|numeric|min:0',
            'isVatable' => 'boolean'
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'category.required' => 'The category field is required.',
            'category.string' => 'The category must be a string.',
            'category.max' => 'The category may not be greater than 255 characters.',
            'barcode.required' => 'The barcode field is required.',
            'barcode.string' => 'The barcode must be a string.',
            'barcode.size' => 'The barcode must be exactly 12 characters.',
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 500 characters.',
            'unitPrice.required' => 'The unit price field is required.',
            'unitPrice.numeric' => 'The unit price must be a number.',
            'unitPrice.min' => 'The unit price must be at least 0.',
        ]);


        $this->validate([
            'name' => 'required',
            'category' => 'required',
            'barcode' => 'required',
            'description' => 'required',
            'unitPrice' => 'required',
        ]);

        $item = Item::find($this->selectedItem->itemID);
        $item->update([
            'itemName' => $this->name,
            'itemCategory' => $this->category,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'unitPrice' => $this->unitPrice,
            'sellingPrice' => $this->unitPrice * 1.2, // calculate selling price as 20% more than unit price

            'isVatable' => $this->isVatable == null,
        ]);
        session()->flash('message', 'Item successfully update.');
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

    public function delete()
    {
        $item = Item::find($this->selectedItem->itemID);
        $item->delete();
        session()->flash('message', 'Item successfully deleted.');
    }


    public function mount()
    {
        $this->fetchItems();
        // Fetch vatable items when the component mounts
        $this->fetchVatableItems();
        $this->vatable = Item::where('isVatable', true)->get();
        $this->items = Item::all();
    }

    public function getVatableItems()
    {
        $this->vatable = Item::where('isVatable', true)->get(); // Adjust according to your database structure
    }
    public function fetchItems()
    {
        $this->items = Item::all(); // Replace with appropriate query if needed
    }
    public function fetchVatableItems()
    {
        // Fetch items marked as vatable from the database
        $this->vatable = Item::where('isVatable', true)->get(); // Adjust the query based on your schema
    }
}
