<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $table = 'inventory_items';
    protected $primaryKey = 'inventory_item_id';


    protected $fillable = [
        'itemID',
        'inventoryId',
        'expiry_date',
        'received_date',
        'unit_price',
        'selling_price',
        'quantity',
        'purchase_item_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'itemID');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventoryId');
    }

    public function stockCard()
    {
        return $this->hasMany(StockCard::class, 'inventory_item_id');
    }


    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class, 'purchase_item_id', 'purchase_item_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'purchase_item_id', 'purchase_item_id');
    }


}
