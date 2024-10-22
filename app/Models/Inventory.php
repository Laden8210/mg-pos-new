<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'inventoryId';

    public $timestamps = false;

    protected $fillable = [
        'inventoryId',
        'original_quantity',
        'status',
        're_order_point',
        'SupplierId'

    ];

    // Relationship to the Item model
    public function item()
    {
        return $this->belongsTo(Item::class, 'itemID'); // Ensure this matches your foreign key
    }
    // Relationship to the SupplierItem model
    public function supplierItem()
    {
        return $this->belongsTo(SupplierItem::class, 'itemID', 'ItemID');
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierId');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventoryId');
    }

    // Relationship to PurchaseItem
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'inventoryId', 'inventoryId'); // Adjust if necessary
    }

    // Relationship to the PurchaseOrder model through PurchaseItem
    public function purchaseOrders()
    {
        return $this->hasManyThrough(PurchaseOrder::class, PurchaseItem::class, 'inventoryId', 'purchase_order_id', 'inventoryId', 'purchase_order_id');
    }


    public function inventoryItem()
    {
        return $this->hasMany(InventoryItem::class, 'inventoryId', 'inventoryId');
    }


    public function stockCards()
    {
        return $this->hasMany(StockCard::class, 'inventoryId', 'inventoryId');
    }

    protected $casts = [
        'expiry_date' => 'datetime',
    ];



    // Scope for searching inventory
    public function scopeSearch($query, $value)
    {
        return $query->where('inventoryId', 'like', '%' . $value . '%')
            ->orWhere('original_quantity', 'like', '%' . $value . '%')
            ->orWhere('status', 'like', '%' . $value . '%')
            ->orWhere('re_order_point', 'like', '%' . $value . '%');
    }

    // Calculate the reorder point based on original quantity
    public function getReorderPointAttribute()
    {
        $threshold = 0.3; // 30% threshold to trigger reorder point
        $originalQty = $this->original_quantity;

        return $originalQty * $threshold;
    }
    // Relationship to the Supplier model through PurchaseOrder
    public function suppliersThroughPurchaseOrders()
    {
        return $this->hasManyThrough(Supplier::class, PurchaseItem::class, 'inventoryId', 'SupplierId', 'inventoryId', 'itemID');
    }

}
