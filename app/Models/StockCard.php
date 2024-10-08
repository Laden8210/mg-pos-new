<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    use HasFactory;

    protected $table = 'stockcard';
    protected $primaryKey = 'StockCardID';

    public $timestamps = false;

    protected $fillable = [
        'DateReceived',
        'Type',
        'Value',
        'Quantity',
        'supplierItemID',
        'inventoryId',
        'Remarks',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventoryId');
    }


    public function supplierItem()
    {
        return $this->belongsTo(SupplierItem::class, 'supplierItemID');
    }

    public function supplier()
    {
        return $this->hasOneThrough(Supplier::class, SupplierItem::class, 'supplierItemID', 'supplierID', 'supplierItemID', 'SupplierId');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'supplierItemID'); // Make sure the foreign key matches
    }
}
