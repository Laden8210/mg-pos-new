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

        Schema::create('items', function (Blueprint $table) {
            $table->id('itemID');
            $table->string('itemName');
            $table->string('itemCategory');
            $table->string('barcode')->nullable()->change();
            $table->string('description')->nullable();
            $table->decimal('unitPrice', 8, 2);
            $table->string('status');
            $table->boolean('vatApplicable')->default(0);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
