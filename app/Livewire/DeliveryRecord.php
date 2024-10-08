<?php
namespace App\Livewire;

use App\Models\PurchaseOrder;
use Livewire\Component;

class DeliveryRecord extends Component
{
    public $search = '';
    public $status;

    public function render()
    {
        $deliveries = PurchaseOrder::with('supplier')
            ->when($this->search, function ($query) {
                return $query->search($this->search);
            })->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->paginate(10);

        return view('livewire.delivery-record', compact('deliveries'));
    }

    public function clearSearch()
    {
        $this->search = '';
    }
}
