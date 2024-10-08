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
        'batch',
        'itemID',
        'itemName',
        'description',
        'itemCategory',
        'qtyonhand',
        'status',
        'original_quantity',
        'expiry_date',
        'date_received',
        'SaleReturnID',
        'supplierItemID',
        'SupplierId',

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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierId');
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
        return $query->where('itemID', 'like', '%' . $value . '%')
            ->orWhere('qtyonhand', 'like', '%' . $value . '%')
            ->orWhere('expiry_date', 'like', '%' . $value . '%')
            ->orWhere('date_received', 'like', '%' . $value . '%')
            ->orWhereHas('item', function ($query) use ($value) {
                $query->where('itemName', 'like', '%' . $value . '%')
                    ->orWhere('itemCategory', 'like', '%' . $value . '%');
            });
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
