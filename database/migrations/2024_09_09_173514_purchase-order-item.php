<?php

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id('purchase_order_id');
            $table->foreignIdFor(Supplier::class, 'SupplierId');
            $table->integer('quantity');
            $table->decimal('total_price', 8, 2);
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('purchase_item', function (Blueprint $table) {
            $table->id('purchase_item_id');
            $table->foreignIdFor(PurchaseOrder::class, 'purchase_order_id');
            $table->foreignIdFor(Item::class, 'itemID');
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
