<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Inventory;

class InventoryAdjustment extends Component
{
    use WithPagination;

    public $inventories;

    public function mount()
    {
        // Fetch paginated inventories
        $this->inventories = Inventory::paginate(10);
    }

    public function render()
    {
        return view('livewire.inventory-adjustment', [
            'inventories' => $this->inventories
        ]);
    }
}