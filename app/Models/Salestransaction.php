<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salestransaction extends Model
{
    use HasFactory;

    // Define the table if it's not following Laravel's naming convention
    protected $table = 'salestransaction_detail'; // Adjust the table name if necessary

    // Specify which attributes should be mass-assignable
    protected $fillable = [
        'SaleTransaction_DetailID',
        'itemNumber',
        'QuantitySold',
        'Subtotal',
        'supplierItemID', // Updated to match the correct field name
    ];

    // If you have timestamps in your table, Laravel will manage them automatically
    public $timestamps = true; // Set to false if you do not have created_at and updated_at fields

    // Define the relationship to the SupplierItem model
    public function supplierItem()
    {
        return $this->belongsTo(SupplierItem::class, 'supplierItemID', 'supplierItemID'); // Updated to match the field name
    }
}
