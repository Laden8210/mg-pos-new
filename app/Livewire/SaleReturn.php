<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;

class SaleReturn extends Component
{
    public $items;

    public function mount()
    {
        $this->items = Item::all();
    }

    public function render()
    {
        return view('livewire.cashier.sale-return');
    }
}
