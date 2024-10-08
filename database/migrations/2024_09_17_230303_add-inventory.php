<?php

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
        Schema::create(
            'inventory',
            function (Blueprint $table) {
                $table->id('inventoryId');
                $table->foreignIdFor(Item::class, 'itemID');
                $table->integer('qtyonhand');
                $table->date('expiry_date');
                $table->date('date_received');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
