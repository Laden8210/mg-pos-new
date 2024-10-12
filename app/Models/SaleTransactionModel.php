<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleTransactionModel extends Model
{
    use HasFactory;


    protected $table = 'sale_transaction';

    protected $primaryKey = 'sales_transaction_id';

    protected $fillable = [
        'transaction_number',
        'total_amount',
        'amount_tendered',
        'change',
        'vat',
        'subtotal',
        'total_items',
        'employee_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'sales_transaction_id', 'sales_transaction_id');
    }






}
