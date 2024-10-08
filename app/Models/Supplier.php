<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    public $timestamps = false;
    protected $primaryKey = 'SupplierId';

    protected $fillable = [
        'CompanyName',
        'ContactPerson',
        'ContactNumber',
        'Address',
        'Status',
    ];

    // Search scope for filtering suppliers
    public function scopeSearch($query, $value)
    {
        return $query->where('CompanyName', 'like', '%' . $value . '%')
                     ->orWhere('ContactPerson', 'like', '%' . $value . '%')
                     ->orWhere('ContactNumber', 'like', '%' . $value . '%')
                     ->orWhere('Address', 'like', '%' . $value . '%')
                     ->orWhere('Status', 'like', '%' . $value . '%');
    }

    // Relationship to SupplierItem
    public function supplierItems()
    {
        return $this->hasMany(SupplierItem::class, 'supplierID', 'SupplierId'); // Ensure correct keys
    }

    // Relationship to Inventory
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'SupplierId', 'SupplierId'); // Ensure correct keys
    }

}
