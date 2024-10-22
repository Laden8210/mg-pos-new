<?php

use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id('inventory_item_id');
            $table->foreignIdFor(Item::class, 'itemID');
            $table->foreignIdFor(Inventory::class, 'inventoryId');
            $table->date('expiry_date')->nullable();
            $table->date('received_date');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->integer('quantity');
            $table->timestamps();
        });
        // Schema::table('inventory', function (Blueprint $table) {
        //     $table->dropColumn(['expiry_date', 'date_received', 'qtyonhand', 'batch', 'SupplierId', 'itemID']);
        //     $table->integer('re_order_point')->nullable();

        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
