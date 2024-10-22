<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = "items";
    protected $primaryKey = "itemID";

    protected $fillable = [
        'itemName',
        'itemCategory',
        'barcode',
        'description',
        'status',
        'vatApplicable',
        'isVatable'
    ];

    // Relationship to Inventory
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function transactions()
    {
        // Explicitly set the foreign key if it's different from the default `item_id`
        return $this->hasMany(Transactions::class, 'itemID', 'itemID');
    }

    public function supplierItem()
    {
        return $this->hasMany(SupplierItem::class, 'itemID', 'itemID'); // Correct key reference
    }


    // Search scope for filtering items
    public function scopeSearch($query, $value)
    {
        return $query->where('itemName', 'like', '%' . $value . '%')
            ->orWhere('itemCategory', 'like', '%' . $value . '%')
            ->orWhere('description', 'like', '%' . $value . '%')

            ->orWhere('status', 'like', '%' . $value . '%')
            ->orWhere('barcode', 'like', '%' . $value . '%');
    }

    public function inventoryItem()
    {
        return $this->hasMany(inventoryItem::class, 'itemID', 'itemID');
    }
}
