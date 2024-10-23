<?php

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
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('sellingPrice');
            $table->dropColumn('unitPrice');
        });

        Schema::table('inventory', function (Blueprint $table) {
            $table->decimal('unitsale', 8, 2)->nullable();
            $table->decimal('selling_price', 8, 2)->nullable();
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
