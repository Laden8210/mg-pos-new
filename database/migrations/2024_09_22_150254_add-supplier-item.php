<?php

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
        Schema::create('supplieritem', function (Blueprint $table) {
            $table->id('supplierItemID');
            $table->foreignIdFor(Supplier::class, 'supplierID');
            $table->foreignIdFor(Item::class, 'itemID');
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
