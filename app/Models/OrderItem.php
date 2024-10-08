<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;


    protected $primaryKey = 'orderItemID';
    protected $fillable = [
        'quantity', 'orderAmount', 'itemID'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'itemID', 'itemID');
    }
}
