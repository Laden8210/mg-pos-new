<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'supplieritem';
    protected $primaryKey = 'supplierItemID';
    protected $fillable = [
        'SupplierId',
        'itemID',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierId', 'SupplierId');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'itemID', 'itemID');
    }
}
