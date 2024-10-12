<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;


    protected $table = 'transactions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'transaction_id',
        'transaction_no',
        'itemID',
        'selling_price',
        'quantity',
        'date_created',
        'total_sell'
    ];


    public function item()
    {
        // Explicitly set the foreign key if it's different from the default `item_id`
        return $this->belongsTo(Item::class, 'itemID', 'itemID');
    }

    public function saleTransaction()
    {
        return $this->belongsTo(SaleTransactionModel::class, 'sales_transaction_id', 'sales_transaction_id');
    }

}
