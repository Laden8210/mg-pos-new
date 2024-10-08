<?php

use App\Models\Inventory;
use App\Models\SupplierItem;
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
        Schema::create('stockcard', function (Blueprint $table) {
            $table->id('StockCardID');
            $table->date('DateReceived');
            $table->string('Type');
            $table->decimal('Value', 8, 2);
            $table->integer('Quantity');
            $table->foreignIdFor(SupplierItem::class, 'supplierItemID');
            $table->foreignIdFor(Inventory::class, 'inventoryId');
            $table->string('Remarks');
        });
    }


    // // ];

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
