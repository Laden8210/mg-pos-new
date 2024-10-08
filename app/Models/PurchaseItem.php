<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_item';
    protected $primaryKey = 'purchase_item_id';

    protected $fillable = [
        'purchase_order_id',
        'inventoryId',
        'itemID',
        'quantity',
        'unit_price',
        'total_price'
    ];

    // Relationship to PurchaseOrder
    public function purchaseOrders()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'purchase_order_id');
    }

    // Relationship to Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'itemID', 'itemID');
    }
}
