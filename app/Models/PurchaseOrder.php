<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';
    protected $primaryKey = 'purchase_order_id';

    protected $fillable = [
        'purchase_number',
        'SupplierId',
        'quantity',
        'total_price',
        'order_date',
        'delivery_date',
        'status',
    ];


    // In the PurchaseOrder model
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'SupplierId'); // Adjust as necessary
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_order_id');
    }

    // Relationship to PurchaseItem
    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_order_id');
    }

    // Search scope for filtering purchase orders by supplier
    public function scopeSearch($query, $search)
    {
        return $query->whereHas('supplier', function ($query) use ($search) {
            $query->where('CompanyName', 'like', '%' . $search . '%')
                ->orWhere('ContactPerson', 'like', '%' . $search . '%')
                ->orWhere('ContactNumber', 'like', '%' . $search . '%');
        });
    }

    // Calculate total value of items in the purchase order
    public function calculateTotalValue()
    {
        return $this->items->sum('total_price'); // Ensure PurchaseItem has 'total_price'
    }
}
