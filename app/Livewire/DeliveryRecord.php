<?php
namespace App\Livewire;

use App\Models\PurchaseItem;
use App\Models\PurchaseOrder;
use Livewire\Component;

class DeliveryRecord extends Component
{
    public $search = '';
    public $status;

    public $orderDetails;

    public $itemEditName;

    public $itemEditQuantity;
    public $selectedEditItem;
    public $itemEditPrice;
    public $itemExpirationDate;

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

    public function viewOrderDetails($id)
    {
        $this->orderDetails = PurchaseItem::with('item', 'inventoryItem')
            ->where('purchase_order_id', $id)
            ->get();

    }

    public function editItem($id)
    {

        $this->selectedEditItem = PurchaseItem::find($id);
        $this->itemEditName = $this->selectedEditItem->item->itemName;
        $this->itemEditQuantity = $this->selectedEditItem->quantity;

        $this->itemEditPrice = $this->selectedEditItem->unit_price;

    }

    public function updateItem()
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

        // expiry date validation

        if ($this->itemExpirationDate < now()) {
            session()->flash('error', 'Expiry date cannot be in the past.');
            return;
        }

        $this->selectedEditItem->update([
            'quantity' => $this->itemEditQuantity,
            'unit_price' => $this->itemEditPrice,
        ]);

        $this->selectedEditItem->inventoryItem->update(
            [
                'quantity' => $this->itemEditQuantity,
                'received_date' => now(),
                'unit_price' => $this->itemEditPrice,
                'selling_price' => $this->itemEditPrice * 1.2,
                'expiry_date' => $this->itemExpirationDate
            ]
        );

        session()->flash('message', 'Item and inventory updated successfully.');


    }
}
